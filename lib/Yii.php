<?php

    class Yii {
        public $controller, $action, $view;
        private static $application, $config, $lib;
        private static $classMap = array();
        private static $coreMap = array(
            'base',
            'db',
            'cache',
        );

        public function __construct() {
            $this->__setException();
            $this->__setCore();
            $this->__setConfig();
            $this->__setRequest();
            $this->__setResource();
        }

        private function __setException() {
            set_error_handler(array($this, 'Error'));
            set_exception_handler(array($this, 'Exception'));
        }

        public function Error($code, $msg) {
            exit("code: {$code}, message: {$msg}");
        }
        
        public function Exception($exception) {
            exit("code: {$exception->getCode()}, message: {$exception->getMessage()}");
        }

        private function __setCore() {
            foreach (self::$coreMap as $core) {
                set_include_path(get_include_path() . PATH_SEPARATOR . getcwd() . DIRECTORY_SEPARATOR . self::$lib . DIRECTORY_SEPARATOR . $core);
            }
        }

        private function __setConfig() {
            if (isset(self::$config['import'])) {
                $this->__setImport(self::$config['import']);
                unset(self::$config['import']);
            }
            if (isset(self::$config['component'])) {
                $this->__setComponent(self::$config['component']);
                unset(self::$config['component']);
            }
            foreach (self::$config as $config_key => $config_value) {
                $this->$config_key = $config_value;
            }
        }

        private function __setImport($classMap) {
            foreach ($classMap as $class) {
                set_include_path(get_include_path() . PATH_SEPARATOR . getcwd() . DIRECTORY_SEPARATOR . self::$config['base'] . DIRECTORY_SEPARATOR . $class);
            }
        }

        private function __setComponent($components) {
            foreach ($components as $component_key => $component_config) {
                if (isset($component_config['class'])) {
                    $this->$component_key = new $component_config['class']($components[$component_key]);
                }
            }
        }

        private function __setRequest() {
            $this->controller = isset($_GET['c']) ? $_GET['c'] : self::$config['defaultController'];
            $this->action = isset($_GET['a']) ? $_GET['a'] : self::$config['defaultAction'];
            $this->view = isset($_GET['view']) ? $_GET['view'] : self::$config['defaultView'];
        }

        private function __setResource() {
            $this->image = "{$this->base}/view/{$this->view}/{$this->image}";
        }

        public static function createWebApplication($config, $lib) {
            if (!isset(self::$application)) {
                self::$config = include_once($config);
                self::$lib = $lib;
                self::$application = new self();
            }

            return self::$application;
        }

        public static function app() {
            return self::$application;
        }

        public function run() {
            header('Content-Type: text/html; charset=' . self::$config['charset']);
            date_default_timezone_set(self::$config['timeZone']);
            $controller = new $this->controller();
            $action = $this->action;
            $controller->$action();
        }

        public static function autoload($className) {
            if (!isset(self::$classMap[$className])) {
                self::$classMap[] = $className;
                include_once($className . '.php');
            }
        }
    }

    spl_autoload_register(array('Yii', 'autoload'));
?>
