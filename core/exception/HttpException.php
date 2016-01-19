<?php

/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 18.01.16
 * Time: 12:22
 */
namespace core\exception;


class HttpException extends \Exception {
    public function __construct($code, $message, \Exception $previous = null) {

        parent::__construct($message, $code, $this);
    }
}
