<?php
namespace svsoft\yii\modules\properties\migrations;

use yii\db\Migration;

class m170802_215110_add_column extends Migration
{
    public function safeUp()
    {
        // доавляем колонку в таблицу групп свойств обязательная
        $this->addColumn('property_group','require',$this->boolean());

        $this->alterColumn('property','group_id',$this->integer()->notNull());
        $this->alterColumn('property_group','model_type_id',$this->integer()->notNull());
        $this->alterColumn('property_object','model_type_id',$this->integer()->notNull());
        $this->alterColumn('property_value','object_id',$this->integer()->notNull());
        $this->alterColumn('property_value','property_id',$this->integer()->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('property_group','require');

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
