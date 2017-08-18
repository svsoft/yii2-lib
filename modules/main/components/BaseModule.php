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


        $configFilePath = $this->basePath  . '/config/config.php';

        if (file_exists($configFilePath))
        {
            $config = ArrayHelper::merge($config, require ($configFilePath));
        }

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
