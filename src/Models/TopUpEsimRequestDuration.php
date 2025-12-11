<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration.
 */
enum TopUpEsimRequestDuration: number
{
    case _1 = 1;
    case _2 = 2;
    case _7 = 7;
    case _14 = 14;
    case _30 = 30;
    case _90 = 90;
}
