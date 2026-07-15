<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimDeviceOkResponse implements \JsonSerializable
{
  #[SerializedName('device')]
  public Device $device;

  public function __construct(Device $device)
  {
    $this->device = $device;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      device: isset($data['device']) && is_array($data['device'])
        ? Device::fromArray($data['device'])
        : null
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['device'] = $this->device;
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'device',
        'contents' => json_encode($this->device)
      ]
    ];
  }

  public function validate(): void
  {
    $this->device->validate();
  }
}
