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

use svsoft\yii\behaviors\EncodeAttribute;
use yii\base\Behavior;


class EncodeBehavior extends Behavior
{
    /**
     * flag that make $fillAttribute is unique
     *
     * @var bool
     */
    protected $_encode;

    public function init()
    {
        parent::init();


//
//        var_dump($this->encode);
    }

    public function getEncode()
    {
        if ($this->_encode === null)
            $this->_encode = new EncodeAttribute(['owner'=>$this->owner]);

        return $this->_encode;
    }
}