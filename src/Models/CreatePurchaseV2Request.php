<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseV2Request
{
    /**
     * ISO representation of the package's destination
     */
    #[SerializedName('destination')]
    public string $destination;

    /**
     * Size of the package in GB. The available options are 1, 2, 3, 5, 8, 20GB
     */
    #[SerializedName('dataLimitInGB')]
    public float $dataLimitInGb;

    /**
     * Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
     */
    #[SerializedName('startDate')]
    public string $startDate;

    /**
     * End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
     */
    #[SerializedName('endDate')]
    public string $endDate;

    /**
     * Number of eSIMs to purchase.
     */
    #[SerializedName('quantity')]
    public float $quantity;

    /**
     * Email address where the purchase confirmation email will be sent (including QR Code & activation steps)
     */
    #[SerializedName('email')]
    public ?string $email;

    /**
     * An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes.
     */
    #[SerializedName('referenceId')]
    public ?string $referenceId;

    /**
     * Customize the network brand of the issued eSIM. This parameter is accessible to platforms with Diamond tier and requires an alphanumeric string of up to 15 characters.
     */
    #[SerializedName('networkBrand')]
    public ?string $networkBrand;

    /**
     * Customize the email brand. This parameter is accessible to platforms with Diamond tier and requires an alphanumeric string of up to 25 characters.
     */
    #[SerializedName('emailBrand')]
    public ?string $emailBrand;

    public function __construct(
        string $destination,
        float $dataLimitInGb,
        string $startDate,
        string $endDate,
        float $quantity,
        ?string $email = null,
        ?string $referenceId = null,
        ?string $networkBrand = null,
        ?string $emailBrand = null
    ) {
        $this->destination = $destination;
        $this->dataLimitInGb = $dataLimitInGb;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->quantity = $quantity;
        $this->email = $email;
        $this->referenceId = $referenceId;
        $this->networkBrand = $networkBrand;
        $this->emailBrand = $emailBrand;
    }
}
