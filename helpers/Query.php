<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 10.03.2017
 * Time: 15:59
 */

namespace svsoft\yii\helpers;

use yii\data\Pagination;
use yii\db\ActiveQuery;

class Query
{

    /**
     * @param $dataProvide ActiveQuery
     * @param $pageSize - count items on page
     *
     * @return Pagination
     */
    public static function getPagination(ActiveQuery $dataProvide, $pageSize = 30)
    {
        return new Pagination(['totalCount' => $dataProvide->count(), 'pageSize'=>$pageSize]);
    }

    /**
     * @param ActiveQuery $dataProvide
     * @param Pagination $pagination
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRecords(ActiveQuery $dataProvide, Pagination $pagination)
    {
        return $dataProvide
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
    }
}