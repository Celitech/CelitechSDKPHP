<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimDeviceOkResponse
{
    #[SerializedName('device')]
    public ?Device $device;

    public function __construct(?Device $device = null)
    {
        $this->device = $device;
    }
}
