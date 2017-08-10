<?php

namespace svsoft\yii\modules\shop;

use svsoft\yii\modules\catalog\models\Product;
use svsoft\yii\modules\main\components\BaseModule;
use yii\base\Event;
use yii\db\ActiveRecord;

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
