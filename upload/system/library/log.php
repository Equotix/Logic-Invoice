<?php
class Log {
    private $handle;

    public function __construct($filename) {
        $this->handle = fopen(DIR_SYSTEM . 'logs/' . $filename, 'a');
    }

    public function __destruct() {
        fclose($this->handle);
    }

    public function write($message) {
        fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
    }
}