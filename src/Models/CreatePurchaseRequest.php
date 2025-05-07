<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseRequest
{
    /**
     * ISO representation of the package's destination
     */
    #[SerializedName('destination')]
    public string $destination;

    /**
     * Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20GB
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
     * Customize the network brand of the issued eSIM. The `networkBrand` parameter cannot exceed 15 characters in length and must contain only letters and numbers. This feature is available to platforms with Diamond tier only.
     */
    #[SerializedName('networkBrand')]
    public ?string $networkBrand;

    /**
     * Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.
     */
    #[SerializedName('emailBrand')]
    public ?string $emailBrand;

    /**
     * Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months.
     */
    #[SerializedName('startTime')]
    public ?float $startTime;

    /**
     * Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time.
     */
    #[SerializedName('endTime')]
    public ?float $endTime;

    public function __construct(
        string $destination,
        float $dataLimitInGb,
        string $startDate,
        string $endDate,
        ?string $email = null,
        ?string $referenceId = null,
        ?string $networkBrand = null,
        ?string $emailBrand = null,
        ?float $startTime = null,
        ?float $endTime = null
    ) {
        $this->destination = $destination;
        $this->dataLimitInGb = $dataLimitInGb;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->email = $email;
        $this->referenceId = $referenceId;
        $this->networkBrand = $networkBrand;
        $this->emailBrand = $emailBrand;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}
