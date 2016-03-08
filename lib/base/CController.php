<?php

    class CController {
        protected function render($view, $data = array()) {
            extract($data);
            include_once(Yii::app()->base . '/view/' . Yii::app()->view . '/' . Yii::app()->controller . '/' . $view . '.php');
        }
    }

?>