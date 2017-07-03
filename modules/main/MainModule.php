<?php

namespace svsoft\yii\modules\main;

use svsoft\yii\modules\main\admin\AdminModule;
use Yii;
use svsoft\yii\modules\main\components\BaseModule;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * main module definition class
 */
class MainModule extends \yii\base\Module implements BootstrapInterface
{
    /**
     * Список модулей у которых будет админка
     *
     * @var array
     */
    public $adminModules = [];

    /**
     * MainModule constructor.
     *
     * @param string $id
     * @param null|\yii\base\Module $parent
     * @param array $config
     */
    public function __construct($id, $parent, $config = [])
    {
        $config['modules']['admin'] = [
            'class' => __NAMESPACE__ . '\admin\AdminModule',
            'controllerNamespace'=>__NAMESPACE__ . '\admin\controllers',
            'viewPath'=>$this->basePath . '/admin/views'
        ];

        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        Yii::setAlias('@webroot', '@app/web');
        Yii::setAlias('@web', '/');
        Yii::setAlias('@views', '@app/views');

        Yii::setAlias('@svs-lib', '@vendor/svsoft/yii2-lib');
        Yii::setAlias('@svs-main', '@svs-lib/modules/main');

        Yii::setAlias('@upload', '@app/web/upload');
        Yii::setAlias('@files', '@upload/files');
        Yii::setAlias('@images', '@upload/images');
        Yii::setAlias('@web-upload', '@web/upload');
        Yii::setAlias('@web-images', '@web-upload/images');
        Yii::setAlias('@web-files', '@web-upload/files');


        // Для консольного приложения добавляем пути миграция
        if ($app instanceof \yii\console\Application)
        {
            $migrateConfig = &Yii::$app->controllerMap['migrate'];
            if (empty($migrateConfig['class']))
                $migrateConfig['class'] = 'yii\console\controllers\MigrateController';

            // Список модулей для миграций
            foreach($this->adminModules as $moduleId)
            {
                $path = Yii::getAlias('@svs-lib/modules') . '/' . $moduleId;
                $namespace = 'svsoft\yii\modules\\' . $moduleId;

                if (file_exists($path. '/migrations'))
                    $migrateConfig['migrationNamespaces'][] =  $namespace . '\migrations';
            }
        }

        // Добавляем кодогенератор
        $giiCrudConfig = &$app->getModule('gii')->generators['crud'];

        if (empty($giiCrudConfig['class']))
            $giiCrudConfig['class'] = 'yii\gii\generators\crud\Generator';

        $giiCrudConfig['templates']['adminlte'] = '@svs-main/gii/templates/crud/simple';
    }}
