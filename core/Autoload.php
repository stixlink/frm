<?php

/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 18.01.16
 * Time: 15:02
 */
namespace core;


class Autoload {

    static public function run() {

        spl_autoload_register(__NAMESPACE__ . '\Autoload::load');

    }

    static function load($class) {

        $path = str_replace("\\", DS, rtrim($class, "\\"));
        $fullPath = BASE_PATH . DS . $path . '.php';
        include_once $fullPath;
    }
}
