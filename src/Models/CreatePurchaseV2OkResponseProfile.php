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

    /**
     * iOS Activation Link of the eSIM
     */
    #[SerializedName('iosActivationLink')]
    public string $iosActivationLink;

    /**
     * Android Activation Link of the eSIM
     */
    #[SerializedName('androidActivationLink')]
    public string $androidActivationLink;

    public function __construct(
        string $iccid,
        string $activationCode,
        string $manualActivationCode,
        string $iosActivationLink,
        string $androidActivationLink
    ) {
        $this->iccid = $iccid;
        $this->activationCode = $activationCode;
        $this->manualActivationCode = $manualActivationCode;
        $this->iosActivationLink = $iosActivationLink;
        $this->androidActivationLink = $androidActivationLink;
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
            manualActivationCode: $data['manualActivationCode'] ?? null,
            iosActivationLink: $data['iosActivationLink'] ?? null,
            androidActivationLink: $data['androidActivationLink'] ?? null
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
            'iosActivationLink' => $this->iosActivationLink,
            'androidActivationLink' => $this->androidActivationLink,
        ];
    }
}
