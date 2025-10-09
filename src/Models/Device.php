<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Device
{
    /**
     * Name of the OEM
     */
    #[SerializedName('oem')]
    public string $oem;

    /**
     * Name of the Device
     */
    #[SerializedName('hardwareName')]
    public string $hardwareName;

    /**
     * Model of the Device
     */
    #[SerializedName('hardwareModel')]
    public string $hardwareModel;

    /**
     * Serial Number of the eSIM
     */
    #[SerializedName('eid')]
    public string $eid;

    public function __construct(string $oem, string $hardwareName, string $hardwareModel, string $eid)
    {
        $this->oem = $oem;
        $this->hardwareName = $hardwareName;
        $this->hardwareModel = $hardwareModel;
        $this->eid = $eid;
    }
}
