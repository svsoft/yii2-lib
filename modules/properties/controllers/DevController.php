<?php

namespace svsoft\yii\modules\properties\controllers;

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

        $this->layout = '@app/modules/admin/views/layouts/main';


        $text = new Text();
        $objects = [$text];

        $text->text = 'Демо';
        $text->code = 'demo';

        $property = $text->getPropertyBySlug('resolution');
        $value = $property->addValue('1920x1280');

        if (!$text->save())
        {
            var_dump($text->getErrors());
            die();
        }

        return $this->render('index', ['objects'=>$objects]);
    }
}
