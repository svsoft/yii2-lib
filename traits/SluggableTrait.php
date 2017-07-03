<?php

namespace svsoft\yii\traits;

trait SluggableTrait
{
    static function findBySlug($slug)
    {
        return static::findOne(['slug'=>$slug]);
    }
}
