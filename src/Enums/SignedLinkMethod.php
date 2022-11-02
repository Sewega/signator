<?php

declare(strict_types = 1);

namespace Sewega\Signator\Enums;

enum SignedLinkMethod: string
{

    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';

}
