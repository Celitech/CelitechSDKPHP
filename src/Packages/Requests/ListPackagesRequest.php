<?php

declare(strict_types=1);

namespace Celitech\Packages\Requests;

use Celitech\Core\Json\JsonSerializableType;
use DateTime;
use Celitech\Utils\Serializer;

class ListPackagesRequest extends JsonSerializableType
{
  /**
   * @var ?string $destination ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes.
   */
  public ?string $destination;

  /**
   * @var ?float $dataLimitInGb Filter packages by data limit in GB. When provided, only packages with this exact data limit are returned. Use `-1` together with `includeUnlimited=true` to return only unlimited packages. A value of `0` is ignored.
   */
  public ?float $dataLimitInGb;

  /**
   * @var ?DateTime $startDate Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
   */
  public ?DateTime $startDate;

  /**
   * @var ?DateTime $endDate End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
   */
  public ?DateTime $endDate;

  /**
   * @var ?string $afterCursor To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data.
   */
  public ?string $afterCursor;

  /**
   * @var ?float $limit Maximum number of packages to be returned in the response. The value must be greater than 0 and less than or equal to 160. If not provided, the default value is 20
   */
  public ?float $limit;

  /**
   * @var ?int $startTime Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months
   */
  public ?int $startTime;

  /**
   * @var ?int $endTime Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time
   */
  public ?int $endTime;

  /**
   * @var ?bool $includeUnlimited Whether to include unlimited (date-based) packages in the results. Unlimited packages are excluded by default; set this to `true` to include them. An unlimited package has `dataLimitInGB` and `dataLimitInBytes` equal to `-1`, and is offered for 3 to 30 days with `minDays` equal to `maxDays`.
   */
  public ?bool $includeUnlimited;

  /**
   * @param array{
   *   destination?: ?string,
   *   dataLimitInGb?: ?float,
   *   startDate?: ?DateTime,
   *   endDate?: ?DateTime,
   *   afterCursor?: ?string,
   *   limit?: ?float,
   *   startTime?: ?int,
   *   endTime?: ?int,
   *   includeUnlimited?: ?bool,
   * } $values
   */
  public function __construct(array $values = [])
  {
    $this->destination = $values['destination'] ?? null;
    $this->dataLimitInGb = $values['dataLimitInGb'] ?? null;
    $this->startDate = $values['startDate'] ?? null;
    $this->endDate = $values['endDate'] ?? null;
    $this->afterCursor = $values['afterCursor'] ?? null;
    $this->limit = $values['limit'] ?? null;
    $this->startTime = $values['startTime'] ?? null;
    $this->endTime = $values['endTime'] ?? null;
    $this->includeUnlimited = $values['includeUnlimited'] ?? null;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self([
      'destination' => $data['destination'] ?? null,
      'dataLimitInGb' => $data['dataLimitInGB'] ?? null,
      'startDate' => isset($data['startDate'])
        ? Serializer::deserializeDate($data['startDate'])
        : null,
      'endDate' => isset($data['endDate']) ? Serializer::deserializeDate($data['endDate']) : null,
      'afterCursor' => $data['afterCursor'] ?? null,
      'limit' => $data['limit'] ?? null,
      'startTime' => $data['startTime'] ?? null,
      'endTime' => $data['endTime'] ?? null,
      'includeUnlimited' => $data['includeUnlimited'] ?? null
    ]);
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    if ($this->destination !== null) {
      $result['destination'] = $this->destination;
    }
    if ($this->dataLimitInGb !== null) {
      $result['dataLimitInGB'] = $this->dataLimitInGb;
    }
    if ($this->startDate !== null) {
      $result['startDate'] = Serializer::serializeDate($this->startDate);
    }
    if ($this->endDate !== null) {
      $result['endDate'] = Serializer::serializeDate($this->endDate);
    }
    if ($this->afterCursor !== null) {
      $result['afterCursor'] = $this->afterCursor;
    }
    if ($this->limit !== null) {
      $result['limit'] = $this->limit;
    }
    if ($this->startTime !== null) {
      $result['startTime'] = $this->startTime;
    }
    if ($this->endTime !== null) {
      $result['endTime'] = $this->endTime;
    }
    if ($this->includeUnlimited !== null) {
      $result['includeUnlimited'] = $this->includeUnlimited;
    }
    return $result;
  }
}
