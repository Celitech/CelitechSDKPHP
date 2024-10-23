<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimMacOkResponseEsim
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

    public function __construct(
        ?string $iccid = null,
        ?string $smdpAddress = null,
        ?string $manualActivationCode = null
    ) {
        $this->iccid = $iccid;
        $this->smdpAddress = $smdpAddress;
        $this->manualActivationCode = $manualActivationCode;
    }
}
