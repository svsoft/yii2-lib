<?php
namespace svsoft\yii\modules\properties\migrations;

use yii\db\Migration;

class m170730_132947_ctreate_table_property_object_group extends Migration
{
    public function safeUp()
    {
        // Таблица связи объектов и групп свойств
        $this->createTable('property_object_group', [
            'id' => $this->primaryKey(),
            'object_id'=> $this->integer(),
            'group_id'=> $this->integer(),
        ]);

        // Создаем ключи для таблиц групп и объектов
        $this->addForeignKey('pog_group_id', 'property_object_group', 'group_id', 'property_group', 'group_id', 'RESTRICT');
        $this->addForeignKey('pog_object_id', 'property_object_group', 'object_id', 'property_object', 'object_id', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('property_object_group');

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
