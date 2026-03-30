<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class History implements \JsonSerializable
{
    /**
     * The status of the eSIM at a given time, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR'
     */
    #[SerializedName('status')]
    public string $status;

    /**
     * The date when the eSIM status changed in the format 'yyyy-MM-ddThh:mm:ssZZ'
     */
    #[SerializedName('statusDate')]
    public string $statusDate;

    /**
     * Epoch value representing the date when the eSIM status changed
     */
    #[SerializedName('date')]
    public ?float $date;

    public function __construct(string $status, string $statusDate, ?float $date = null)
    {
        $this->status = $status;
        $this->statusDate = $statusDate;
        $this->date = $date;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'] ?? null,
            statusDate: $data['statusDate'] ?? null,
            date: $data['date'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status,
            'statusDate' => $this->statusDate,
            'date' => $this->date,
        ];
    }
}
