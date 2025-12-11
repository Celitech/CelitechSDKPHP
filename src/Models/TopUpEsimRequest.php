<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TopUpEsimRequest implements \JsonSerializable
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
    public ?string $startDate;

    /**
     * End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
     */
    #[SerializedName('endDate')]
    public ?string $endDate;

    /**
     * Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration.
     */
    #[SerializedName('duration')]
    public ?TopUpEsimRequestDuration $duration;

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
        ?string $startDate = null,
        ?string $endDate = null,
        ?TopUpEsimRequestDuration $duration = null,
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
        $this->duration = $duration;
        $this->email = $email;
        $this->referenceId = $referenceId;
        $this->emailBrand = $emailBrand;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            iccid: $data['iccid'] ?? null,
            dataLimitInGb: $data['dataLimitInGB'] ?? null,
            startDate: $data['startDate'] ?? null,
            endDate: $data['endDate'] ?? null,
            duration: isset($data['duration']) && is_string($data['duration'])
                ? TopUpEsimRequestDuration::from($data['duration'])
                : null,
            email: $data['email'] ?? null,
            referenceId: $data['referenceId'] ?? null,
            emailBrand: $data['emailBrand'] ?? null,
            startTime: $data['startTime'] ?? null,
            endTime: $data['endTime'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'iccid' => $this->iccid,
            'dataLimitInGB' => $this->dataLimitInGb,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'duration' => $this->duration,
            'email' => $this->email,
            'referenceId' => $this->referenceId,
            'emailBrand' => $this->emailBrand,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ];
    }
}
