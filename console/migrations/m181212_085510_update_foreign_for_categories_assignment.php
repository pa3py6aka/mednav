<?php

use yii\db\Migration;

/**
 * Class m181212_085510_update_foreign_key_fk_boards_category_id
 */
class m181212_085510_update_foreign_for_categories_assignment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-boards-category_id', '{{%boards}}');
        $this->addForeignKey('fk-boards-category_id', '{{%boards}}', 'category_id', '{{%board_categories}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('fk-trades-category_id', '{{%trades}}');
        $this->addForeignKey('fk-trades-category_id', '{{%trades}}', 'category_id', '{{%trade_categories}}', 'id', 'CASCADE', 'CASCADE');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-boards-category_id', '{{%boards}}');
        $this->addForeignKey('fk-boards-category_id', '{{%boards}}', 'category_id', '{{%board_categories}}', 'id', 'RESTRICT', 'CASCADE');

        $this->dropForeignKey('fk-trades-category_id', '{{%trades}}');
        $this->addForeignKey('fk-trades-category_id', '{{%trades}}', 'category_id', '{{%trade_categories}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
