<?php

declare(strict_types=1);

namespace Celitech\Purchases\Requests;

use Celitech\Core\Json\JsonSerializableType;
use DateTime;
use Celitech\Utils\Serializer;

class ListPurchasesRequest extends JsonSerializableType
{
  /**
   * @var ?string $purchaseId ID of the purchase
   */
  public ?string $purchaseId;

  /**
   * @var ?string $iccid ID of the eSIM
   */
  public ?string $iccid;

  /**
   * @var ?DateTime $afterDate Start date of the interval for filtering purchases in the format 'yyyy-MM-dd'
   */
  public ?DateTime $afterDate;

  /**
   * @var ?DateTime $beforeDate End date of the interval for filtering purchases in the format 'yyyy-MM-dd'
   */
  public ?DateTime $beforeDate;

  /**
   * @var ?string $email Email associated to the purchase.
   */
  public ?string $email;

  /**
   * @var ?string $referenceId The referenceId that was provided by the partner during the purchase or topup flow.
   */
  public ?string $referenceId;

  /**
   * @var ?string $afterCursor To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data.
   */
  public ?string $afterCursor;

  /**
   * @var ?float $limit Maximum number of purchases to be returned in the response. The value must be greater than 0 and less than or equal to 100. If not provided, the default value is 20
   */
  public ?float $limit;

  /**
   * @var ?float $after Epoch value representing the start of the time interval for filtering purchases
   */
  public ?float $after;

  /**
   * @var ?float $before Epoch value representing the end of the time interval for filtering purchases
   */
  public ?float $before;

  /**
   * @param array{
   *   purchaseId?: ?string,
   *   iccid?: ?string,
   *   afterDate?: ?DateTime,
   *   beforeDate?: ?DateTime,
   *   email?: ?string,
   *   referenceId?: ?string,
   *   afterCursor?: ?string,
   *   limit?: ?float,
   *   after?: ?float,
   *   before?: ?float,
   * } $values
   */
  public function __construct(array $values = [])
  {
    $this->purchaseId = $values['purchaseId'] ?? null;
    $this->iccid = $values['iccid'] ?? null;
    $this->afterDate = $values['afterDate'] ?? null;
    $this->beforeDate = $values['beforeDate'] ?? null;
    $this->email = $values['email'] ?? null;
    $this->referenceId = $values['referenceId'] ?? null;
    $this->afterCursor = $values['afterCursor'] ?? null;
    $this->limit = $values['limit'] ?? null;
    $this->after = $values['after'] ?? null;
    $this->before = $values['before'] ?? null;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self([
      'purchaseId' => $data['purchaseId'] ?? null,
      'iccid' => $data['iccid'] ?? null,
      'afterDate' => isset($data['afterDate'])
        ? Serializer::deserializeDate($data['afterDate'])
        : null,
      'beforeDate' => isset($data['beforeDate'])
        ? Serializer::deserializeDate($data['beforeDate'])
        : null,
      'email' => $data['email'] ?? null,
      'referenceId' => $data['referenceId'] ?? null,
      'afterCursor' => $data['afterCursor'] ?? null,
      'limit' => $data['limit'] ?? null,
      'after' => $data['after'] ?? null,
      'before' => $data['before'] ?? null
    ]);
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    if ($this->purchaseId !== null) {
      $result['purchaseId'] = $this->purchaseId;
    }
    if ($this->iccid !== null) {
      $result['iccid'] = $this->iccid;
    }
    if ($this->afterDate !== null) {
      $result['afterDate'] = Serializer::serializeDate($this->afterDate);
    }
    if ($this->beforeDate !== null) {
      $result['beforeDate'] = Serializer::serializeDate($this->beforeDate);
    }
    if ($this->email !== null) {
      $result['email'] = $this->email;
    }
    if ($this->referenceId !== null) {
      $result['referenceId'] = $this->referenceId;
    }
    if ($this->afterCursor !== null) {
      $result['afterCursor'] = $this->afterCursor;
    }
    if ($this->limit !== null) {
      $result['limit'] = $this->limit;
    }
    if ($this->after !== null) {
      $result['after'] = $this->after;
    }
    if ($this->before !== null) {
      $result['before'] = $this->before;
    }
    return $result;
  }
}
