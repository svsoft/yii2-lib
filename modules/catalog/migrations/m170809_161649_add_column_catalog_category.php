<?php

namespace svsoft\yii\modules\catalog\migrations;

use yii\db\Migration;

class m170809_161649_add_column_catalog_category extends Migration
{
    public function up()
    {
        $this->addColumn('catalog_category', 'sort', $this->integer());

        $this->update('catalog_category', ['sort'=>100]);
    }

    public function down()
    {
        $this->dropColumn('catalog_category', 'sort');
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
