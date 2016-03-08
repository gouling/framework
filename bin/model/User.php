<?php

    class User extends CModel {
        public static function model($className = __CLASS__) {
            return parent::model($className);
        }

        public function get() {
            return $this->query('SELECT * FROM tbl_school WHERE id<:id', array(':id' => 10));
        }

        public function set() {
            return $this->execute('UPDATE tbl_school SET time=:time WHERE id=:id', array(':time' => time(), ':id' => 1));
        }
    }

?>