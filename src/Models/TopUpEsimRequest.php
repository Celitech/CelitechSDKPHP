<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TopUpEsimRequest
{
    /**
     * ID of the eSIM
     */
    #[SerializedName('iccid')]
    public string $iccid;

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
     * Email address where the purchase confirmation email will be sent (excluding QR Code & activation steps).
     */
    #[SerializedName('email')]
    public ?string $email;

    /**
     * An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes.
     */
    #[SerializedName('referenceId')]
    public ?string $referenceId;

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
        string $iccid,
        float $dataLimitInGb,
        string $startDate,
        string $endDate,
        ?string $email = null,
        ?string $referenceId = null,
        ?string $emailBrand = null,
        ?float $startTime = null,
        ?float $endTime = null
    ) {
        $this->iccid = $iccid;
        $this->dataLimitInGb = $dataLimitInGb;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->email = $email;
        $this->referenceId = $referenceId;
        $this->emailBrand = $emailBrand;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}
