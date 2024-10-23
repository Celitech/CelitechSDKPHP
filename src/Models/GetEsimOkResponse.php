<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimOkResponse
{
    #[SerializedName('esim')]
    public ?GetEsimOkResponseEsim $esim;

    public function __construct(?GetEsimOkResponseEsim $esim = null)
    {
        $this->esim = $esim;
    }
}
