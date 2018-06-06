<?php

use yii\db\Migration;

/**
 * Class m180605_133324_set_nested_sets_to_one_root_type
 */
class m180605_133324_set_nested_sets_to_one_root_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* @var $old \core\entities\Board\BoardCategory[] */
        $old = \core\entities\Board\BoardCategory::find()->roots()->all();

        $category = new \core\entities\Board\BoardCategory([
            'name' => '',
            'enabled' => 1,
            'slug' => 'root',
            'meta_description' => '',
            'meta_keywords' => '',
            'description_top' => '',
            'description_bottom' => '',
            'meta_description_item' => '',
            'active' => 1,
        ]);
        $category->makeRoot();

        foreach ($old as $cat) {
            $cat->appendTo($category);
        }

        $category->save();
        foreach ($old as $cat) {
            $cat->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Revert is not supported now." . PHP_EOL;
        return false;
    }
}
