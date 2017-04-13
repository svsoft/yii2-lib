<?php
/**
 * Позваляет хранить прошлые версии объекта ActiveRecord
 *
 * Created by PhpStorm.
 * User: viktor
 * Date: 04.03.2017
 * Time: 14:34
 */

namespace svsoft\yii\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

class PrevVersionsBehavior extends Behavior
{
    /**
     * Полный путь к дирректории где будут хранится все версии всех сущностей
     *
     * @var
     */
    public $dirPath;

    public $countVersions = 5;

    public function init()
    {
        parent::init();

        $this->dirPath = Yii::getAlias($this->dirPath);
        $this->dirPath = FileHelper::normalizePath($this->dirPath);


        $this->countVersions = abs($this->countVersions);
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            //ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * TODO: Сделать очистку и удаление папки
     */
    public function beforeDelete()
    {

    }

    public function beforeSave()
    {
        /**
         * @var $model ActiveRecord
         */
        $model = $this->owner;

        $dirPath = $this->getObjectDirPath();

        if (!file_exists($dirPath))
            FileHelper::createDirectory($dirPath);

        if (!is_writable($dirPath))
            throw new Exception('Directory "' . $dirPath . '" access deny');

        $currentModel = $model::findOne($model->getPrimaryKey());

        file_put_contents($dirPath . time(), serialize($currentModel->getAttributes()));

        // Проверяем количество элементов? если задано countVersions
        if ($this->countVersions)
        {
            $versions = $this->getPrevVersions();
            $count = count($versions);

            if($count > $this->countVersions)
            {
                for($i = 0; $i < ($count - $this->countVersions); $i++)
                {
                    $filename = $versions[$i];
                    unlink($dirPath . $filename);
                }
            }
        }
    }

    /**
     * Получает полный пудь до папки конкеретного обхекта
     *
     * @return bool|string
     */
    public function getObjectDirPath()
    {
        $model = $this->owner;

        $id = $model->getPrimaryKey();

        if (!$id)
            return false;

        $table = $model::tableName();

        return  $this->dirPath . '/' . $table. '/' . $id . '/';
    }

    /**
     * Получает все прошлые версии сущности
     *
     * @return array
     */
    public function getPrevVersions()
    {
        $dirPath = $this->getObjectDirPath();

        $files = FileHelper::findFiles($dirPath);

        foreach($files as $file)
        {
            $time = pathinfo($file, PATHINFO_FILENAME);

            $versions[] = $time;
        }

        sort($versions);

        return $versions;
    }

    /**
     * Получает unixtime последней прошлой версии
     *
     * @return mixed
     */
    public function getLastPrevVersion()
    {
        $versions = $this->getPrevVersions();

        return $versions[count($versions)-1];
    }

    /**
     * Загружает в сущность прошлую версию
     *
     * @param $time
     *
     * @throws Exception
     */
    public function loadPrevVersion($time)
    {
        $dirPath = $this->getObjectDirPath();

        $filePath = $dirPath . $time;

        if (!file_exists($filePath))
            throw new Exception('Does not exist');

        $data = file_get_contents($filePath);

        if (!$data = unserialize($data))
            throw new Exception('Data is not valid');

        $this->owner->setAttributes($data);
    }
}