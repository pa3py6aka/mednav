<?php

use yii\db\Migration;

/**
 * Class m180606_081003_set_nested_sets_to_one_root_type
 */
class m180606_081003_set_nested_sets_to_one_root_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* @var $old \core\entities\Company\CompanyCategory[] */
        $old = \core\entities\Company\CompanyCategory::find()->roots()->all();

        $category = new \core\entities\Company\CompanyCategory([
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
        $category->makeRoot()->save();

        foreach ($old as $cat) {
            $cat->appendTo($category)->save();
        }

        $this->dropColumn('{{%company_categories}}', 'tree');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180606_081003_set_nested_sets_to_one_root_type cannot be reverted.\n";
        return false;
    }
}
