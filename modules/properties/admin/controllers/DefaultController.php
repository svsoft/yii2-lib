<?php

namespace svsoft\yii\modules\properties\admin\controllers;

use yii\web\Controller;

/**
 * Default controller for the `admin2` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
