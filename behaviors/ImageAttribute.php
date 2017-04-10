<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 06.03.2017
 * Time: 1:16
 */

namespace svsoft\yii\behaviors;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class FileAttribute
 *
 * @package app\base
 */
class ImageAttribute extends FileAttribute
{
    public $blank;

    public function crop($thumbName)
    {
        if ($this->getUrl())
            return Yii::$app->image->crop($this->getUrl(), $thumbName);
    }

    /**
     * Переопределяем для вывода $blank
     *
     * @return bool
     */
    public function getUrl($displayBlank = true)
    {
        if ($url = parent::getUrl())
            return $url;

        if ($displayBlank && $this->blank)
            $url = Yii::getAlias('@web/' . $this->blank);

        return $url;
    }
}