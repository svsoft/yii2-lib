<?php

namespace svsoft\yii\modules\main\files;

use \svsoft\yii\modules\main\files\FileAttributeTrait;
use \yii\helpers\Html;

class FileAttributeHelper
{
    /**
     * @param FileAttributeTrait $model
     * @param bool $attribute
     * @param array $imgOptions
     *
     * @return string
     */
    static function getHtmlImages($model, $attribute = false, $imgOptions = [])
    {
        if ($attribute)
            $model->setFileAttribute($attribute);

        $html = '';

        foreach($model->getFilesArraySrc() as $src)
        {
            $html .= Html::img($src, $imgOptions);
        }

        return $html;
    }
}