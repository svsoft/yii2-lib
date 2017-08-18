<?php

namespace svsoft\yii\modules\properties\controllers;

use svsoft\yii\modules\catalog\models\Product;
use svsoft\yii\modules\properties\models\dev\Text;
use Yii;
use yii\web\Controller;

class DevController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $this->layout = '@svs-admin/views/layouts/main';



        $product = Product::findOne(11);


        $objects = [$product];

        var_dump($product->getProperties());
        die();
//
//        $text = new Text();
//        $objects = [$text];
//
//        $text->text = 'Демо';
//        $text->code = 'demo';
//
//        $property = $text->getPropertyBySlug('resolution');
//        $value = $property->addValue('1920x1280');
//
//        if (!$text->save())
//        {
//            var_dump($text->getErrors());
//            die();
//        }

        return $this->render('index', ['objects'=>$objects]);
    }
}
