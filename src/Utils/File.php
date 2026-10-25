<?php

declare(strict_types=1);

namespace Celitech\Utils;

use Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\StreamInterface;
use Celitech\Core\Multipart\MultipartFormDataPart;

/**
 * File is a utility class used to transfer files as multipart form data.
 */
class File
{
  /** @var StreamInterface */
  private StreamInterface $stream;

  /** @var ?string */
  private ?string $filename;

  /** @var ?string */
  private ?string $contentType;

  /**
   * Whether this instance created the stream and may therefore close it. Streams handed to the
   * public constructor belong to the caller, so destruction must leave them alone.
   *
   * @var bool
   */
  private bool $ownsStream = false;

  /**
   * Whether the stream has been handed to a multipart part. Once it has, the body is owned by the
   * outgoing request and closing it here would detach it mid-flight.
   *
   * @var bool
   */
  private bool $streamReleased = false;

  /**
   * @param StreamInterface $stream
   * @param ?string $filename
   * @param ?string $contentType
   */
  public function __construct(
    StreamInterface $stream,
    ?string $filename = null,
    ?string $contentType = null
  ) {
    $this->filename = $filename;
    $this->contentType = $contentType;
    $this->stream = $stream;
  }

  /**
   * Creates a File instance from a filepath.
   *
   * @param string $filepath
   * @param ?string $filename
   * @param ?string $contentType
   * @return File
   * @throws Exception
   */
  public static function createFromFilepath(
    string $filepath,
    ?string $filename = null,
    ?string $contentType = null
  ): File {
    // Cleared first so error_get_last() below cannot report a stale error from earlier in the
    // request when fopen fails without setting one.
    error_clear_last();
    $resource = @fopen($filepath, 'r');
    if (!$resource) {
      $reason = error_get_last()['message'] ?? 'unknown error';
      throw new Exception("Unable to open file $filepath: $reason");
    }
    $stream = Psr17FactoryDiscovery::findStreamFactory()->createStreamFromResource($resource);
    if (!$stream->isReadable()) {
      // The stream owns the resource now, so closing it releases the descriptor. Without this
      // the handle leaks for the life of the process on every unreadable file.
      $stream->close();
      throw new Exception("File $filepath is not readable");
    }
    $file = new self(
      stream: $stream,
      filename: $filename ?? basename($filepath),
      contentType: $contentType
    );
    $file->ownsStream = true;

    return $file;
  }

  /**
   * Creates a File instance from a string.
   *
   * @param string $content
   * @param ?string $filename
   * @param ?string $contentType
   * @return File
   */
  public static function createFromString(
    string $content,
    ?string $filename,
    ?string $contentType = null
  ): File {
    $file = new self(
      stream: Psr17FactoryDiscovery::findStreamFactory()->createStream($content),
      filename: $filename,
      contentType: $contentType
    );
    $file->ownsStream = true;

    return $file;
  }

  /**
   * Maps this File into a multipart form data part.
   *
   * @param string $name The name of the multipart form data part.
   * @param ?string $contentType Overrides the Content-Type associated with the file, if any.
   * @return MultipartFormDataPart
   */
  public function toMultipartFormDataPart(
    string $name,
    ?string $contentType = null
  ): MultipartFormDataPart {
    $contentType ??= $this->contentType;
    $headers = $contentType !== null ? ['Content-Type' => $contentType] : null;

    $this->streamReleased = true;

    return new MultipartFormDataPart(
      name: $name,
      value: $this->stream,
      filename: $this->filename,
      headers: $headers
    );
  }

  /**
   * Closes the file stream.
   */
  public function close(): void
  {
    $this->stream->close();
  }

  /**
   * Closes the stream on garbage collection, but only when doing so cannot damage someone else's
   * data: the stream must be one this instance created, and must not have been handed to a
   * multipart part. An explicit close() is always honoured; this implicit path is deliberately
   * conservative, because a File going out of scope after toMultipartFormDataPart() would
   * otherwise detach the request body and send an empty upload.
   */
  public function __destruct()
  {
    if (!$this->ownsStream || $this->streamReleased) {
      return;
    }

    try {
      $this->close();
    } catch (\Throwable) {
      // Swallow errors during garbage collection to avoid fatal errors.
    }
  }
}
