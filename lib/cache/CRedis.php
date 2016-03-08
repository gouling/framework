<?php

    class CRedis {
        private $_redis = array();
        private $_virtualNode = array();
        private $_virtualKeys = array();
        private $_virtualKey = 0;
        private $_virtualNum = 10;
        private $_keyNode = 0;

        public function __construct($config) {
            foreach ($config['host'] as $key => $val) {
                for ($i = 0; $i < $this->_virtualNum; $i++) {
                    $this->_virtualNode[abs(crc32($val . '#' . $i))] = $val . '#' . $i;
                }
            }
            ksort($this->_virtualNode);
            $this->_virtualKeys = array_keys($this->_virtualNode);
        }

        private function _connect($key) {
            $this->_keyNode = abs(crc32($key));
            $this->_virtualKey = $this->_findNode();
            $host = $this->_virtualNode[$this->_virtualKey];
            list($host, $num) = explode('#', $host);
            list($host, $port) = explode(':', $host);
            $this->_redis[$this->_virtualKey] = new Redis();
            $this->_redis[$this->_virtualKey]->connect($host, $port);

            return $this->_redis[$this->_virtualKey];
        }

        private function _findNode() {
            $low = $this->_virtualKeys[0];
            $high = $this->_virtualKeys[count($this->_virtualKeys) - 1];
            if ($this->_keyNode < $low || $this->_keyNode > $high) {
                return $low;
            } else {
                foreach ($this->_virtualKeys as $val) {
                    if ($this->_keyNode > $val) {
                        $low = $val;
                    }
                    if ($this->_keyNode < $val) {
                        $high = $val;
                        break;
                    }
                }

                return $this->_keyNode - $low < abs($this->_keyNode - $high) ? $low : $high;
            }
        }

        public function set($key, $value) {
            return $this->_connect($key)->set($key, $value);
        }

        public function get($key) {
            return $this->_connect($key)->get($key);
        }

        public function __destruct() {
            foreach ($this->_redis as $key => $redis) {
                $redis->close();
                unset($this->_redis[$key]);
            }
        }
    }

?>