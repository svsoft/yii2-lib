<?php

namespace svsoft\yii\modules\catalog;

use svsoft\yii\modules\catalog\components\Catalog;
use svsoft\yii\modules\main\components\BaseModule;

/**
 * main module definition class
 *
 * @property Catalog $catalog
 */

class CatalogModule extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'svsoft\yii\modules\catalog\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
