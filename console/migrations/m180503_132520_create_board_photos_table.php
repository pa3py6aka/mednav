<?php

use yii\db\Migration;

/**
 * Handles the creation of table `board_photos`.
 */
class m180503_132520_create_board_photos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%board_photos}}', [
            'id' => $this->primaryKey(),
            'board_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull()->unique(),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);
        $this->createIndex('idx-board_photos-board_id', '{{%board_photos}}', 'board_id');
        $this->addForeignKey('fk-board_photos-board_id', '{{%board_photos}}', 'board_id', '{{%boards}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%board_photos}}');
    }
}
