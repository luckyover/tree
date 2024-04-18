<?php

namespace App\Utility\Database\Facades;

use Illuminate\Support\Facades\Facade;
use App\Utility\Constants;

use App\Utility\Database\SqlServerDao;

class Dao extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        $db_connection = env('DB_CONNECTION', '');
  
        // if using sql server 
        if ($db_connection == Constants::DB_CONNECTION_SQL_SERVER) {
            return SqlServerDao::class;
        }
    }
}
