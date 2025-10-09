<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TopUpEsimOkResponse
{
    #[SerializedName('purchase')]
    public TopUpEsimOkResponsePurchase $purchase;

    #[SerializedName('profile')]
    public TopUpEsimOkResponseProfile $profile;

    public function __construct(TopUpEsimOkResponsePurchase $purchase, TopUpEsimOkResponseProfile $profile)
    {
        $this->purchase = $purchase;
        $this->profile = $profile;
    }
}
