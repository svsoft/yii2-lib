<?php

namespace svsoft\yii\traits;

trait SluggableTrait
{
    /**
     * @param $slug
     *
     * @return self
     */
    static function findBySlug($slug)
    {
        return static::findOne(['slug'=>$slug]);
    }
}
