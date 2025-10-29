<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimHistoryOkResponse implements \JsonSerializable
{
    #[SerializedName('esim')]
    public GetEsimHistoryOkResponseEsim $esim;

    public function __construct(GetEsimHistoryOkResponseEsim $esim)
    {
        $this->esim = $esim;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(esim: $data['esim'] ?? null);
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'esim' => $this->esim,
        ];
    }
}
