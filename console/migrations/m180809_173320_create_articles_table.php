<?php

use yii\db\Migration;

/**
 * Handles the creation of table `articles`.
 */
class m180809_173320_create_articles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%articles}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull()->defaultValue(''),
            'meta_description' => $this->text()->notNull(),
            'meta_keywords'=> $this->string()->notNull()->defaultValue(''),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'intro' => $this->text()->notNull(),
            'full_text' => $this->text()->notNull(),
            'indirect_links' => $this->boolean()->notNull()->defaultValue(true),
            'main_photo_id' => $this->integer(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'views' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-articles-user_id', '{{%articles}}', 'user_id');
        $this->addForeignKey('fk-articles-user_id', '{{%articles}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-articles-slug', '{{%articles}}', 'slug', true);
        $this->createIndex('idx-articles-category_id', '{{%articles}}', 'category_id');
        $this->addForeignKey('fk-articles-category_id', '{{%articles}}', 'category_id', '{{%article_categories}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%article_photos}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull()->unique(),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('idx-article_photos-article_id', '{{%article_photos}}', 'article_id');
        $this->addForeignKey('fk-article_photos-article_id', '{{%article_photos}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-articles-main_photo_id', '{{%articles}}', 'main_photo_id', '{{%article_photos}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%article_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%article_tags_assignment}}', [
            'article_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-article_tags_assignment', '{{%article_tags_assignment}}', ['article_id', 'tag_id']);
        $this->createIndex('idx-article_tags_assignment-article_id', '{{%article_tags_assignment}}', 'article_id');
        $this->addForeignKey('fk-article_tags_assignment-article_id', '{{%article_tags_assignment}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-article_tags_assignment-tag_id', '{{%article_tags_assignment}}', 'tag_id', '{{%article_tags}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_tags_assignment}}');
        $this->dropTable('{{%article_tags}}');
        $this->dropForeignKey('fk-articles-main_photo_id', '{{%articles}}');
        $this->dropTable('{{%article_photos}}');
        $this->dropTable('{{%articles}}');
    }
}
