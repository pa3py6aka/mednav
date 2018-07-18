<?php

use yii\db\Migration;

/**
 * Handles the creation of table `trade_tags`.
 */
class m180712_181911_create_trade_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%trade_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%trade_tags_assignment}}', [
            'trade_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-trade_tags_assignment', '{{%trade_tags_assignment}}', ['trade_id', 'tag_id']);
        $this->createIndex('idx-trade_tags_assignment-trade_id', '{{%trade_tags_assignment}}', 'trade_id');
        $this->addForeignKey('fk-trade_tags_assignment-trade_id', '{{%trade_tags_assignment}}', 'trade_id', '{{%trades}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-trade_tags_assignment-tag_id', '{{%trade_tags_assignment}}', 'tag_id', '{{%trade_tags}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%trade_tags_assignment}}');
        $this->dropTable('{{%trade_tags}}');
    }
}
