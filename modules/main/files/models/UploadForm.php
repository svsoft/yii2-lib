<?php

namespace svsoft\yii\modules\main\files\models;

use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use Yii;

/**
 * TODO: Добавить поведение и возможно трайт
 * TODO: Сделать картинку бланк
 * TODO: Сделать разные типы файлов. возможно наследование классов
 * TODO: Сделать форму модель для одного файла для доступа через модель к которой прекреплены фотографии
 *
 *
 * Class UploadForm
 *
 * @property array $files
 *
 * @package app\modules\content\admin\models
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $uploadedFiles;

    public $fileDirPath;

    public $webDirPath;

    public $formNamePostfix;

    /**
     * Список текущих имен файлов
     *
     * @var array
     */
    public $names = [];

    /**
     * Массив файлов на удаление
     *
     * @var array
     */
    public $unlinks = [];

    private $uploadedFileNames;

    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->fileDirPath = Yii::getAlias($this->fileDirPath);
        $this->webDirPath = Yii::getAlias($this->webDirPath);
    }

    public function rules()
    {
        return [
            [['uploadedFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 4,'checkExtensionByMimeType'=>false],
            [['names','unlinks'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'uploadedFiles' => 'Загрузка файлов',
        ];
    }

    protected function generateFileNames()
    {
        $names = [];
        foreach ($this->uploadedFiles as $key=>$file)
        {
            $filename = Yii::$app->getSecurity()->generateRandomString(15) . '.' . $file->extension;

            $names[$key] = $filename;
        }

        return $names;
    }

    /**
     * Получает список имен загруженных файлов для сохранения
     *
     * @return array
     */
    public function getUploadedFileNames($key = null)
    {
        if ($this->uploadedFileNames === null)
            $this->uploadedFileNames = $this->generateFileNames();

        if ($key === null)
            return $this->uploadedFileNames;

        return ArrayHelper::getValue($this->uploadedFileNames, $key);
    }

    /**
     * @return bool
     */
    public function upload()
    {
        foreach ($this->uploadedFiles as $key=>$file)
        {
            $filename = $this->getUploadedFileNames($key);

            FileHelper::createDirectory($this->fileDirPath);

            // var_dump($this->fileDirPath . '/' . $filename ); die();

            $file->saveAs($this->fileDirPath . '/' . $filename );
        }

        return true;
    }

    public function save()
    {
        $this->upload();
        $this->delete();
    }

    // Удаляет файлы с диска
    public function delete()
    {
        foreach($this->files as $file)
        {
            if (!$file['unlink'])
                continue;

            $path = $this->fileDirPath . '/' . $file['name'];

            if (file_exists($path))
                unlink($path);
        }
    }

    /**
     * Возвращает массив ранее загруженных файлов
     *
     * @return array
     */
    public function getFiles()
    {
        $return = [];

        $web = Yii::getAlias($this->webDirPath);

        foreach($this->names as $key=>$name)
        {
            $file = [
                'name' => $name,
                'unlink'=> (bool)ArrayHelper::getValue($this->unlinks, $key, false),
                'new'=>false,
                'src'=>$web . '/' . $name,
                'id'=>$key
            ];

            $return[] = $file;
        }

        return $return;
    }

    /**
     * Возвращает сериализованный массив список для сохранения в БД
     *
     * @return array
     */
    public function getFilesToSave()
    {
        $return = [];

        // Обходим текущие файлы
        foreach($this->getFiles() as $file)
        {
            // пропускаем файлы на удаление
            if ($file['unlink'])
                continue;

            $return[] = $file['name'];
        }

        // Собираем имена файлов новых загруженных файлов
        foreach($this->getUploadedFileNames() as $filename)
            $return[] = $filename;

        return serialize($return);
    }

    /**
     * @param array $files
     */
    public function setFiles($files)
    {
        $this->names = $files ? $files : [];
        $this->unlinks = [];
    }

    public function formName()
    {
        $formName = parent::formName(); // TODO: Change the autogenerated stub

        if (!$this->formNamePostfix)
            return $formName;

        $formName .=  $this->formNamePostfix;

        return $formName;
    }
}