<?php

namespace Decodeblock\Ercaspay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Decodeblock\Ercaspay\Ercaspay
 */
class Ercaspay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Decodeblock\Ercaspay\Ercaspay::class;
    }
}
