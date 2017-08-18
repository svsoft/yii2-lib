<?php

namespace svsoft\yii\modules\main;

use svsoft\yii\modules\admin\AdminModule;
use svsoft\yii\modules\catalog\models\Product;
use svsoft\yii\modules\shop\components\Cart;
use svsoft\yii\modules\shop\models\CartItem;
use Yii;
use svsoft\yii\modules\main\components\BaseModule;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\Model;
use yii\base\ModelEvent;
use yii\base\Module;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * main module definition class
 *
 * @property Cart $cart
 *
 */

/**
 * Class MainModule
 * @package svsoft\yii\modules\main
 *
 * @property Cart $cart
 */
class MainModule extends Module implements BootstrapInterface
{
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
        Yii::setAlias('@svs-admin', '@svs-lib/modules/admin');
        Yii::setAlias('@svs-main', '@svs-lib/modules/main');
        Yii::setAlias('@svs-properties', '@svs-lib/modules/properties');
        Yii::setAlias('@svs-catalog', '@svs-lib/modules/catalog');
        Yii::setAlias('@svs-shop', '@svs-lib/modules/shop');

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

            // Список модулей
            foreach($this->modules as $id=>$module)
            {
                // $module = ArrayHelper::getValue(Yii::$app->modules, $id);
                if ($module)
                {
                    $class = $module['class'];

                    $reflectionClass = new \ReflectionClass($class);
                    $path = dirname($reflectionClass->getFileName());
                    $namespace = $reflectionClass->getNamespaceName();

                    $migrationsDirPath = $path. '/migrations';
                    if (file_exists($migrationsDirPath))
                        $migrateConfig['migrationNamespaces'][] =  $namespace . '\migrations';
                }
            }
        }

        if ($this->modules['catalog'] && $this->modules['shop'])
        {
            ModelEvent::on(Product::className(), ActiveRecord::EVENT_BEFORE_DELETE, function (ModelEvent $event){

                $cartItems = CartItem::findOne(['product_id'=>$event->sender->product_id]);
                if ($cartItems)
                {
                    $event->sender->addError('beforeDelete', 'Нельзя удалить товар если если он есть в корзине');
                    $event->isValid = false;
                }
            });
        }
    }
}
