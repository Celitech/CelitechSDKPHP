<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimOkResponseEsim
{
    /**
     * ID of the eSIM
     */
    #[SerializedName('iccid')]
    public ?string $iccid;

    /**
     * SM-DP+ Address
     */
    #[SerializedName('smdpAddress')]
    public ?string $smdpAddress;

    /**
     * The manual activation code
     */
    #[SerializedName('manualActivationCode')]
    public ?string $manualActivationCode;

    /**
     * Status of the eSIM, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR'
     */
    #[SerializedName('status')]
    public ?string $status;

    /**
     * Indicates whether the eSIM is currently eligible for a top-up. This flag should be checked before attempting a top-up request.
     */
    #[SerializedName('isTopUpAllowed')]
    public ?bool $isTopUpAllowed;

    public function __construct(
        ?string $iccid = null,
        ?string $smdpAddress = null,
        ?string $manualActivationCode = null,
        ?string $status = null,
        ?bool $isTopUpAllowed = null
    ) {
        $this->iccid = $iccid;
        $this->smdpAddress = $smdpAddress;
        $this->manualActivationCode = $manualActivationCode;
        $this->status = $status;
        $this->isTopUpAllowed = $isTopUpAllowed;
    }
}
