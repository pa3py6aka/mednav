<?php

use yii\db\Migration;

/**
 * Class m180716_150921_add_user_id_column_to_trade_user_categoies
 */
class m180716_150921_add_user_id_column_to_trade_user_categoies extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%trade_user_categories}}', 'user_id', $this->integer()->notNull()->after('id'));
        $this->createIndex('idx-trade_user_categories-user_id', '{{%trade_user_categories}}', 'user_id');
        $this->addForeignKey('fk-trade_user_categories-user_id', '{{%trade_user_categories}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-trade_user_categories-user_id', '{{%trade_user_categories}}');
        $this->dropIndex('idx-trade_user_categories-user_id', '{{%trade_user_categories}}');
        $this->dropColumn('{{%trade_user_categories}}', 'user_id');
    }
}
