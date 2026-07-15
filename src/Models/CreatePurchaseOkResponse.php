<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseOkResponse implements \JsonSerializable
{
  #[SerializedName('purchase')]
  public CreatePurchaseOkResponsePurchase $purchase;

  #[SerializedName('profile')]
  public CreatePurchaseOkResponseProfile $profile;

  public function __construct(
    CreatePurchaseOkResponsePurchase $purchase,
    CreatePurchaseOkResponseProfile $profile
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
    $instance = new self(
      purchase: isset($data['purchase']) && is_array($data['purchase'])
        ? CreatePurchaseOkResponsePurchase::fromArray($data['purchase'])
        : null,
      profile: isset($data['profile']) && is_array($data['profile'])
        ? CreatePurchaseOkResponseProfile::fromArray($data['profile'])
        : null
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['purchase'] = $this->purchase;
    $result['profile'] = $this->profile;
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'purchase',
        'contents' => json_encode($this->purchase)
      ],

      [
        'name' => 'profile',
        'contents' => json_encode($this->profile)
      ]
    ];
  }

  public function validate(): void
  {
    $this->purchase->validate();
    $this->profile->validate();
  }
}
