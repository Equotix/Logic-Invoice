<?php
class Log {
    private $file;
    private $handle;

    public function __construct($filename) {
        $this->file = DIR_SYSTEM . 'logs/' . $filename;
        $this->handle = fopen($this->file, 'a');
    }

    public function __destruct() {
        fclose($this->handle);
    }

    public function write($message) {
        fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
    }

    public function get() {
        $data = '';

        if (file_exists($this->file)) {
            if (filesize($this->file) > 5242880) {
                $suffix = array(
                    'B',
                    'KB',
                    'MB',
                    'GB',
                    'TB',
                    'PB',
                    'EB',
                    'ZB',
                    'YB'
                );

                $i = 0;

                while (($size / 1024) > 1) {
                    $size = $size / 1024;
                    $i++;
                }

                $data = basename($this->file) . ' - ' . round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i];
            } else {
                $data = file_get_contents($this->file, FILE_USE_INCLUDE_PATH, null);
            }
        }

        return $data;
    }
}