<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TopUpEsimOkResponse implements \JsonSerializable
{
  #[SerializedName('purchase')]
  public TopUpEsimOkResponsePurchase $purchase;

  #[SerializedName('profile')]
  public TopUpEsimOkResponseProfile $profile;

  public function __construct(
    TopUpEsimOkResponsePurchase $purchase,
    TopUpEsimOkResponseProfile $profile
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
        ? TopUpEsimOkResponsePurchase::fromArray($data['purchase'])
        : null,
      profile: isset($data['profile']) && is_array($data['profile'])
        ? TopUpEsimOkResponseProfile::fromArray($data['profile'])
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
