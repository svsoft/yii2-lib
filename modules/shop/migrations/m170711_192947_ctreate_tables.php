<?php
namespace svsoft\yii\modules\shop\migrations;

use yii\db\Migration;

class m170711_192947_ctreate_tables extends Migration
{
    public function safeUp()
    {
        // Таблица категорий
        $this->createTable('shop_order', [
            'order_id' => $this->primaryKey()->unsigned(),
            'user_id'=> $this->integer()->unsigned(),
            'external_id'=> $this->string()->notNull(),
            'created'=> $this->integer()->notNull(),
            'updated'=> $this->integer()->notNull(),
            'status_id' => $this->smallInteger()->unsigned(),
        ]);

        // Таблица товаров
        $this->createTable('shop_cart_item', [
            'item_id' => $this->primaryKey()->unsigned(),
            'product_id' => $this->integer()->unsigned(),
            'order_id'=> $this->integer()->unsigned(),
            'user_id'=> $this->integer()->unsigned(),
            'session_id'=> $this->string()->notNull(),
            'price'=> $this->float(),
            'count'=> $this->float(),
            'created'=> $this->integer()->notNull(),
            'updated'=> $this->integer()->notNull(),
        ]);

        $this->addForeignKey('sci_order_id', 'shop_cart_item', 'order_id', 'shop_order', 'order_id', 'RESTRICT');

    }

    public function safeDown()
    {
        $this->dropTable('shop_cart_item');
        $this->dropTable('shop_order');

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
