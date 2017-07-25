<?php
namespace svsoft\yii\modules\content\migrations;

use yii\db\Migration;

class m170723_195647_ctreate_tables extends Migration
{
    public function safeUp()
    {
        // Таблица документов
        $this->createTable('content_document', [
            'document_id' => $this->primaryKey()->unsigned(),
            'parent_id'=> $this->integer()->unsigned(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'slug_chain' => $this->string()->notNull(),
            'children' => $this->boolean()->unsigned(),
            'content' => $this->text(),
            'preview' => $this->text(),
            'active' => $this->boolean()->unsigned(),
            'created' => $this->integer()->notNull(),
            'updated' => $this->integer()->notNull(),
            'sort' => $this->integer()->unsigned(),
            'images' => $this->text(),
            'title' => $this->string()->notNull(),
            'h1' => $this->string(),
            'description' => $this->text(),
        ]);

        $this->addForeignKey('cd_parent_id', 'content_document', 'parent_id', 'content_document', 'document_id', 'RESTRICT');

        $this->createTable('content_block', [
            'block_id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'key' => $this->string()->notNull(),
            'content' => $this->text(),
            'format' => $this->smallInteger()->unsigned(),
            'created' => $this->integer()->notNull(),
            'updated' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('content_document');
        $this->dropTable('content_block');

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
