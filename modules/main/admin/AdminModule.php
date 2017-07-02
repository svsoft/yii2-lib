<?php

namespace svsoft\yii\modules\main\admin;

use yii\filters\AccessControl;
use yii\web\AssetManager;
use yii\web\ErrorHandler;

/**
 * admin module definition class
 */
class AdminModule extends \yii\base\Module
{
    public $layout = '@svs-main/admin/views/layouts/main.php';

    /**
     * @inheritdoc
     */

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
