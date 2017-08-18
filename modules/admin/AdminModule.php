<?php

namespace svsoft\yii\modules\admin;

use svsoft\yii\modules\admin\components\BaseAdminModule;
use yii\filters\AccessControl;
use yii\web\AssetManager;
use yii\web\ErrorHandler;

/**
 * admin module definition class
 */
class AdminModule extends BaseAdminModule
{

    /**
     * @inheritdoc
     */

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Добавляем шаблон для crud в gii
        $gii = $this->getModule('gii');

        if (!$gii)
            $gii = \Yii::$app->getModule('gii');

        if ($gii)
        {
            $giiCrudConfig = &$gii->generators['crud'];

            if(empty($giiCrudConfig['class']))
                $giiCrudConfig['class'] = 'yii\gii\generators\crud\Generator';

            $giiCrudConfig['templates']['adminlte'] = '@svs-admin/gii/templates/crud/simple';
        }
    }
}
