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

    #[SerializedName('activationCode')]
    public ?string $activationCode;

    public function __construct(?string $iccid = null, ?string $activationCode = null)
    {
        $this->iccid = $iccid;
        $this->activationCode = $activationCode;
    }
}
