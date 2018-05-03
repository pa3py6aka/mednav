<?php

use yii\db\Migration;

/**
 * Handles the creation of table `boards`.
 */
class m180502_101228_create_boards_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%boards}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'keywords' => $this->text(),
            'note' => $this->string(100)->notNull()->defaultValue(''),
            'price' => $this->integer()->notNull()->defaultValue(0),
            'currency' => $this->smallInteger()->notNull(),
            'price_from' => $this->boolean()->notNull()->defaultValue(false),
            'full_text' => $this->text(),
            'term_id' => $this->smallInteger()->notNull(),
            'geo_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'active_until' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-boards-slug', '{{%boards}}', 'slug', true);
        $this->addForeignKey('fk-boards-author_id', '{{%boards}}', 'author_id', '{{%users}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-boards-category_id', '{{%boards}}', 'category_id', '{{%board_categories}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-boards-geo_id', '{{%boards}}', 'geo_id', '{{%geo}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%boards}}');
    }
}
