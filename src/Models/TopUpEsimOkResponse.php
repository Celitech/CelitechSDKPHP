<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TopUpEsimOkResponse implements \JsonSerializable
{
    #[SerializedName('purchase')]
    public TopUpEsimOkResponsePurchase $purchase;

    #[SerializedName('profile')]
    public TopUpEsimOkResponseProfile $profile;

    public function __construct(TopUpEsimOkResponsePurchase $purchase, TopUpEsimOkResponseProfile $profile)
    {
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
