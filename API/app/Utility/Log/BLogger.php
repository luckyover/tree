<?php

namespace App\Utility\Log;

use Illuminate\Support\Facades\Log;

class BLogger
{
    /**
     * insert log 
     *
     * @param  String $log_channel : stderr, database_log, mail_log | default : stderr
     * @param  String $log_level : default : debug | emergency, alert, critical, error, warning, notice, info, and debug.
     * @param  String $message
     * @param  Array $contextual 
     * @return void
     */
    public function insert($log_channel = 'stderr', $log_level = 'debug', $message = '', $contextual  = [])
    {
        // if LOG_DRIVER has not value => show all in stderr (AWS CloudWatch)
        if (env('LOG_CHANNEL','') == 'stderr') {
            $this->processError('stderr', $log_level, $message, $contextual);
        }else{
            $this->processError($log_channel, $log_level, $message, $contextual);
        }
    }

    /**
     * processError
     *
     * @param  mixed $log_channel
     * @param  mixed $log_level
     * @param  mixed $message
     * @param  mixed $options
     * @return void
     */
    public function processError($log_channel = 'stack', $log_level = 'debug', $message = '', $contextual = [])
    {
        if ($log_level == 'emergency') {
            Log::channel($log_channel)->emergency($message,$contextual);
        } elseif ($log_level == 'alert') {
            Log::channel($log_channel)->alert($message,$contextual);
        } elseif ($log_level == 'critical') {
            Log::channel($log_channel)->critical($message,$contextual);
        } elseif ($log_level == 'alert') {
            Log::channel($log_channel)->alert($message,$contextual);
        } elseif ($log_level == 'error') {
            Log::channel($log_channel)->error($message,$contextual);
        } elseif ($log_level == 'warning') {
            Log::channel($log_channel)->warning($message,$contextual);
        } elseif ($log_level == 'notice') {
            Log::channel($log_channel)->notice($message,$contextual);
        } elseif ($log_level == 'info') {
            Log::channel($log_channel)->info($message,$contextual);
        } else {
            Log::channel($log_channel)->debug($message,$contextual);
        }
    }
}
