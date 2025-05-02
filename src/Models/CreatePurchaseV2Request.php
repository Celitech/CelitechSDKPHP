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
- ``Limited Packages (1, 2, 3, 5, 8, 20GB):`` supports `duration` or `startDate` `endDate`
- ``Unlimited Packages (Region-3 only)`` supports `duration` only. Use ``-1`` for unlimited.

	 */
    #[SerializedName('dataLimitInGB')]
    public float $dataLimitInGb;

    /**
	 * Start date of the package validity in the format `yyyy-MM-dd`. This date can be set to the current day or any day within the enxt 12 months. 
- ``Required`` if `duration` is ``not`` provided.  
- ``Optional`` must not passed if `duration` is provided.

	 */
    #[SerializedName('startDate')]
    public ?string $startDate;

    /**
	 * End date of the package validity in the format `yyyy-MM-dd`. End date can be maximum 90 days after Start date. 
- ``Required`` if `duration` is ``not`` provided.  
- ``Optional`` must not passed if `duration` is provided.

	 */
    #[SerializedName('endDate')]
    public ?string $endDate;

    /**
	 * Defines the number of days the eSIM package remains active. Available options: ``1, 2, 7, 14, 30`` 
- ``Required`` if `startDate` and `endDate` are ``not`` provided.  
- ``Optional`` must not passed if `startDate` and `endDate` are provided.    

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
