<?php

namespace ImportBundle\Common;

use Carbon\Carbon;
use Pimcore\File;

class Loggy
{
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';

    public static function emergency($name, $value, $message, $organiseByDirectory = true, $useDailyFiles = true)
    {
        self::log($name, $value, $message, self::EMERGENCY, $organiseByDirectory, $useDailyFiles);
    }

    public static function alert($name, $value, $message, $organiseByDirectory = true, $useDailyFiles = true)
    {
        self::log($name, $value, $message, self::ALERT, $organiseByDirectory, $useDailyFiles);
    }

    public static function critical($name, $value, $message, $organiseByDirectory = true, $useDailyFiles = true)
    {
        self::log($name, $value, $message, self::CRITICAL, $organiseByDirectory, $useDailyFiles);
    }

    public static function error($name, $value, $message, $organiseByDirectory = true, $useDailyFiles = true)
    {
        self::log($name, $value, $message, self::ERROR, $organiseByDirectory, $useDailyFiles);
    }

    public static function warning($name, $value, $message, $organiseByDirectory = true, $useDailyFiles = true)
    {
        self::log($name, $value, $message, self::WARNING, $organiseByDirectory, $useDailyFiles);
    }

    public static function notice($name, $value, $message, $organiseByDirectory = true, $useDailyFiles = true)
    {
        self::log($name, $value, $message, self::NOTICE, $organiseByDirectory, $useDailyFiles);
    }

    // public static function info($name, $message, $organiseByDirectory = true, $useDailyFiles = true)
    // {
    //     self::log($name, $message, self::INFO, $organiseByDirectory, $useDailyFiles = true);
    // }
    public static function info($name, $value, $message, $organiseByDirectory = true, $useDailyFiles = true)
    {
        self::log($name, $value, $message, self::INFO, $organiseByDirectory, $useDailyFiles = true);
    }
    public static function infovalue($name, $value, $message, $organiseByDirectory = true, $useDailyFiles = true)
    {
        self::value($name, $value, $message, self::INFO, $organiseByDirectory, $useDailyFiles = true);
    }

    public static function debug($name, $value, $message, $organiseByDirectory = true, $useDailyFiles = true)
    {
        self::log($name, $value, $message, self::DEBUG, $organiseByDirectory, $useDailyFiles);
    }

    private static function log($name, $value, $message, $level, $organiseByDirectory = true, $useDailyFiles = true)
    {
        $directory = self::bakeDirectory($name, $value, $organiseByDirectory);

        $log = self::bakeFile($name, $useDailyFiles, $directory);

        if (!is_dir($directory)) {
            File::mkdir($directory);
        }

        // check for big logfile, empty it if it's bigger than about 200M
        if (is_file($log) && filesize($log) > 200000000) {
            File::put($log, '');
        }

        $content = '[' . Carbon::now() . '] ';
        $content .= '[' . $level . '] ';
        $content .= '[' . self::getformattedMemoryUsage() . '] ';

        $content .= $message . "\r\n";

        self::writeLog($log, $content);
    }

    private static function value($name, $value, $message, $level, $organiseByDirectory = true, $useDailyFiles = true)
    {
        $directory = self::bakeDirectory($name, $value, $organiseByDirectory);

        $log = self::bakeFile($name, $useDailyFiles, $directory);

        if (!is_dir($directory)) {
            File::mkdir($directory);
        }

        // check for big logfile, empty it if it's bigger than about 200M
//        if (is_file($log) && filesize($log) > 200000000) {
//            File::put($log, '');
//        }

//        $content = '[' . Carbon::now() . '] ';
//        $content .= '[' . $level . '] ';
//        $content .= '[' . self::getformattedMemoryUsage() . '] ';

        $content = $message . "\r\n";

        self::writeLog($log, $content);
    }

    private function writeLog($filename, $entry, $mode = 'a+')
    {
        $f = fopen($filename, $mode);
        fwrite($f, $entry);
        fclose($f);
    }

    private static function getformattedMemoryUsage()
    {
        $memUsage = memory_get_usage(true);

        if ($memUsage < 1024) {
            return $memUsage . 'B';
        } elseif ($memUsage < 1048576) {
            return round($memUsage / 1024, 2) . 'KB';
        } else {
            return round($memUsage / 1048576, 2) . 'MB';
        }
    }

    private static function bakeDirectory($name, $value, $organiseByDirectory)
    {
        if ($organiseByDirectory) {
            $directory = PIMCORE_LOG_DIRECTORY . '/pfc/' . strtolower(str_replace(' ', '-', $name) . '/' . $value);
        } else {
            $directory = PIMCORE_LOG_DIRECTORY . '/pfc/' . strtolower(str_replace(' ', '-', $name));
        }

        return $directory;
    }

    private static function bakeFile($name, $useDailyFiles, $directory)
    {
        $name = strtolower(str_replace(' ', '-', $name));
        if ($useDailyFiles) {
            $log = "${directory}/${name}_" . Carbon::parse(Carbon::now())->format('Y-m-d') . '.log';
        } else {
            $log = "${directory}/${name}.log";
        }

        return $log;
    }
}
