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
	 * Size of the package in GB.
- ``Limited Packages`` (1, 2, 3, 5, 8, 20GB): supports `duration` or `startDate` / `endDate`.
- ``Unlimited Packages`` (available for Region-3): supports `duration` only. Use ``-1`` for unlimited.

	 */
    #[SerializedName('dataLimitInGB')]
    public float $dataLimitInGb;

    /**
	 * Start date of the package validity in the format yyyy-MM-dd. This date can be set to the current day or any day within the next 12 months. 

Exactly one of the following must be provided:
- Both `startDate` and `endDate` together
- Or `duration` alone

These options are mutually exclusive — do not include `duration` with `startDate` or `endDate`.

	 */
    #[SerializedName('startDate')]
    public ?string $startDate;

    /**
	 * End date of the package validity in the format yyyy-MM-dd. End date can be maximum 90 days after Start date. 

Exactly one of the following must be provided:
- Both `startDate` and `endDate` together
- Or `duration` alone

These options are mutually exclusive — do not include `duration` with `startDate` or `endDate`.

	 */
    #[SerializedName('endDate')]
    public ?string $endDate;

    /**
	 * It designates the number of days the eSIM is valid for within 90-day validity from issuance date.
 - ``For limited packages`` (1, 2, 3, 5, 8, 20GB): The available options are 1, 2, 7, 14, 30 days (following the pricing of 0-30 days) and 90 days (following the pricing of 0-90 days)
 - ``For unlimited package`` (available for Region-3): The available options are for 1, 2, 7, 14, 30 days (following a custom pricing).

 Exactly one of the following must be provided:
 - Both `startDate` and `endDate` together
 - Or `duration` alone

 These options are mutually exclusive — do not include `duration` with `startDate` or `endDate`.

	 */
    #[SerializedName('duration')]
    public ?float $duration;

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

    public function __construct(
        string $destination,
        float $dataLimitInGb,
        ?string $startDate = null,
        ?string $endDate = null,
        ?float $duration = null,
        float $quantity,
        ?string $email = null,
        ?string $referenceId = null,
        ?string $networkBrand = null
    ) {
        $this->destination = $destination;
        $this->dataLimitInGb = $dataLimitInGb;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->duration = $duration;
        $this->quantity = $quantity;
        $this->email = $email;
        $this->referenceId = $referenceId;
        $this->networkBrand = $networkBrand;
    }
}
