<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimHistoryOkResponseEsim implements \JsonSerializable
{
    /**
     * ID of the eSIM
     */
    #[SerializedName('iccid')]
    public string $iccid;

    /**
     * @var History[]
     */
    #[SerializedName('history')]
    public array $history;

    public function __construct(string $iccid, array $history)
    {
        $this->iccid = $iccid;
        $this->history = $history;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(iccid: $data['iccid'] ?? null, history: $data['history'] ?? null);
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'iccid' => $this->iccid,
            'history' => $this->history,
        ];
    }
}
