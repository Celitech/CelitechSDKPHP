<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetPurchaseConsumptionOkResponse
{
    /**
     * Remaining balance of the package in bytes
     */
    #[SerializedName('dataUsageRemainingInBytes')]
    public float $dataUsageRemainingInBytes;

    /**
     * Status of the connectivity, possible values are 'ACTIVE' or 'NOT_ACTIVE'
     */
    #[SerializedName('status')]
    public string $status;

    public function __construct(float $dataUsageRemainingInBytes, string $status)
    {
        $this->dataUsageRemainingInBytes = $dataUsageRemainingInBytes;
        $this->status = $status;
    }
}
