<?php

namespace svsoft\yii\modules\catalog\migrations;

use yii\db\Migration;

class m170815_200949_add_column_catalog_category extends Migration
{
    public function up()
    {
        $this->addColumn('catalog_category', 'slug_chain', $this->string()->notNull());

        $data = $this->db->createCommand('SELECT * FROM catalog_category')->queryAll();
        $categories = [];
        foreach($data as $category)
        {
            $categories[$category['category_id']] = $category;
        }

        foreach($categories as $category)
        {

            $category_id = $category['category_id'];

            $chain = [];
            $categoryVar = $category;

            while($categoryVar['parent_id'])
            {
                $parent_id = $categoryVar['parent_id'];
                $categoryVar = $categories[$parent_id];
                $chain[$parent_id] = $categoryVar['slug'];
            }

            array_reverse($chain);
            array_push($chain, $category['slug']);

            $this->update('catalog_category',['slug_chain'=>implode('/', $chain)], ['category_id'=>$category_id]);
        }
    }

    public function down()
    {
        $this->dropColumn('catalog_category', 'slug_chain');
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
