<?php

    class site extends Controller {
        public function index() {
            $this->render('index', array(
                'data' => array(User::model()->get(), User::model()->set())
            ));
        }
    }

?>