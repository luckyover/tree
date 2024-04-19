<?php
namespace App\Utility\Log\Facades;

use Illuminate\Support\Facades\Facade;
use App\Utility\Log\BLogger;

class BLog extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BLogger::class;
    }
}
