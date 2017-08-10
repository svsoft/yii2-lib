<?php
namespace svsoft\yii\modules\properties\migrations;

use yii\db\Migration;

class m170802_225110_reorganization_groups extends Migration
{
    public function safeUp()
    {
        $properties = $this->db->createCommand('SELECT * FROM property WHERE group_id IS NULL')
            ->queryAll();

        $groupBy = [];
        foreach($properties as $property)
        {
            $groupBy[$property['model_type_id']][] = $property['property_id'];
        }
        foreach($groupBy as $model_type_id=>$propertyIdList)
        {
            $slug = 'common-' . md5($model_type_id . time());
            $this->insert('property_group',['model_type_id'=>$model_type_id, 'slug'=>$slug, 'name'=>'Общие']);

            $group = $this->db->createCommand("SELECT * FROM property_group WHERE slug = '{$slug}'  AND model_type_id = {$model_type_id}")
                ->queryOne();

            $this->update('property', ['group_id'=>$group['group_id'], 'require'=>1], ['property_id'=>$propertyIdList]);
        }

        // простовляем обязательность для всех групп
        $objects = $this->db->createCommand('SELECT * FROM property_object')
            ->queryAll();

        foreach($objects as $object)
        {
            $object_id = $object['object_id'];
            $model_type_id = $object['model_type_id'];
            $groups = $this->db->createCommand('SELECT * FROM property_group WHERE model_type_id = ' . $model_type_id)
                ->queryAll();

            foreach($groups as $group)
            {
                $group_id = $group['group_id'];
                $link = $this->db->createCommand("SELECT * FROM property_object_group WHERE group_id = {$group_id}  AND object_id = {$object_id}")
                    ->queryOne();
                if (!$link)
                    $this->insert('property_object_group',['group_id'=>$group_id, 'object_id'=>$object_id]);
            }
        }
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
