<?php

declare(strict_types=1);

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

/**
 * Service class containing API endpoint methods.
 *
 * This class extends the base service to provide typed methods for specific API operations.
 * Each method corresponds to an API endpoint and handles request serialization,
 * execution, and response deserialization.
 */
class Edit extends BaseService
{
  /** @var array|null Method-level configuration for editPurchase */
  protected ?array $editPurchaseConfig = null;

  /**
   * Set method-level configuration for editPurchase.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setEditPurchaseConfig(array $config): static
  {
    $this->editPurchaseConfig = $config;
    return $this;
  }

  /**
   * This endpoint allows you to modify the validity dates of an existing purchase.
   *
   * **Behavior:**
   * - If the purchase has **not yet been activated**, both the start and end dates can be updated.
   * - If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).
   * - Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.
   *
   * The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits.
   *
   *
   * @param Models\EditPurchaseRequest $input Request body
   * @param string $accept
   * @return mixed
   */
  public function editPurchase(
    Models\EditPurchaseRequest $input,
    string $accept,
    array $requestConfig = []
  ): mixed {
    $resolvedConfig = $this->getResolvedConfig($this->editPurchaseConfig, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/purchases/edit',
      [
        'json' => Serializer::serialize($input),
        'headers' => [
          'Accept' => $accept
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = $this->decodeJson($data);

    return $result;
  }
}
