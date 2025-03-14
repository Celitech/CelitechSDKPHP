<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseV2OkResponseProfile
{
    /**
     * ID of the eSIM
     */
    #[SerializedName('iccid')]
    public ?string $iccid;

    /**
     * QR Code of the eSIM as base64
     */
    #[SerializedName('activationCode')]
    public ?string $activationCode;

    /**
     * Manual Activation Code of the eSIM
     */
    #[SerializedName('manualActivationCode')]
    public ?string $manualActivationCode;

    public function __construct(
        ?string $iccid = null,
        ?string $activationCode = null,
        ?string $manualActivationCode = null
    ) {
        $this->iccid = $iccid;
        $this->activationCode = $activationCode;
        $this->manualActivationCode = $manualActivationCode;
    }
}
