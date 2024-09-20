<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseOkResponseProfile
{
    /**
     * ID of the eSIM
     */
    #[SerializedName('iccid')]
    public ?string $iccid;

    /**
     * Manual Activation Code of the eSIM
     */
    #[SerializedName('manualActivationCode')]
    public ?string $manualActivationCode;

    /**
     * QR Code of the eSIM as base64
     */
    #[SerializedName('activationCode')]
    public ?string $activationCode;

    public function __construct(
        ?string $iccid = null,
        ?string $manualActivationCode = null,
        ?string $activationCode = null
    ) {
        $this->iccid = $iccid;
        $this->manualActivationCode = $manualActivationCode;
        $this->activationCode = $activationCode;
    }
}
