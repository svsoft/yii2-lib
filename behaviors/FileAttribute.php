<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 06.03.2017
 * Time: 1:16
 */

namespace svsoft\yii\behaviors;

use Yii;
use yii\base\Exception;
use yii\base\Object;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class FileAttribute
 *
 * @package app\base
 */
class FileAttribute extends Object
{
    /**
     * filename that saving in DB
     *
     * @var string
     */
    public $filename = '';

    public $dir;

    public $callbackFilename;

    public $attribute;

    protected $dirPath;

    protected $webDirPath;

    /**
     *
     * @var UploadedFile array
     */
    private $_uploadFile = [];


    public function init()
    {
        if ($this->dir)
            $this->dir = FileHelper::normalizePath(DIRECTORY_SEPARATOR . $this->dir);

        $this->dirPath      = Yii::getAlias('@webroot') . $this->dir . DIRECTORY_SEPARATOR;
        $this->webDirPath   = Yii::getAlias('@web') . $this->dir . DIRECTORY_SEPARATOR;

        parent::init();
    }

    /**
     * @param UploadedFile $file
     */
    public function setUploadFile(UploadedFile $file)
    {
        $this->_uploadFile = $file;
    }

    /**
     * @return UploadedFile
     */
    public function getUploadFile()
    {
        return $this->_uploadFile;
    }

    /**
     * delete cuurent file from disk
     *
     * @throws Exception
     */
    public function deleteFile()
    {
        $path = $this->getPath();

        if ($path && file_exists($path) && is_writable($path))
        {
            if (!unlink($path))
                throw new Exception('File deleting error.');
        }
    }

    public function save($filename = false)
    {
        if (!$this->_uploadFile)
            return $this->filename;

        //throw new Exception('File saving error.');

        if (!$filename)
            $filename = $this->_uploadFile->name;

        $dirPath = $this->dirPath;

        if (!file_exists($dirPath))
            mkdir($dirPath, 0777, true);

        $path = $dirPath . '/' . $filename;

        if (!$this->_uploadFile->saveAs($path))
            throw new Exception('File saving error.');

        return $filename;
    }

    /**
     * Retuen real path to file
     *
     * @return bool|string
     */
    public function getPath()
    {
        if ($this->filename)
            return $this->dirPath . $this->filename;

        return false;
    }

    /**
     * Return url to file
     *
     * @return bool|string
     */
    public function getUrl()
    {
        if ($this->filename)
            return $this->webDirPath . $this->filename;

        return false;
    }

    /**
     * Return url to file
     *
     * @return bool|string
     */
    public function getAbsoluteUrl()
    {
        if ($this->filename)
            return Yii::$app->params['site.url'] . $this->getUrl();

        return false;
    }

    public function __toString()
    {
        return $this->filename;
    }

    /**
     * check file existing
     *
     * @return bool
     */
    public function exists()
    {
        if (!$this->filename)
            return false;

        if (!file_exists($this->getPath()))
            return false;

        return true;
    }

    public function getDirPath()
    {
        return $this->dirPath;
    }


    public function getWebDirPath()
    {
        return $this->webDirPath;
    }
}