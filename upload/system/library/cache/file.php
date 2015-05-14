<?php

class CacheFile {

    private $expire;

    public function __construct($expire = 3600) {
        $this->expire = $expire;

        $files = glob(DIR_SYSTEM . 'cache/cache.*');

        if ($files) {
            foreach ($files as $file) {
                $time = substr(strrchr($file, '.'), 1);

                if ($time < time()) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }
    }

    public function get($key) {
        $files = glob(DIR_SYSTEM . 'cache/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            $handle = fopen($files[0], 'r');

            flock($handle, LOCK_SH);

            $data = fread($handle, filesize($files[0]));

            flock($handle, LOCK_UN);

            fclose($handle);

            return unserialize($data);
        }

        return false;
    }

    public function set($key, $value) {
        $this->delete($key);

        $file = DIR_SYSTEM . 'cache/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);

        $handle = fopen($file, 'w');

        flock($handle, LOCK_EX);

        fwrite($handle, serialize($value));

        fflush($handle);

        flock($handle, LOCK_UN);

        fclose($handle);
    }

    public function delete($key) {
        $files = glob(DIR_SYSTEM . 'cache/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }
}