<?php

namespace svsoft\yii\modules\catalog\migrations;

use yii\db\Migration;

class m170816_140331_add_column_catalog_category extends Migration
{
    public function up()
    {
        $this->addColumn('catalog_category', 'slug_lock', $this->boolean());
    }

    public function down()
    {
        $this->dropColumn('catalog_category', 'slug_lock');
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
