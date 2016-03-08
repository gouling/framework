<?php

    class CDBMySQL extends PDO {
        public function __construct($config) {
            parent::__construct($config['dsn'], $config['username'], $config['password'], $config['options']);
        }

        public function query($statement, $params = array()) {
            $ds = $this->prepare($statement);

            return $ds->execute($params) ? $ds->fetchAll(self::FETCH_ASSOC) : array();
        }

        public function pager($statement, $params = array(), $pagesize = 10, $page = 1) {
            return array();
        }

        public function execute($statement, $params = array()) {
            $ds = $this->prepare($statement);
            $ds->execute($params);

            return array(
                'row' => $ds->rowCount(),
                'id' => (int)$this->lastInsertId()
            );
        }
    }

?>