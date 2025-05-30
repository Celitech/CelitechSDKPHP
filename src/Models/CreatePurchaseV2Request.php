<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseV2Request
{
    /**
     * ISO representation of the package's destination.
     */
    #[SerializedName('destination')]
    public string $destination;

    /**
	 * Size of the package in GB.

For ``limited packages``, the available options are: ``0.5, 1, 2, 3, 5, 8, 20GB`` (supports `duration` or `startDate` / `endDate`).

For ``unlimited packages`` (available to Region-3), please use ``-1`` as an identifier (supports `duration` only).

	 */
    #[SerializedName('dataLimitInGB')]
    public float $dataLimitInGb;

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
     * Customize the network brand of the issued eSIM. The `networkBrand` parameter cannot exceed 15 characters in length and must contain only letters and numbers. This feature is available to platforms with Diamond tier only.
     */
    #[SerializedName('networkBrand')]
    public ?string $networkBrand;

    /**
     * Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.
     */
    #[SerializedName('emailBrand')]
    public ?string $emailBrand;

    public function __construct(
        string $destination,
        float $dataLimitInGb,
        float $quantity,
        ?string $email = null,
        ?string $referenceId = null,
        ?string $networkBrand = null,
        ?string $emailBrand = null
    ) {
        $this->destination = $destination;
        $this->dataLimitInGb = $dataLimitInGb;
        $this->quantity = $quantity;
        $this->email = $email;
        $this->referenceId = $referenceId;
        $this->networkBrand = $networkBrand;
        $this->emailBrand = $emailBrand;
    }
}
