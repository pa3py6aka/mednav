<?php

use yii\db\Migration;

/**
 * Class m181219_141938_update_fk_boards_author_id_key
 */
class m181219_141938_update_fk_boards_author_id_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-boards-author_id', '{{%boards}}');
        $this->addForeignKey('fk-boards-author_id', '{{%boards}}', 'author_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('fk-trades-user_id', '{{%trades}}');
        $this->addForeignKey('fk-trades-user_id', '{{%trades}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('fk-order_items-trade_id', '{{%order_items}}');
        $this->addForeignKey('fk-order_items-trade_id', '{{%order_items}}', 'trade_id', '{{%trades}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-boards-author_id', '{{%boards}}');
        $this->addForeignKey('fk-boards-author_id', '{{%boards}}', 'author_id', '{{%users}}', 'id', 'RESTRICT', 'CASCADE');

        $this->dropForeignKey('fk-trades-user_id', '{{%trades}}');
        $this->addForeignKey('fk-trades-user_id', '{{%trades}}', 'user_id', '{{%users}}', 'id', 'RESTRICT', 'CASCADE');

        $this->dropForeignKey('fk-order_items-trade_id', '{{%order_items}}');
        $this->addForeignKey('fk-order_items-trade_id', '{{%order_items}}', 'trade_id', '{{%trades}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
