<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TopUpEsimOkResponsePurchase
{
    /**
     * ID of the purchase
     */
    #[SerializedName('id')]
    public ?string $id;

    /**
     * ID of the package
     */
    #[SerializedName('packageId')]
    public ?string $packageId;

    /**
     * Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
     */
    #[SerializedName('startDate')]
    public ?string $startDate;

    /**
     * End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
     */
    #[SerializedName('endDate')]
    public ?string $endDate;

    /**
     * It designates the number of days the eSIM is valid for within 90-day validity from issuance date.
     */
    #[SerializedName('duration')]
    public ?float $duration;

    /**
     * Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ'
     */
    #[SerializedName('createdDate')]
    public ?string $createdDate;

    /**
     * Epoch value representing the start time of the package's validity
     */
    #[SerializedName('startTime')]
    public ?float $startTime;

    /**
     * Epoch value representing the end time of the package's validity
     */
    #[SerializedName('endTime')]
    public ?float $endTime;

    public function __construct(
        ?string $id = null,
        ?string $packageId = null,
        ?string $startDate = null,
        ?string $endDate = null,
        ?float $duration = null,
        ?string $createdDate = null,
        ?float $startTime = null,
        ?float $endTime = null
    ) {
        $this->id = $id;
        $this->packageId = $packageId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->duration = $duration;
        $this->createdDate = $createdDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}
