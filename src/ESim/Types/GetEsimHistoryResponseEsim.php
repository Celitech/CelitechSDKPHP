<?php

declare(strict_types=1);

namespace Celitech\ESim\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;
use Celitech\Core\Types\ArrayType;
use Celitech\Utils\Validator;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class GetEsimHistoryResponseEsim extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * ID of the eSIM
   */
  #[JsonProperty('iccid', required: true)]
  public string $iccid;

  /**
   * @var \Celitech\ESim\Types\GetEsimHistoryResponseEsimHistoryItem[]
   */
  #[
    JsonProperty('history', required: true),
    ArrayType([\Celitech\ESim\Types\GetEsimHistoryResponseEsimHistoryItem::class])
  ]
  public array $history;

  /**
   * @param array{
   *   iccid: string,
   *   history: \Celitech\ESim\Types\GetEsimHistoryResponseEsimHistoryItem[],
   * } $values
   */
  public function __construct(array $values)
  {
    $this->iccid = $values['iccid'];
    $this->history = $values['history'];

    $this->_dirtyFields = [
      'iccid' => true,
      'history' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'iccid',
      'contents' => $this->iccid
    ];

    $parts[] = [
      'name' => 'history',
      'contents' => json_encode($this->history),
      'headers' => ['Content-Type' => 'application/json']
    ];

    return $parts;
  }

  public function validate(): void
  {
    Validator::validateString($this->iccid, 'iccid', minLength: 18, maxLength: 22);
    foreach ($this->history as $item) {
      $item->validate();
    }
  }
}
