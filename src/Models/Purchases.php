<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Purchases
{
    /**
     * ID of the purchase
     */
    #[SerializedName('id')]
    public ?string $id;

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

    /**
     * Epoch value representing the date of creation of the purchase
     */
    #[SerializedName('createdAt')]
    public ?float $createdAt;

    #[SerializedName('package')]
    public ?Package $package;

    #[SerializedName('esim')]
    public ?PurchasesEsim $esim;

    /**
     * The source indicates where the eSIM was purchased, which can be from the API, dashboard, landing-page or promo-page. For purchases made before September 8, 2023, the value will be displayed as 'Not available'.
     */
    #[SerializedName('source')]
    public ?string $source;

    /**
     * The referenceId that was provided by the partner during the purchase or topup flow. This identifier can be used for analytics and debugging purposes.
     */
    #[SerializedName('referenceId')]
    public ?string $referenceId;

    public function __construct(
        ?string $id = null,
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $createdDate = null,
        ?float $startTime = null,
        ?float $endTime = null,
        ?float $createdAt = null,
        ?Package $package = null,
        ?PurchasesEsim $esim = null,
        ?string $source = null,
        ?string $referenceId = null
    ) {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->createdDate = $createdDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->createdAt = $createdAt;
        $this->package = $package;
        $this->esim = $esim;
        $this->source = $source;
        $this->referenceId = $referenceId;
    }
}
