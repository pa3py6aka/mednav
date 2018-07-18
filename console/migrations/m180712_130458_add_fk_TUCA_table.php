<?php

use yii\db\Migration;

/**
 * Class m180712_130458_add_fk_TUCA_table
 */
class m180712_130458_add_fk_TUCA_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-trade_user_category_assignments-category_id', '{{%trade_user_category_assignments}}', 'category_id', '{{%trade_user_categories}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-trade_user_category_assignments-category_id', '{{%trade_user_category_assignments}}');
    }
}
