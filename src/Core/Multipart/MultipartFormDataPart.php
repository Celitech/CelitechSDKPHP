<?php

declare(strict_types=1);

namespace Celitech\Core\Multipart;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\StreamInterface;

class MultipartFormDataPart
{
  /**
   * @var string
   */
  private string $name;

  /**
   * @var StreamInterface|string
   */
  private StreamInterface|string $contents;

  /**
   * @var ?string
   */
  private ?string $filename;

  /**
   * @var ?array<string, string>
   */
  private ?array $headers;

  /**
   * @param string $name
   * @param string|bool|float|int|StreamInterface $value
   * @param ?string $filename
   * @param ?array<string, string> $headers
   */
  public function __construct(
    string $name,
    string|bool|float|int|StreamInterface $value,
    ?string $filename = null,
    ?array $headers = null
  ) {
    $this->name = $name;
    $this->contents = $value instanceof StreamInterface ? $value : (string) $value;
    $this->filename = $filename;
    $this->headers = $headers;
  }

  /**
   * Adds this part to a MultipartStreamBuilder.
   *
   * @param MultipartStreamBuilder $builder
   */
  public function addToBuilder(MultipartStreamBuilder $builder): void
  {
    $options = array_filter(
      [
        'filename' => $this->filename,
        'headers' => $this->headers
      ],
      fn($value) => $value !== null
    );

    $builder->addResource($this->name, $this->contents, $options);
  }

  /**
   * Renders this part in the array shape Guzzle's `multipart` request option expects.
   *
   * Generated `toMultipart()` builds a Guzzle payload, not a MultipartStreamBuilder, so a part
   * originating from `File::toMultipartFormDataPart()` has to cross between the two. Guzzle
   * rejects anything that is not a string, resource or StreamInterface, so the stream is handed
   * over rather than the File wrapping it.
   *
   * @return array<string, mixed>
   */
  public function toGuzzlePart(): array
  {
    return array_filter(
      [
        'name' => $this->name,
        'contents' => $this->contents,
        'filename' => $this->filename,
        'headers' => $this->headers
      ],
      fn($value) => $value !== null
    );
  }
}
