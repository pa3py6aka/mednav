<?php

use yii\db\Migration;

/**
 * Handles the creation of table `board_tags`.
 */
class m180503_120504_create_board_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%board_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%board_tags_assignment}}', [
            'board_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-board_tags_assignment', '{{%board_tags_assignment}}', ['board_id', 'tag_id']);
        $this->createIndex('idx-board_tags_assignment-board_id', '{{%board_tags_assignment}}', 'board_id');
        $this->addForeignKey('fk-board_tags_assignment-board_id', '{{%board_tags_assignment}}', 'board_id', '{{%boards}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-board_tags_assignment-tag_id', '{{%board_tags_assignment}}', 'tag_id', '{{%board_tags}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%board_tags_assignment}}');
        $this->dropTable('{{%board_tags}}');
    }
}
