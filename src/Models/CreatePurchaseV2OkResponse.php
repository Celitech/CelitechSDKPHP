<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseV2OkResponse implements \JsonSerializable
{
    #[SerializedName('purchase')]
    public CreatePurchaseV2OkResponsePurchase $purchase;

    #[SerializedName('profile')]
    public CreatePurchaseV2OkResponseProfile $profile;

    public function __construct(
        CreatePurchaseV2OkResponsePurchase $purchase,
        CreatePurchaseV2OkResponseProfile $profile
    ) {
        $this->purchase = $purchase;
        $this->profile = $profile;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(purchase: $data['purchase'] ?? null, profile: $data['profile'] ?? null);
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'purchase' => $this->purchase,
            'profile' => $this->profile,
        ];
    }
}
