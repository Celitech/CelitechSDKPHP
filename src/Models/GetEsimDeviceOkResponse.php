<?php

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
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(device: $data['device'] ?? null);
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'device' => $this->device,
        ];
    }
}
