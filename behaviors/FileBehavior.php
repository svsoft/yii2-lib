<?php
/**
 * TransliterationBehavior automatically translatirates one of attribute to other attribute of an ActiveRecord
 * object when certain events happen.
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
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class FileBehavior extends Behavior
{
    public $attributes;

    /**
     * @var FileAttribute[]
     */
    private $_fileAttributes = [];

    public function init()
    {
        parent::init();

        if (empty($this->attributes[0]))
        {
            $this->attributes = [$this->attributes];
        }

        foreach($this->attributes as $config)
        {
            $this->_fileAttributes[$config['attribute']] = $this->createFileAttribute($config);
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            //ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            //ActiveRecord::EVENT_AFTER_FIND  => 'afterFind',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }


    /**
     * @param $attr
     *
     * @return FileAttribute
     */
    public function fileAttribute($attr)
    {
        if (!ArrayHelper::keyExists($attr, $this->_fileAttributes))
        {
            throw new Exception('Attribute "'.$attr.'" not found');
        }

        if (is_string($this->owner->$attr))
            $this->_fileAttributes[$attr]->filename = $this->owner->$attr;

        return $this->_fileAttributes[$attr];
    }

    public function getFileAttributes()
    {
        foreach($this->_fileAttributes as $attribute=>$fileAttribute)
        {
            $this->fileAttribute($attribute);
        }

        return $this->_fileAttributes;
    }

    /**
     * загружает в модель объекты класса UploadedFile
     *
     * @param string $attr - название файлового атрибута
     * @param UploadedFile|null $uploadFile
     */
    public function loadFile($attr, UploadedFile $uploadFile = null)
    {
        if (!$uploadFile)
            return;

        $file = $this->fileAttribute($attr);

        $this->owner->$attr = $uploadFile;

        $file->setUploadFile($uploadFile);
    }

    public function beforeDelete()
    {
        foreach($this->getFileAttributes() as $fileAttribute)
        {
            if ($fileAttribute->exists())
                $fileAttribute->deleteFile();
        }
    }

    public function beforeSave()
    {
        /**
         * @var $model ActiveRecord
         */
        $model = $this->owner;

        // Обработчик только для ActiveRecord
        if (!$model instanceof ActiveRecord)
            return;

        // Проверяем все файловые атрибуты, чтоб удалить файлы с диска
        foreach($this->getFileAttributes() as $fileAttribute)
        {
            $attr = $fileAttribute->attribute;

            $attrValue = $fileAttribute->filename;

            if ($fileAttribute->getUploadFile())
            {
                $filename = call_user_func($fileAttribute->callbackFilename, $fileAttribute->getUploadFile(), $model);
                $attrValue = $fileAttribute->save($filename);
            }

            $attrOldValue = $model->getOldAttribute($attr);

            if ($attrValue != $attrOldValue && $attrOldValue)
            {
                $fileOld = clone $fileAttribute;
                $fileOld->filename = $attrOldValue;
                $fileOld->deleteFile();
            }

            $model->setAttribute($attr, $attrValue);
        }
    }

    /**
     * Ищет по названию конфигурацию в масииве $this->attributes
     *
     * @param $attrName
     *
     * @return mixed
     * @throws Exception
     */
    protected function getAttributeByName($attrName)
    {
        foreach($this->attributes as $attribute)
            if ($attribute['attribute']==$attrName)
                return $attribute;

        throw new Exception('Attribute "'.$attrName.'" not found');
    }

    /**
     * Returns uploaded file name on  basis of function callbackFilename
     *
     * @param string $attribute
     * @param UploadedFile $uploadedFile
     *
     * @return string
     */
    protected function getUploadedFileName($attribute, UploadedFile $uploadedFile)
    {
        $callback = $attribute['callbackFilename'];

        return $callback($uploadedFile, $this->owner);
    }

    protected function createFileAttribute($config)
    {
        $default = [
            'class'=>FileAttribute::className(),
            'dir'=>'upload',
            'callbackFilename' =>
                function(UploadedFile $uploadFile, $model = null)
                {
                    return md5(rand(0, 1000000). time() . $uploadFile->name) . '.' . $uploadFile->extension;
                },
        ];

        $config = ArrayHelper::merge($default, $config);


        return Yii::createObject($config);
    }
}