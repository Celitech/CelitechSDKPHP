<?php

declare(strict_types=1);

namespace Celitech\ESim\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class GetEsimHistoryResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  #[JsonProperty('esim', required: true)]
  public \Celitech\ESim\Types\GetEsimHistoryResponseEsim $esim;

  /**
   * @param array{
   *   esim: \Celitech\ESim\Types\GetEsimHistoryResponseEsim,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->esim = $values['esim'];

    $this->_dirtyFields = [
      'esim' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'esim',
      'contents' => json_encode($this->esim),
      'headers' => ['Content-Type' => 'application/json']
    ];

    return $parts;
  }

  public function validate(): void
  {
    $this->esim->validate();
  }
}
