<?php

declare(strict_types=1);

namespace Celitech\Exceptions;

use Exception;

/**
 * Base exception for every error thrown by this SDK.
 *
 * Catch this type to handle any SDK failure with a single clause — API error
 * responses, transport failures, and timeouts all extend it.
 */
class CelitechException extends Exception
{
}
