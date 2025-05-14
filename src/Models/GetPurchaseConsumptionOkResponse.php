<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetPurchaseConsumptionOkResponse
{
    /**
	 * Remaining balance of the package in byte. 
For ``limited packages``, this field indicates the remaining data in bytes. 
For ``unlimited packages``, it will return ``-1`` as an identifier.

	 */
    #[SerializedName('dataUsageRemainingInBytes')]
    public ?float $dataUsageRemainingInBytes;

    /**
     * Status of the connectivity, possible values are 'ACTIVE' or 'NOT_ACTIVE'
     */
    #[SerializedName('status')]
    public ?string $status;

    public function __construct(?float $dataUsageRemainingInBytes = null, ?string $status = null)
    {
        $this->dataUsageRemainingInBytes = $dataUsageRemainingInBytes;
        $this->status = $status;
    }
}
