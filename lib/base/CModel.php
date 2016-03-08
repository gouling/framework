<?php

    class CModel implements IModel {
        private static $classMap = array();
        protected $db, $redis;

        public function __construct() {
            $this->db = Yii::app()->db;
            $this->redis = Yii::app()->redis;
        }

        public static function model($className = __CLASS__) {
            if (!isset(self::$classMap[$className])) {
                self::$classMap[$className] = new $className();
            }

            return self::$classMap[$className];
        }

        protected function query($statement, $params = array()) {
            return $this->db->query($statement, $params);
        }

        protected function pager($statement, $params = array(), $pagesize = 10, $page = 1) {
            return $this->db->pager($statement, $params, $pagesize, $page);
        }

        protected function execute($statement, $params = array()) {
            return $this->db->execute($statement, $params);
        }
    }

?>