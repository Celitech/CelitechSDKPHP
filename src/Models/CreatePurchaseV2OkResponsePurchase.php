<?php

declare(strict_types=1);

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
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      id: $data['id'],
      packageId: $data['packageId'],
      createdDate: $data['createdDate']
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['id'] = $this->id;
    $result['packageId'] = $this->packageId;
    $result['createdDate'] = $this->createdDate;
    return $result;
  }

  public function validate(): void
  {
  }
}
