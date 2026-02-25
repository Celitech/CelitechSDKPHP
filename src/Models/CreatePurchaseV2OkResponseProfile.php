<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseV2OkResponseProfile implements \JsonSerializable
{
    /**
     * ID of the eSIM
     */
    #[SerializedName('iccid')]
    public string $iccid;

    /**
     * QR Code of the eSIM as base64
     */
    #[SerializedName('activationCode')]
    public string $activationCode;

    /**
     * Manual Activation Code of the eSIM
     */
    #[SerializedName('manualActivationCode')]
    public string $manualActivationCode;

    public function __construct(string $iccid, string $activationCode, string $manualActivationCode)
    {
        $this->iccid = $iccid;
        $this->activationCode = $activationCode;
        $this->manualActivationCode = $manualActivationCode;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            iccid: $data['iccid'] ?? null,
            activationCode: $data['activationCode'] ?? null,
            manualActivationCode: $data['manualActivationCode'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'iccid' => $this->iccid,
            'activationCode' => $this->activationCode,
            'manualActivationCode' => $this->manualActivationCode,
        ];
    }
}
