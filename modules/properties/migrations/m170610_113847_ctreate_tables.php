<?php
namespace svsoft\yii\modules\properties\migrations;

use yii\db\Migration;

class m170610_113847_ctreate_tables extends Migration
{
    public function safeUp()
    {
        // Таблицf типов моделей к которым можно привязать свойста
        $this->createTable('property_model_type', [
            'model_type_id' => $this->primaryKey(),
            'name'=> $this->string(255),
            'slug'=> $this->string(255),
            'class'=> $this->string(255),
        ]);

        // Таблицп групп свойств
        $this->createTable('property_group', [
            'group_id' => $this->primaryKey(),
            'name'=> $this->string(255),
            'slug'=> $this->string(255),
            'model_type_id' => $this->integer(),
        ]);
        $this->addForeignKey('pg_model_type_id', 'property_group', 'model_type_id', 'property_model_type', 'model_type_id', 'RESTRICT');

        // Таблицп объектов, в ней хранятся ссылки на модели и их типы
        $this->createTable('property_object', [
            'object_id' => $this->primaryKey(),
            'model_id'=> $this->integer(),
            'model_type_id' => $this->integer(),
        ]);
        $this->addForeignKey( 'po_model_type_id', 'property_object', 'model_type_id', 'property_model_type', 'model_type_id', 'RESTRICT' );

        // Таблица свойств
        $this->createTable('property', [
            'property_id' => $this->primaryKey(),
            'name'=> $this->string(255),
            'slug'=> $this->string(255),
            'model_type_id' => $this->integer(),
            'group_id' => $this->integer(),
            'type'=> $this->smallInteger()->unsigned(),
            'multiple'=> $this->boolean(),
            'active'=> $this->boolean(),
        ]);
        $this->addForeignKey('p_model_type_id', 'property', 'model_type_id', 'property_model_type', 'model_type_id', 'RESTRICT');
        $this->addForeignKey('p_group_id', 'property', 'group_id', 'property_group', 'group_id', 'RESTRICT');


        // Таблица значений свойств дя лбъектов
        $this->createTable('property_value', [
            'value_id' => $this->primaryKey(),
            'property_id' => $this->integer(),
            'object_id' => $this->integer(),
            'string_value'=> $this->string(255),
            'text_value'=> $this->text(),
            'int_value'=> $this->integer(),
            'float_value'=> $this->float(),
            'timestamp_value'=> $this->integer()->unsigned(),
        ]);
        $this->addForeignKey('pv_property_id', 'property_value', 'property_id', 'property', 'property_id', 'RESTRICT');
        $this->addForeignKey('pv_object_id', 'property_value', 'object_id', 'property_object', 'object_id', 'RESTRICT');


        // add foreign key for table `property` to `property_group`



        //$this->addForeignKey('a_category_id','article','category_id','category','category_id','RESTRICT');
//
//        // add foreign key for table `property_value` to `property`
//        $this->addForeignKey(
//            'property_value_property_id',
//            'property_value',
//            'property_id',
//            'property',
//            'property_id',
//            'RESTRICT'
//        );
    }

    public function safeDown()
    {
        // echo "m170610_113847_ctreate_tables cannot be reverted.\n";
        $this->dropTable('property_value');
        $this->dropTable('property');
        $this->dropTable('property_object');
        $this->dropTable('property_group');
        $this->dropTable('property_model_type');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170610_113847_ctreate_tables cannot be reverted.\n";

        return false;
    }
    */
}
