<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PurchasesEsim implements \JsonSerializable
{
    /**
     * ID of the eSIM
     */
    #[SerializedName('iccid')]
    public string $iccid;

    public function __construct(string $iccid)
    {
        $this->iccid = $iccid;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(iccid: $data['iccid'] ?? null);
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'iccid' => $this->iccid,
        ];
    }
}
