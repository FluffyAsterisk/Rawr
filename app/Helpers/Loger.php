<?php

namespace App\Helpers;

class Loger {
    private $logFile;
    private $logerName;
    public function __construct(private \App\Core\App $app) {
        $this->createLogFile();
    }

    public function prettyPrint($value) {
        print_r('<pre>');
        print_r($value);
        print_r('</pre>');
    }

    public function setName($name) {
        $this->logerName = $name;
    }

    public function debug($message) {
        $this->writeToLogFile($message, 'debug');
    }

    public function info($message) {
        $this->writeToLogFile($message, 'info');
    }

    public function notice($message) {
        $this->writeToLogFile($message, 'notice');
    }

    public function warning($message) {
        $this->writeToLogFile($message, 'warning');
    }

    public function error($message) {
        $this->writeToLogFile($message, 'error');
    }

    private function writeToLogFile($message, $msgType) {
        $str = "{$this->getCurrentTimestamp()} " . "{$this->logerName}." ?? '';
        $str .= strtoupper($msgType) . ': ' . $message . PHP_EOL;

        if ( fwrite( $this->logFile, $str) === false ) {
            trigger_error("Can't write info into log file", E_USER_WARNING);
        }
    }

    private function getCurrentTimestamp() {
        $now = \DateTime::createFromFormat('U.u', microtime(true));
        return $now->format('[Y-m-d H:i:s.u]');
    }

    private function getCurrentDate() {
        return date('Y-m-d', time());
    }

    private function createLogFile() {
        $logFile = $this->app->logs_path().$this->getCurrentDate().".log";

        $o = fopen($logFile, 'a');

        if ( isset($this->logFile) ) { fclose($this->logFile); }
        $this->logFile = $o;
    }

    public function __destruct() {
        fclose($this->logFile);
    }
}
