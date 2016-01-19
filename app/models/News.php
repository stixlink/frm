<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 19.01.16
 * Time: 4:00
 */

namespace app\models;


use core;

class News extends core\Model {

    /**
     * Return name table
     *
     * @return string
     */
    public function getTableName() {

        return "news";
    }

    /**
     * Return name pk field
     *
     * @return string
     */
    public function getPKName() {

        return "id";
    }

    static public function instance($className = __CLASS__) {

        return parent::instance($className);
    }

    /**
     * Return formatted date create news
     *
     * @return bool|string
     */
    public function getDate() {

        return date('d.m.Y', $this->date_create);
    }

    /**
     * Return title news
     *
     * @return bool|string
     */
    public function getTitle() {

        return $this->title;
    }

    /**
     * Return body news
     *
     * @return bool|string
     */
    public function getBody() {

        return $this->body;
    }
}
