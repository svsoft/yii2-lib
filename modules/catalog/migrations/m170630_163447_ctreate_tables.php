<?php
namespace svsoft\yii\modules\catalog\migrations;

use yii\db\Migration;

class m170630_163447_ctreate_tables extends Migration
{
    public function safeUp()
    {
        // Таблица категорий
        $this->createTable('catalog_category', [
            'category_id' => $this->primaryKey()->unsigned(),
            'parent_id'=> $this->integer()->unsigned(),
            'name'=> $this->string()->notNull(),
            'slug'=> $this->string(),
            'images'=> $this->text(),
            'active'=> $this->boolean()->unsigned(),
            'created'=> $this->integer()->notNull(),
            'updated'=> $this->integer()->notNull(),
        ]);

        $this->addForeignKey('cc_parent_id', 'catalog_category', 'parent_id', 'catalog_category', 'category_id', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('catalog_category');

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
