<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimMacOkResponse
{
    #[SerializedName('esim')]
    public GetEsimMacOkResponseEsim $esim;

    public function __construct(GetEsimMacOkResponseEsim $esim)
    {
        $this->esim = $esim;
    }
}
