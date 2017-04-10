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

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\validators\UniqueValidator;

class TransliterationBehavior extends AttributeBehavior
{
    /**
     * flag that make $fillAttribute is unique
     *
     * @var bool
     */
    public $unique = true;

    /**
     * @var string the attribute that will receive timestamp value
     * Set this property to false if you do not want to record the creation time.
     */
    public $sourceAttribute = 'title';

    /**
     * @var string the attribute that will fill transliteration $sourceAttribute.
     */
    public $fillAttribute = 'url';

    /**
     * @inheritdoc
     *
     * In case, when the value is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
     * will be used as value.
     */
    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->fillAttribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->fillAttribute,
            ];
        }
    }

    /**
     * @inheritdoc
     *
     * In case, when the [[value]] is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
     * will be used as value.
     */
    protected function getValue($event)
    {
        if ($this->value === null)
        {
            /**
             * @var ActiveRecord $model
             */
            $model = $this->owner;


            $fillAttributeValue = $model->getAttribute($this->fillAttribute);

            // Если уже заполнено поле, то ничего не желаем
            if (!$fillAttributeValue)
            {
                //$sourceAttributeValue = $model->getDirtyAttributes([$this->sourceAttribute]);
                // Получаем значение которое будет транислитерировать
                $sourceAttributeValue = $model->getAttribute($this->sourceAttribute);

                // Транслитерируем
                $fillAttributeValue = $this->transliterate($sourceAttributeValue);

                $fillAttributeValue = $this->findUnique($fillAttributeValue);
            }

            return $fillAttributeValue;
        }

        return parent::getValue($event);
    }

    /**
     * search first unique value
     *
     * @param $transliteration
     *
     * @return string
     */
    protected function findUnique($transliteration)
    {
        /**
         * @var ActiveRecord $model
         */

        // Clone owner model
        $model = clone $this->owner;

        // cycle of search uniqu velue
        $i = 0;
        do
        {
            $model->clearErrors();
            $url = $transliteration;

            if ($i)
                $url .= '-'.$i;

            $model->setAttribute($this->fillAttribute, $url);

            $validator = new UniqueValidator();
            $validator->validateAttribute($model, $this->fillAttribute);

            $i++;

            if ($i>100)
            {
                if ($i)
                    $url .= '-'.md5(time());

                break;
            }

        }
        while($model->hasErrors());

        return $url;
    }



    /**
     * transliterate text
     *
     * @param string $text
     * @return string
     */
    protected function transliterate($text)
    {
        $table = [
            // Буквосочетания
            'ий' => 'iy',
            'ый' => 'yi',
            'ье' => 'ye',
            'ьё' => 'yo',

            // Строчные буквы
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ь' => '',
            'ы' => 'y',
            'ъ' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',

            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Ж' => 'Zh',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'Y',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Sch',
            'Ь' => '',
            'Ы' => 'Y',
            'Ъ' => '',
            'Э' => 'E',
            'Ю' => 'Yu',
            'Я' => 'Ya',
            'q' => 'Q',
            ' ' => '-',
        ];
        $text = preg_replace(
            ['/[^a-zA-Z0-9\-]/u', '/[-]{2,}/u'],
            ['-', '-'],
            str_replace(array_keys($table), array_values($table), mb_strtolower($text))
        );
        return trim($text, '-');
    }
}