<?php

namespace App\Utility\Database;

use App\Utility\Log\Facades\BLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class SqlServerDao
{
   
    /**
     * execute
     *
     * @param  String $store_name
     * @param  Array $params
     * @param  Boolean $blank
     * @param  String $option
     * @return Array
     */
    public function execute($store_name = '', $params = array(), $blank = false, $option = 'FETCH_ASSOC')
    {
        $dt = Carbon::now();
        $start_time = $dt->valueOf();
        $result = [];
        $has_log_act = 0;
        // dd($store_name, strpos($store_name, 'ACT'));
        if (strpos($store_name, 'ACT') > 0 && $store_name != 'SPC_000_t_lock_ACT1') {
            $has_log_act = 1; // If store is ACT then insert into database s_log
        }
        try {
            $pdo = DB::connection()->getPdo();
            $pdo->setAttribute(constant('PDO::SQLSRV_ATTR_DIRECT_QUERY'), true);
            $pdo->query("SET NOCOUNT ON");
            //Unnamed Placeholder
            $len    = 0;
            $hold   = '';
            if (isset($params) && is_array($params)) {
                $len = count($params);
                for ($i = 1; $i <= $len; $i++) {
                    if ($i == $len) {
                        $hold .= '?';
                    } else {
                        $hold .= '?,';
                    }
                }
            }
            //
            $stmt = $pdo->prepare("{CALL $store_name($hold)}");
            // BIND PARAM
            $index    = 1;
            if (isset($params) && is_array($params)) {
                foreach ($params as &$val) {
                    if (is_null($val)) {
                        $type = PDO::PARAM_NULL;
                    } else if (is_int($val)) {
                        $type = PDO::PARAM_INT;
                    } else if (is_bool($val)) {
                        $type = PDO::PARAM_BOOL;
                    } else {
                        $type = PDO::PARAM_STR;
                    }
                    $stmt->bindParam($index, $val, $type);
                    $index++;
                }
            }
            // Store log for query
            $debug_sql = $this->interpolateQuery($store_name, $params);
         
            // EXECUTE SQL
            if (!$stmt->execute()) {
                $result[0][0]['id']         = $stmt->errorCode();
                $result[0][0]['error_typ']  = 999;
                $result[0][0]['data']       = $stmt->errorInfo();
                $result[0][0]['remark']     = 'Database query error';
                // Store log error query
                BLog::insert('database_log', 'error', '[Database query error]', $stmt->errorInfo());
                // insert db log
                if ($has_log_act == 1) {
                    $this->insertDatabaseLog($store_name, $params, $stmt->errorInfo());
                }
            }
            // SET FETCH MODE
            if ($option === 'FETCH_NUM') {
                $stmt->setFetchMode(PDO::FETCH_NUM);
            } else {
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
            }
            // RESULT
            $idx = 0;
            do {
                $result[$idx] = array();
                while ($sql_result = $stmt->fetch()) {
                    array_push($result[$idx], $sql_result);
                }
                //
                if (count($result[$idx]) == 0) {
                    if ($blank) {
                        $result[$idx] = $this->getCols($stmt);
                    }
                }
                //
                ++$idx;
            } while ($stmt->nextRowset());
            // insert db log
            if ($has_log_act == 1) {
                $this->insertDatabaseLog($store_name, $params, '');
            }
            // log store procedure query
            $dt = Carbon::now();
            $end_time = $dt->valueOf();
            $ms = $this->getExecuteTime($start_time, $end_time);
            BLog::insert('database_log', 'debug', "[Store Procedure] Total Time $ms ms >>> " . $debug_sql);
        } catch (PDOException $e) {
            $result[0][0]['id']         = $e->getCode();
            $result[0][0]['error_typ']  = 999;
            $result[0][0]['data']       = 'Exception';
            $result[0][0]['remark']     = $e->getMessage();
            // Store log error exception
            BLog::insert('database_log', 'error', $e->getMessage());
            // insert db log
            if ($has_log_act == 1) {
                $this->insertDatabaseLog($store_name, $params, $e->getMessage());
            }
            // log store procedure query
            $dt = Carbon::now();
            $end_time = $dt->valueOf();
            $ms = $this->getExecuteTime($start_time, $end_time);
            BLog::insert('database_log', 'debug', "[Store Procedure] Total Time $ms ms >>> " );
        }
        return $result;
    }

    /**
     * getCols
     *
     * @param  mixed $stmt
     * @return void
     */
    private function getCols($stmt)
    {
        $cols = [];
        for ($i = 0, $cnt = $stmt->columnCount(); $i < $cnt; ++$i) {
            $col = $stmt->getColumnMeta($i);
            $cols[0][$col['name']] = null;
        }
        return $cols;
    }

    /**
     * interpolateQuery
     *
     * @param  mixed $query
     * @param  mixed $params
     * @return void
     */
    private function interpolateQuery($query, $params)
    {
        $params_str = implode("','", $params);
        $query = "EXEC " . $query;
        //
        if ($params_str !== '') {
            $query .= " '" . $params_str . "';";
        }
        return $query;
    }

    /**
     * insertDatabaseLog
     *
     * @param  String $store_name
     * @param  Array $params
     * @param  String $error_message
     * @return void
     */
    public function insertDatabaseLog($store_name = '', $params = [], $error_message = '')
    {
        try {
            if ($store_name == '') {
                return false;
            }
            // get  prg_id
            if (strlen($store_name) < 8) {
                return false;
            }
            $act_position = strpos($store_name, '_ACT');
            $prg_id = strtolower(substr($store_name, 4, $act_position - 4));
            $json_array = [];
            if (isset($params['json']) && $params['json'] != '') {
                $json_array = json_decode($params['json'], true);
            }
            $log['prs_date'] = Carbon::now();
            $log['prs_user_id'] = $json_array['staff_cd'] ?? session('_login_session')->staff_cd;
            $log['prs_prg_id'] = $prg_id;
            $log['prs_ip'] = $json_array['ip'] ?? '';
            $log['spc'] = $store_name;
            $log['spc_params'] = $this->interpolateQuery($store_name, $params);
            $log['error_message'] = $error_message;
            return $this->log_repo->insertLog($log);
        } catch (\Throwable $th) {
            // Store log error exception
            BLog::insert('database_log', 'error', $th->getMessage());
        }
    }

    /**
     * getExecuteTime
     *
     * @param  mixed $time_start
     * @param  mixed $time_end
     * @return void
     */
    private function getExecuteTime($time_start, $time_end)
    {
        return $time_end - $time_start;
    }
}
