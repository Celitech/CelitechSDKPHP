<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimHistoryOkResponseEsim
{
    /**
     * ID of the eSIM
     */
    #[SerializedName('iccid')]
    public string $iccid;

    /**
     * @var History[]
     */
    #[SerializedName('history')]
    public array $history;

    public function __construct(string $iccid, array $history)
    {
        $this->iccid = $iccid;
        $this->history = $history;
    }
}
