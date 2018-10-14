<?php

use yii\db\Migration;

/**
 * Class m181014_114812_create_cnews_tables
 */
class m181014_114812_create_cnews_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%cnews_categories}}', [
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

        $this->createIndex('{{%idx-cnews_categories-slug}}', '{{%cnews_categories}}', 'slug', true);
        $this->createIndex('lft', '{{%cnews_categories}}', ['lft', 'rgt']);
        $this->createIndex('rgt', '{{%cnews_categories}}', ['rgt']);

        $this->insert('{{%cnews_categories}}', [
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

        $this->createTable('{{%cnews}}', [
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

        $this->createIndex('idx-cnews-user_id', '{{%cnews}}', 'user_id');
        $this->addForeignKey('fk-cnews-user_id', '{{%cnews}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-cnews-slug', '{{%cnews}}', 'slug', true);
        $this->createIndex('idx-cnews-category_id', '{{%cnews}}', 'category_id');
        $this->addForeignKey('fk-cnews-category_id', '{{%cnews}}', 'category_id', '{{%cnews_categories}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%cnews_photos}}', [
            'id' => $this->primaryKey(),
            'cnews_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull()->unique(),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('idx-cnews_photos-cnews_id', '{{%cnews_photos}}', 'cnews_id');
        $this->addForeignKey('fk-cnews_photos-cnews_id', '{{%cnews_photos}}', 'cnews_id', '{{%cnews}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-cnews-main_photo_id', '{{%cnews}}', 'main_photo_id', '{{%cnews_photos}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%cnews_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%cnews_tags_assignment}}', [
            'cnews_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-cnews_tags_assignment', '{{%cnews_tags_assignment}}', ['cnews_id', 'tag_id']);
        $this->createIndex('idx-cnews_tags_assignment-cnews_id', '{{%cnews_tags_assignment}}', 'cnews_id');
        $this->addForeignKey('fk-cnews_tags_assignment-cnews_id', '{{%cnews_tags_assignment}}', 'cnews_id', '{{%cnews}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-cnews_tags_assignment-tag_id', '{{%cnews_tags_assignment}}', 'tag_id', '{{%cnews_tags}}', 'id', 'CASCADE', 'CASCADE');

        $this->addColumn('{{%cnews}}', 'company_id', $this->integer()->null()->after('user_id'));
        $this->addForeignKey('fk-cnews-company_id', '{{%cnews}}', 'company_id', '{{%companies}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cnews-company_id', '{{%cnews}}');
        $this->dropColumn('{{%cnews}}', 'company_id');
        $this->dropTable('{{%cnews_tags_assignment}}');
        $this->dropTable('{{%cnews_tags}}');
        $this->dropForeignKey('fk-cnews-main_photo_id', '{{%cnews}}');
        $this->dropTable('{{%cnews_photos}}');
        $this->dropTable('{{%cnews}}');
        $this->dropTable('{{%cnews_categories}}');
    }
}
