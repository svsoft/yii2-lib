<?php

namespace svsoft\yii\modules\main\files;

use svsoft\yii\modules\main\files\models\UploadForm;
use Yii;

/**
 * Class FileAttributeTrait
 *
 * @property FileAttributeBehavior fileAttributeBehavior
 * @property string fileDirPath
 * @property string webDirPath
 *
 * @package svsoft\yii\modules\main\files
 */
trait FileAttributeTrait
{
    /**
     * @var Текущий файловый атрибут с которым работает трейт
     */
    private $_fileAttribute;

    /**
     * @var UploadForm[]
     */
    public $uploadForms = [];

    /**
     * Установить название файлового атрибута, и возвращает $this для дальнейшей работы
     *
     * @param $attribute
     *
     * @return FileAttributeTrait $this
     */
    public function fileAttribute($attribute)
    {
        $this->_fileAttribute = $attribute;

        return $this;
    }

    public function getFileAttribute()
    {
        if (empty($this->_fileAttribute))
        {
            $this->_fileAttribute = current($this->fileAttributeBehavior->attributes);
        }

        return $this->_fileAttribute;
    }

    public function setFilesArray($images)
    {
        $attribute = $this->fileAttribute;

        $this->$attribute = serialize($images);
    }

    public function getFilesArray()
    {
        $attribute = $this->fileAttribute;

        if ($this->$attribute)
            return unserialize($this->$attribute);

        return null;
    }

    public function getFilesArraySrc()
    {
        $attribute = $this->fileAttribute;

        $return = [];

        $web = Yii::getAlias($this->fileAttributeBehavior->webDirPath);

        foreach($this->getFilesArray() as $filename)
            $return[] = $web . '/' . $filename;

        return $return;
    }

    /**
     * @return mixed|string
     */
    public function getFirstFileSrc($blank = true)
    {
        $attribute = $this->fileAttribute;

        $src = $this->getFilesArraySrc();

        if ($src)
            return $src[0];

        if ($blank)
            return Yii::getAlias($this->fileAttributeBehavior->blankSrc);
    }

    public function getUploadForm()
    {
        $attribute = $this->fileAttribute;

        if (empty($this->uploadForms[$attribute]))
        {
            $this->uploadForms[$attribute] = new UploadForm([
                'fileDirPath'     => $this->fileDirPath,
                'webDirPath'      => $this->webDirPath,
                'formNamePostfix' => $attribute
            ]);

            $this->uploadForms[$attribute]->files = $this->getFilesArray();
        }


        return $this->uploadForms[$attribute];
    }


}

