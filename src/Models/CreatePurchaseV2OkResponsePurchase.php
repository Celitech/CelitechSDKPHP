<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseV2OkResponsePurchase implements \JsonSerializable
{
    /**
     * ID of the purchase
     */
    #[SerializedName('id')]
    public string $id;

    /**
     * ID of the package
     */
    #[SerializedName('packageId')]
    public string $packageId;

    /**
     * Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ'
     */
    #[SerializedName('createdDate')]
    public string $createdDate;

    public function __construct(string $id, string $packageId, string $createdDate)
    {
        $this->id = $id;
        $this->packageId = $packageId;
        $this->createdDate = $createdDate;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            packageId: $data['packageId'] ?? null,
            createdDate: $data['createdDate'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'packageId' => $this->packageId,
            'createdDate' => $this->createdDate,
        ];
    }
}
