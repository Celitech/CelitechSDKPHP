<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Language of the confirmation email sent to the customer.
 */
enum CreatePurchaseV2RequestLanguage: string
{
    case En = 'en';
    case Es = 'es';
    case Fr = 'fr';
    case De = 'de';
    case PtBr = 'pt-br';
}
