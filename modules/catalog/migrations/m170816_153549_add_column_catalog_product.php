<?php

namespace svsoft\yii\modules\catalog\migrations;

use yii\db\Migration;

class m170816_153549_add_column_catalog_product extends Migration
{
    public function up()
    {
        $this->addColumn('catalog_product', 'sort', $this->integer());

        $this->update('catalog_product', ['sort'=>100]);
    }

    public function down()
    {
        $this->dropColumn('catalog_product', 'sort');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
