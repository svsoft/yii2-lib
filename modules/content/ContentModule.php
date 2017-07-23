<?php

namespace svsoft\yii\modules\content;

use svsoft\yii\modules\main\components\BaseModule;

/**
 * main module definition class
 *
 * @property Catalog $catalog
 */

class ContentModule extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'svsoft\yii\modules\content\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
