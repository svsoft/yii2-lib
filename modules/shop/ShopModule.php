<?php

namespace svsoft\yii\modules\shop;

use svsoft\yii\modules\main\components\BaseModule;

/**
 * main module definition class
 *
 * @property Catalog $catalog
 */

class ShopModule extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'svsoft\yii\modules\shop\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
