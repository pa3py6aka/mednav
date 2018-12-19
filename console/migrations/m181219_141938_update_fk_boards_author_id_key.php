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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-boards-author_id', '{{%boards}}');
        $this->addForeignKey('fk-boards-author_id', '{{%boards}}', 'author_id', '{{%users}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
