<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseV2OkResponsePurchase
{
    /**
     * ID of the purchase
     */
    #[SerializedName('id')]
    public ?string $id;

    /**
     * ID of the package
     */
    #[SerializedName('packageId')]
    public ?string $packageId;

    /**
     * Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ'
     */
    #[SerializedName('createdDate')]
    public ?string $createdDate;

    public function __construct(?string $id = null, ?string $packageId = null, ?string $createdDate = null)
    {
        $this->id = $id;
        $this->packageId = $packageId;
        $this->createdDate = $createdDate;
    }
}
