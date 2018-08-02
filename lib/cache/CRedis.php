<?php

    class CRedis {
        private $__redis = array();
        private $__virtualNode = array();
        private $__virtualKeys = array();
        private $__virtualKey = 0;
        private $__virtualNum = 10;
        private $__keyNode = 0;

        public function __construct($config) {
            foreach ($config['host'] as $key => $val) {
                for ($i = 0; $i < $this->__virtualNum; $i++) {
                    $this->__virtualNode[abs(crc32($val . '#' . $i))] = $val . '#' . $i;
                }
            }
            ksort($this->__virtualNode);
            $this->__virtualKeys = array_keys($this->__virtualNode);
        }

        private function __connect($key) {
            $this->__keyNode = abs(crc32($key));
            $this->__virtualKey = $this->__findNode();
            $host = $this->__virtualNode[$this->__virtualKey];
            list($host, $num) = explode('#', $host);
            list($host, $port) = explode(':', $host);
            $this->__redis[$this->__virtualKey] = new Redis();
            $this->__redis[$this->__virtualKey]->connect($host, $port);

            return $this->__redis[$this->__virtualKey];
        }

        private function __findNode() {
            $low = $this->__virtualKeys[0];
            $high = $this->__virtualKeys[count($this->__virtualKeys) - 1];
            if ($this->__keyNode < $low || $this->__keyNode > $high) {
                return $low;
            } else {
                foreach ($this->__virtualKeys as $val) {
                    if ($this->__keyNode > $val) {
                        $low = $val;
                    }
                    if ($this->__keyNode < $val) {
                        $high = $val;
                        break;
                    }
                }

                return $this->__keyNode - $low < abs($this->__keyNode - $high) ? $low : $high;
            }
        }

        public function set($key, $value) {
            return $this->__connect($key)->set($key, $value);
        }

        public function get($key) {
            return $this->__connect($key)->get($key);
        }

        public function __destruct() {
            foreach ($this->__redis as $key => $redis) {
                $redis->close();
                unset($this->__redis[$key]);
            }
        }
    }

?>
