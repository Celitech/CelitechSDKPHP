<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ListPackagesOkResponse
{
    /**
     * @var Packages[]|null
     */
    #[SerializedName('packages')]
    public ?array $packages;

    /**
     * The cursor value representing the end of the current page of results. Use this cursor value as the "afterCursor" parameter in your next request to retrieve the subsequent page of results. It ensures that you continue fetching data from where you left off, facilitating smooth pagination
     */
    #[SerializedName('afterCursor')]
    public ?string $afterCursor;

    public function __construct(?array $packages = [], ?string $afterCursor = null)
    {
        $this->packages = $packages;
        $this->afterCursor = $afterCursor;
    }
}
