<?php

namespace App\Utility;

/**
 * contains all the constants used in the project
 * ex : CONSTANT_NAME = 123
 */
class Constants
{
    public const PAGING_FIRST_RECORDS_FOR_PAGE = 20;
    public const PAGING_SECOND_RECORDS_FOR_PAGE = 50;
    public const PAGING_TERTIARY_RECORDS_FOR_PAGE = 100;
    public const PAGING_LAST_RECORDS_FOR_PAGE = 200;
    public const PAGING_PAGE = 1;
    public const PAGING_TOTAL_RECORD = 0;
    public const PAGING_PAGE_MAX = 1;
    public const PAGING_PAGE_OFFSET = 1;

    // database connection
    public const DB_CONNECTION_POSTGRES = 'pgsql';
    public const DB_CONNECTION_SQL_SERVER = 'sqlsrv';
    public const DB_EXCEPTION_CODE = 999;


}
