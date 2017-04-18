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

use svsoft\yii\behaviors\FileAttribute;
use Yii;
use yii\base\Behavior;
use yii\base\Exception;
use yii\base\Model;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\validators\UniqueValidator;
use yii\web\UploadedFile;

class FileBehavior extends Behavior
{
    public $attributes;

    /**
     * @var FileAttribute[]
     */
    private $_files = [];

    public function init()
    {
        parent::init();

        if (empty($this->attributes[0]))
        {
            $this->attributes = [$this->attributes];
        }

        $default = [
            'attribute'=>'image',
            'class'=>FileAttribute::className(),
            'dir'=>'upload',
            'callbackFilename' =>
                function(UploadedFile $uploadFile, $model)
                {
                    return md5(rand(0, 1000000). time() . $uploadFile->name) . '.' . $uploadFile->extension;
                },
        ];

        foreach($this->attributes as &$attribute)
        {
            $attribute = ArrayHelper::merge($default, $attribute);
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
        if (!ArrayHelper::keyExists($attr, $this->_files))
        {
            $attributeConfig = $this->getAttributeByName($attr);

            //$config = ArrayHelper::filter($attributeConfig, ['class','dirPath','webDirPath']);

            $this->_files[$attr] = $this->createFileAttribute($attributeConfig);
        }

        if (is_string($this->owner->$attr))
            $this->_files[$attr]->filename = $this->owner->$attr;

        return $this->_files[$attr];
    }

    /**
     * Согружает в модель объекты класса UploadedFile
     *
     * @param $attr название файлового атрибута
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


    public function beforeValidate()
    {
        /**
         * @var ActiveRecord $model
         * @var Model $fileAttribute
         *
         */
        $model = $this->owner;

        foreach($this->attributes as &$attribute)
        {
            $attr = $attribute['attribute'];

            $fileAttribute = $model->$attr;

            if($fileAttribute instanceof FileAttribute && !$fileAttribute->validate())
            {
                $errors = $fileAttribute->getErrors('uploadedFile');

                foreach($errors as $error)
                    $model->addError($attr, $error);
            }
        }
    }

    public function setUploadFile($attrName, UploadedFile $file)
    {
        //$fileAttribute = $this->owner->getAttribute($attrName);
        $fileAttribute = $this->owner->$attrName;

        if (!$fileAttribute)
        {
            $attribute = $this->getAttributeByName($attrName);

            $fileAttribute = $this->createFileAttribute($attribute);

            $this->owner->setAttribute($attrName, $fileAttribute);
        }

        $fileAttribute->uploadedFile = $file;
    }

    public function beforeDelete()
    {
        foreach($this->attributes as $attribute)
        {
            $attr = $attribute['attribute'];

            $file = $this->owner->fileAttribute($attr);

            if ($file->exists())
                $file->deleteFile();
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
        foreach($this->attributes as $attribute)
        {
            $attr = $attribute['attribute'];

            $file = $this->fileAttribute($attr);

            $attrValue = $file->filename;

            if ($file->getUploadFile())
            {
                $filename = call_user_func($attribute['callbackFilename'], $file->getUploadFile(), $model);
                $attrValue = $file->save($filename);
            }

            $attrOldValue = $model->getOldAttribute($attr);

            if ($attrValue != $attrOldValue && $attrOldValue)
            {
                $fileOld = clone $file;
                $fileOld->filename = $attrOldValue;
                $fileOld->deleteFile();
            }

            $model->setAttribute($attr, $attrValue);
        }
    }

    /**
     * mark file to delete on save
     *
     * @param $attr
     *
     * @throws Exception
     */
    public function setDeletingFile($attr)
    {
        if (!in_array($attr, $this->fileAttributes))
            throw new Exception('Attribute "'.$attr.'" not found');

        $fileAttribute = $this->owner->getAttribute($attr);

        $fileAttribute->deletingFile = true;
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
     * @param UploadedFile $uploadedFile
     *
     * @return string
     */
    protected function getUploadedFileName($attribute, UploadedFile $uploadedFile)
    {
        $callback = $attribute['callbackFilename'];

        return $callback($uploadedFile, $this->owner);
    }

    protected function createFileAttribute($attribute)
    {
        $config = array_diff_key($attribute, array_flip(['callbackFilename','attribute']));

        $fileAttribute = Yii::createObject($config);

        return $fileAttribute;
    }
}