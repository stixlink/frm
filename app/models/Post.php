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

/**
 * Class Post
 *
 * @property int    $id
 * @property string $title
 * @property string $body
 * @property string $image_name
 * @property int    $date_update
 * @property int    $active
 */
class Post extends core\Model {

    static $uploadDir = 'uploads';
    public $image;

    /**
     * Return name table
     *
     * @return string
     */
    public function getTableName() {

        return "post";
    }

    /**
     * Return name pk field
     *
     * @return string
     */
    public function getPKName() {

        return "id";
    }

    /**
     * @param string $className
     *
     * @return mixed
     */
    public static function instance($className = __CLASS__) {

        return parent::instance($className);
    }

    /**
     * Return formatted date update post
     *
     * @return bool|string
     */
    public function getDate() {

        return date('d.m.Y', $this->date_update);
    }

    /**
     * Return title post
     *
     * @return bool|string
     */
    public function getTitle() {

        return $this->title;
    }

    /**
     * Return body post
     *
     * @return bool|string
     */
    public function getBody() {

        return $this->body;
    }

    /**
     * @return array
     */
    public function getTableFields() {

        return [
            'id',
            'title',
            'body',
            'date_update',
            'active',
            'image_name',
        ];
    }

    public function beforeUpdate() {

        $this->date_update = time();
        parent::beforeUpdate();
    }

    public function beforeInsert() {

        $this->date_update = time();
        parent::beforeInsert();
    }

    public function afterUpdate() {

        $this->saveImage();
        parent::afterUpdate();
    }

    public function afterInsert() {

        $this->saveImage();
        parent::afterInsert();
    }

    public function afterDelete() {

        $this->deleteImage();
        parent::afterDelete();
    }


    public function getImageName() {

        return "post_" . $this->id;
    }

    public function getExtImage() {

        return $this->img_ext;
    }

    public function getUrlImage() {

        if ($this->getImageName() && $this->getExtImage()) {
            return '/' . static::$uploadDir . '/' . $this->getImageName() . "." . $this->getExtImage() . "?v=" . time();
        }

        return null;
    }

    public function deleteImage() {

        $uploadfile = BASE_PATH . '/' . static::$uploadDir . "/" . $this->getImageName();
        if (file_exists($uploadfile . '.' . $this->getExtImage())) {
            unlink($uploadfile . '.' . $this->getExtImage());
        }
    }

    public function saveImage() {

        if (isset($_FILES['image'])) {
            $imageinfo = getimagesize($_FILES['image']['tmp_name']);
            if ($imageinfo['mime'] != 'image/jpg' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png') {
                $this->setError('image', "Sorry, we only accept JPG and PNG images\n");

                return null;
            }
            $info = pathinfo($_FILES['image']['name']);
            $ext = '.' . $info['extension'];
            $uploadfile = BASE_PATH . '/' . static::$uploadDir . "/" . $this->getImageName();
            $this->deleteImage();

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile . $ext)) {
                $res = $this->execute("UPDATE `{$this->getTableName()}` SET img_ext='{$info['extension']}' WHERE id='{$this->id}';", [], true, false);
                if ($res) {
                    return true;
                } else {
                    @unlink($uploadfile);
                }
            } else {
                $this->setError('image', "File uploading failed.\n");

                return null;
            }
        }
    }
}
