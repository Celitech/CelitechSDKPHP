<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class History
{
    /**
     * The status of the eSIM at a given time, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR'
     */
    #[SerializedName('status')]
    public ?string $status;

    /**
     * The date when the eSIM status changed in the format 'yyyy-MM-ddThh:mm:ssZZ'
     */
    #[SerializedName('statusDate')]
    public ?string $statusDate;

    /**
     * Epoch value representing the date when the eSIM status changed
     */
    #[SerializedName('date')]
    public ?float $date;

    public function __construct(?string $status = null, ?string $statusDate = null, ?float $date = null)
    {
        $this->status = $status;
        $this->statusDate = $statusDate;
        $this->date = $date;
    }
}
