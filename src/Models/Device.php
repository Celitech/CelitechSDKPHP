<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Device implements \JsonSerializable
{
  /**
   * Name of the OEM
   */
  #[SerializedName('oem')]
  public string $oem;

  /**
   * Name of the Device
   */
  #[SerializedName('hardwareName')]
  public string $hardwareName;

  /**
   * Model of the Device
   */
  #[SerializedName('hardwareModel')]
  public string $hardwareModel;

  /**
   * Serial Number of the eSIM
   */
  #[SerializedName('eid')]
  public string $eid;

  public function __construct(string $oem, string $hardwareName, string $hardwareModel, string $eid)
  {
    $this->oem = $oem;
    $this->hardwareName = $hardwareName;
    $this->hardwareModel = $hardwareModel;
    $this->eid = $eid;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      oem: $data['oem'] ?? null,
      hardwareName: $data['hardwareName'] ?? null,
      hardwareModel: $data['hardwareModel'] ?? null,
      eid: $data['eid'] ?? null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'oem' => $this->oem,
      'hardwareName' => $this->hardwareName,
      'hardwareModel' => $this->hardwareModel,
      'eid' => $this->eid
    ];

    return $result;
  }
}
