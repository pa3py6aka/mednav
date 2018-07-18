<?php

use yii\db\Migration;

/**
 * Class m180716_082117_fix_user_categories_assignment
 */
class m180716_082117_fix_user_categories_assignment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%trade_user_category_assignments}}');

        $this->addColumn('{{%trades}}', 'category_id', $this->integer()->notNull()->after('user_id'));
        $this->createIndex('idx-trades-category_id', '{{%trades}}', 'category_id');
        $this->addForeignKey('fk-trades-category_id', '{{%trades}}', 'category_id', '{{%trade_categories}}', 'id', 'RESTRICT', 'CASCADE');

        $this->addColumn('{{%trades}}', 'user_category_id', $this->integer()->notNull()->after('category_id'));
        $this->createIndex('idx-trades-user_category_id', '{{%trades}}', 'user_category_id');
        $this->addForeignKey('fk-trades-user_category_id', '{{%trades}}', 'user_category_id', '{{%trade_user_categories}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180716_082117_fix_user_categories_assignment cannot be reverted.\n";
        return false;
    }
}
