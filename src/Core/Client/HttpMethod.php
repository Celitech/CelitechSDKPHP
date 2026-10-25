<?php

declare(strict_types=1);

namespace Celitech\Core\Client;

enum HttpMethod
{
  case GET;
  case POST;
  case PUT;
  case PATCH;
  case DELETE;
  case HEAD;
  // Beyond Fern's six. A spec is free to declare either, and RawClient dispatches on
  // `$request->method->name`, so an absent case is a fatal rather than a wrong verb.
  case OPTIONS;
  case TRACE;
}
