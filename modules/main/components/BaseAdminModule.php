<?php

namespace svsoft\yii\modules\main\components;

use yii\filters\AccessControl;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * content module definition class
 */
class BaseAdminModule extends \yii\base\Module
{
    public $layout = '@svs-main/admin/views/layouts/main.php';

    public $adminMenu = [];

    public function __construct($id, $parent, $config = [])
    {
        $path = $parent->basePath . '/' . $id;

        $namespace = 'svsoft\yii\modules\\' . $parent->id . '\\' . $id;

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
