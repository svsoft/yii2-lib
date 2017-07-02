<?php

namespace svsoft\yii\modules\main\components;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * content module definition class
 */
class BaseModule extends \yii\base\Module
{
    public function __construct($id, $parent = null, $config = [])
    {
        $path = Yii::getAlias('@svs-lib/modules/' . $id);

        $namespace = 'svsoft\yii\modules\\' . $id;

        $configFilePath = $path  . '/config/config.php';

        if (file_exists($configFilePath))
        {
            $config = ArrayHelper::merge($config, require ($configFilePath));
        }

        $config = ArrayHelper::merge($config, [
            'modules'=>[
                'admin'=>[
                    'class' => $namespace . '\admin\AdminModule',
                    'controllerNamespace' => $namespace . '\admin\controllers',
                    'viewPath' => $this->basePath . '/admin/views',
                ]
            ]
        ]);

        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
