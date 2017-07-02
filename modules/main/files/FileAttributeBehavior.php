<?php
namespace svsoft\yii\modules\main\files;

use yii\base\Behavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 01.07.2017
 * Time: 12:42
 */

class FileAttributeBehavior extends Behavior
{

    public $attributes;

    public $blankSrc = '@web/images/no-photo.png';

    public $fileDirPath = '@app/web/upload/files';

    public $webDirPath = '@web/upload/files';

    /**
     * @var FileAttributeTrait
     */
    public $owner;

    public function init()
    {
        parent::init();

        if (empty($this->attributes[0]))
        {
            $this->attributes = [$this->attributes];
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeSave()
    {
        foreach($this->owner->uploadForms as $attribute=>$uploadForm)
            $this->owner->$attribute = $uploadForm->getFilesToSave();
    }

    public function afterSave()
    {
        foreach($this->owner->uploadForms as $attribute=>$uploadForm)
            $uploadForm->save();
    }

    public function beforeValidate(\yii\base\ModelEvent $event)
    {
        foreach($this->owner->uploadForms as $attribute=>$uploadForm)
        {
            if(!$uploadForm->validate())
            {
                $event->isValid = false;

                return false;
            }
        }
    }



    public function beforeDelete()
    {
        $dirPath = Yii::getAlias($this->fileDirPath);

        foreach($this->attributes as $attribute)
        {
            foreach($this->owner->getFilesArray($attribute) as $filename)
            {
                $path = $dirPath . '/' . $filename;

                if (file_exists($path))
                    unlink($path);
            }
        }

        return true;
    }

    public function getFilesAttributes()
    {
        return $this->attributes;
    }

    public function getFileAttributeBehavior()
    {
        return $this;
    }

}