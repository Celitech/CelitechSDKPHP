<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseOkResponse
{
    #[SerializedName('purchase')]
    public CreatePurchaseOkResponsePurchase $purchase;

    #[SerializedName('profile')]
    public CreatePurchaseOkResponseProfile $profile;

    public function __construct(CreatePurchaseOkResponsePurchase $purchase, CreatePurchaseOkResponseProfile $profile)
    {
        $this->purchase = $purchase;
        $this->profile = $profile;
    }
}
