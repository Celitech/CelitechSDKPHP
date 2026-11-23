<?php

declare(strict_types=1);

namespace Celitech;

/**
 * Predefined API environment URLs.
 *
 * Each case is backed by the environment's base URL, so `->value` yields the
 * URL as a string when one is required.
 */
enum Environments: string
{
  case Default_ = 'https://api.celitech.net/v1';
}
