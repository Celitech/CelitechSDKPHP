<?php

declare(strict_types=1);

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
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      purchase: isset($data['purchase']) && is_array($data['purchase'])
        ? CreatePurchaseV2OkResponsePurchase::fromArray($data['purchase'])
        : null,
      profile: isset($data['profile']) && is_array($data['profile'])
        ? CreatePurchaseV2OkResponseProfile::fromArray($data['profile'])
        : null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'purchase' => $this->purchase,
      'profile' => $this->profile
    ];

    return $result;
  }
}
