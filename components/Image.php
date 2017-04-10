<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 08.02.2017
 * Time: 11:06
 */

namespace svsoft\yii\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use Imagine;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class Image extends Component
{
    public $resizeDir;

    /**
     * Массив настроек превьюшек
     *
     * @var
     */
    public $thumbs;

    protected $resizeDirPath;

    protected $resizeDirWeb;

    public function init()
    {
        parent::init();

        $this->validateParams();

        $this->resizeDirPath = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $this->resizeDir;

        $this->resizeDirWeb = Yii::getAlias('@web') . DIRECTORY_SEPARATOR . $this->resizeDir;

        if (!file_exists($this->resizeDirPath))
        {
            mkdir($this->resizeDirPath, 0777, true);
        }
    }

    protected function validateParams()
    {
        $this->resizeDir = trim($this->resizeDir, '.' . DIRECTORY_SEPARATOR);

        if (!$this->resizeDir)
            throw new InvalidConfigException('Invalid property resizeDir in  component image');

        foreach($this->thumbs as $name=>&$thumb)
        {
            $thumb[0] = (int)ArrayHelper::getValue($thumb, 0);
            $thumb[1] = (int)ArrayHelper::getValue($thumb, 1);
            if ($thumb[0] <= 0 || $thumb[1] <= 0)
                throw new InvalidConfigException('Invalid property thumbs in component image');
        }
    }

    public function crop($src, $thumb)
    {
        if (!isset($this->thumbs[$thumb]))
            return false;

        $thumbConfig = $this->thumbs[$thumb];

        $width = $thumbConfig[0];
        $height = $thumbConfig[1];
        $mode = ArrayHelper::getValue($thumbConfig, 2);

        $paramHash = md5(implode('-',$thumbConfig));

        if (!$mode)
            $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;

        $path = realpath($_SERVER['DOCUMENT_ROOT'] . $src);

        if (!$path)
            return false;

        $pathinfo = pathinfo($path);

        $resizeFileName =  "{$pathinfo['filename']}_{$thumb}_{$paramHash}.{$pathinfo['extension']}";

        $resizeDir = pathinfo($src, PATHINFO_DIRNAME);

        $resizeSrc = $this->resizeDirWeb . $resizeDir . $resizeFileName;

        // Путь до файла с ресайзом
        $resizePath = $this->resizeDirPath . $resizeDir . $resizeFileName;

        if (file_exists($resizePath))
            return $resizeSrc;

        // Проверяем созданали дирректория где будет лежать кропнутый файл, если нет то создаем
        $resizeDirPath = pathinfo($resizePath, PATHINFO_DIRNAME);
        if (!file_exists($resizeDirPath))
            mkdir($resizeDirPath, 0777, true);

        $imagine = new Imagine\Gd\Imagine();
        $size    = new Imagine\Image\Box($width, $height);

        $imagine->open($path)
            ->thumbnail($size, $mode)
            //->save($this->resizeDirPath . DIRECTORY_SEPARATOR);
            ->save($resizePath, array('jpeg_quality' => 90));

        return  $resizeSrc;
    }
}