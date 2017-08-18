<?php

namespace svsoft\yii\modules\admin\components;

use yii\filters\AccessControl;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * content module definition class
 */
class BaseAdminModule extends \yii\base\Module
{
    public $layout = '@svs-admin/views/layouts/main.php';

    public $adminMenu = [];

    public function __construct($id, $parent, $config = [])
    {
        $class = get_class($this);

        $reflectionClass = new \ReflectionClass($class);
        $path = dirname($reflectionClass->getFileName());

        $configFilePath = $path  . '/config/config.php';

        if (file_exists($configFilePath))
        {
            $config = ArrayHelper::merge($config, require ($configFilePath));
        }

        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        //                        'matchCallback' => function ($rule, $action) {
                        //                            return \Yii::$app->user->identity && \Yii::$app->user->identity->isAdmin();
                        //                        }

                    ],
                ],
            ],
        ];
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
