<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brands`.
 */
class m181013_140955_create_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%news_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'context_name' => $this->string()->notNull()->defaultValue(''),
            'enabled' => $this->boolean()->notNull()->defaultValue(true),
            'not_show_on_main' => $this->boolean()->notNull()->defaultValue(false),
            'children_only_parent' => $this->boolean()->notNull()->defaultValue(false),
            'slug' => $this->string()->notNull(),
            'meta_title' => $this->string()->notNull()->defaultValue(''),
            'meta_description' => $this->text()->notNull(),
            'meta_keywords' => $this->text()->notNull(),
            'title' => $this->string()->notNull()->defaultValue(''),
            'description_top' => $this->text()->notNull(),
            'description_top_on' => $this->boolean()->notNull()->defaultValue(true),
            'description_bottom' => $this->text()->notNull(),
            'description_bottom_on' => $this->boolean()->notNull()->defaultValue(true),
            'meta_title_item' => $this->string()->notNull()->defaultValue(''),
            'meta_description_item' => $this->text()->notNull(),
            'pagination' => $this->tinyInteger()->notNull()->defaultValue(1),
            'active' => $this->boolean()->notNull()->defaultValue(true),

            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-news_categories-slug}}', '{{%news_categories}}', 'slug', true);
        $this->createIndex('lft', '{{%news_categories}}', ['lft', 'rgt']);
        $this->createIndex('rgt', '{{%news_categories}}', ['rgt']);

        $this->insert('{{%news_categories}}', [
            'id' => 1,
            'name' => '',
            'slug' => 'root',
            'meta_description' => '',
            'meta_keywords' => '',
            'description_top' => '',
            'description_bottom' => '',
            'meta_description_item' => '',
            'active' => 1,
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
        ]);

        $this->createTable('{{%news}}', [
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

        $this->createIndex('idx-news-user_id', '{{%news}}', 'user_id');
        $this->addForeignKey('fk-news-user_id', '{{%news}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-news-slug', '{{%news}}', 'slug', true);
        $this->createIndex('idx-news-category_id', '{{%news}}', 'category_id');
        $this->addForeignKey('fk-news-category_id', '{{%news}}', 'category_id', '{{%news_categories}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%news_photos}}', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull()->unique(),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('idx-news_photos-news_id', '{{%news_photos}}', 'news_id');
        $this->addForeignKey('fk-news_photos-news_id', '{{%news_photos}}', 'news_id', '{{%news}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-news-main_photo_id', '{{%news}}', 'main_photo_id', '{{%news_photos}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%news_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%news_tags_assignment}}', [
            'news_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-news_tags_assignment', '{{%news_tags_assignment}}', ['news_id', 'tag_id']);
        $this->createIndex('idx-news_tags_assignment-news_id', '{{%news_tags_assignment}}', 'news_id');
        $this->addForeignKey('fk-news_tags_assignment-news_id', '{{%news_tags_assignment}}', 'news_id', '{{%news}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-news_tags_assignment-tag_id', '{{%news_tags_assignment}}', 'tag_id', '{{%news_tags}}', 'id', 'CASCADE', 'CASCADE');

        $this->addColumn('{{%news}}', 'company_id', $this->integer()->null()->after('user_id'));
        $this->addForeignKey('fk-news-company_id', '{{%news}}', 'company_id', '{{%companies}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-news-company_id', '{{%news}}');
        $this->dropColumn('{{%news}}', 'company_id');
        $this->dropTable('{{%news_tags_assignment}}');
        $this->dropTable('{{%news_tags}}');
        $this->dropForeignKey('fk-news-main_photo_id', '{{%news}}');
        $this->dropTable('{{%news_photos}}');
        $this->dropTable('{{%news}}');
        $this->dropTable('{{%news_categories}}');
    }
}
