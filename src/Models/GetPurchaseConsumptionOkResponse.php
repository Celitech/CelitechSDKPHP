<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetPurchaseConsumptionOkResponse implements \JsonSerializable
{
    /**
     * Remaining balance of the package in bytes
     */
    #[SerializedName('dataUsageRemainingInBytes')]
    public float $dataUsageRemainingInBytes;

    /**
     * Remaining balance of the package in GB
     */
    #[SerializedName('dataUsageRemainingInGB')]
    public float $dataUsageRemainingInGb;

    /**
     * Status of the connectivity, possible values are 'ACTIVE' or 'NOT_ACTIVE'
     */
    #[SerializedName('status')]
    public string $status;

    public function __construct(float $dataUsageRemainingInBytes, float $dataUsageRemainingInGb, string $status)
    {
        $this->dataUsageRemainingInBytes = $dataUsageRemainingInBytes;
        $this->dataUsageRemainingInGb = $dataUsageRemainingInGb;
        $this->status = $status;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            dataUsageRemainingInBytes: $data['dataUsageRemainingInBytes'] ?? null,
            dataUsageRemainingInGb: $data['dataUsageRemainingInGB'] ?? null,
            status: $data['status'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'dataUsageRemainingInBytes' => $this->dataUsageRemainingInBytes,
            'dataUsageRemainingInGB' => $this->dataUsageRemainingInGb,
            'status' => $this->status,
        ];
    }
}
