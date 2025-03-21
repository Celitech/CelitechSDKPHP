<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseV2OkResponse
{
    #[SerializedName('purchase')]
    public ?CreatePurchaseV2OkResponsePurchase $purchase;

    #[SerializedName('profile')]
    public ?CreatePurchaseV2OkResponseProfile $profile;

    public function __construct(
        ?CreatePurchaseV2OkResponsePurchase $purchase = null,
        ?CreatePurchaseV2OkResponseProfile $profile = null
    ) {
        $this->purchase = $purchase;
        $this->profile = $profile;
    }
}
