<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimHistoryOkResponse
{
    #[SerializedName('esim')]
    public ?GetEsimHistoryOkResponseEsim $esim;

    public function __construct(?GetEsimHistoryOkResponseEsim $esim = null)
    {
        $this->esim = $esim;
    }
}
