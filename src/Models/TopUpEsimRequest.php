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
	 * Size of the package in GB.
- ``Limited Packages (1, 2, 3, 5, 8, 20GB):`` supports `duration` or `startDate` `endDate`.
- ``Unlimited Packages (Region-3 only)`` support `duration` only. Use ``-1`` for unlimited.

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
     * Email address where the purchase confirmation email will be sent (excluding QR Code & activation steps)
     */
    #[SerializedName('email')]
    public ?string $email;

    /**
     * An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes.
     */
    #[SerializedName('referenceId')]
    public ?string $referenceId;

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
        ?float $duration = null,
        ?string $email = null,
        ?string $referenceId = null,
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
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}
