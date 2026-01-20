<?php

declare(strict_types=1);

namespace MarcelWeidum\Passkeys\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MarcelWeidum\Passkeys\Passkeys
 */
final class Passkeys extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MarcelWeidum\Passkeys\Passkeys::class;
    }
}
