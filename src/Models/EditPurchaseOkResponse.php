<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class EditPurchaseOkResponse implements \JsonSerializable
{
    /**
     * ID of the purchase
     */
    #[SerializedName('purchaseId')]
    public string $purchaseId;

    /**
     * Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
     */
    #[SerializedName('newStartDate')]
    public string $newStartDate;

    /**
     * End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
     */
    #[SerializedName('newEndDate')]
    public string $newEndDate;

    /**
     * Epoch value representing the new start time of the package's validity
     */
    #[SerializedName('newStartTime')]
    public ?float $newStartTime;

    /**
     * Epoch value representing the new end time of the package's validity
     */
    #[SerializedName('newEndTime')]
    public ?float $newEndTime;

    public function __construct(
        string $purchaseId,
        string $newStartDate,
        string $newEndDate,
        ?float $newStartTime = null,
        ?float $newEndTime = null
    ) {
        $this->purchaseId = $purchaseId;
        $this->newStartDate = $newStartDate;
        $this->newEndDate = $newEndDate;
        $this->newStartTime = $newStartTime;
        $this->newEndTime = $newEndTime;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            purchaseId: $data['purchaseId'] ?? null,
            newStartDate: $data['newStartDate'] ?? null,
            newEndDate: $data['newEndDate'] ?? null,
            newStartTime: $data['newStartTime'] ?? null,
            newEndTime: $data['newEndTime'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'purchaseId' => $this->purchaseId,
            'newStartDate' => $this->newStartDate,
            'newEndDate' => $this->newEndDate,
            'newStartTime' => $this->newStartTime,
            'newEndTime' => $this->newEndTime,
        ];
    }
}
