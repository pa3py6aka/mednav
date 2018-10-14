<?php

use yii\db\Migration;

/**
 * Class m181014_092956_create_brands_tables
 */
class m181014_092956_create_brands_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%brand_categories}}', [
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

        $this->createIndex('{{%idx-brand_categories-slug}}', '{{%brand_categories}}', 'slug', true);
        $this->createIndex('lft', '{{%brand_categories}}', ['lft', 'rgt']);
        $this->createIndex('rgt', '{{%brand_categories}}', ['rgt']);

        $this->insert('{{%brand_categories}}', [
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

        $this->createTable('{{%brands}}', [
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

        $this->createIndex('idx-brands-user_id', '{{%brands}}', 'user_id');
        $this->addForeignKey('fk-brands-user_id', '{{%brands}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-brands-slug', '{{%brands}}', 'slug', true);
        $this->createIndex('idx-brands-category_id', '{{%brands}}', 'category_id');
        $this->addForeignKey('fk-brands-category_id', '{{%brands}}', 'category_id', '{{%brand_categories}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%brand_photos}}', [
            'id' => $this->primaryKey(),
            'brand_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull()->unique(),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('idx-brand_photos-brand_id', '{{%brand_photos}}', 'brand_id');
        $this->addForeignKey('fk-brand_photos-brand_id', '{{%brand_photos}}', 'brand_id', '{{%brands}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-brands-main_photo_id', '{{%brands}}', 'main_photo_id', '{{%brand_photos}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%brand_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%brand_tags_assignment}}', [
            'brand_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-brand_tags_assignment', '{{%brand_tags_assignment}}', ['brand_id', 'tag_id']);
        $this->createIndex('idx-brand_tags_assignment-brand_id', '{{%brand_tags_assignment}}', 'brand_id');
        $this->addForeignKey('fk-brand_tags_assignment-brand_id', '{{%brand_tags_assignment}}', 'brand_id', '{{%brands}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-brand_tags_assignment-tag_id', '{{%brand_tags_assignment}}', 'tag_id', '{{%brand_tags}}', 'id', 'CASCADE', 'CASCADE');

        $this->addColumn('{{%brands}}', 'company_id', $this->integer()->null()->after('user_id'));
        $this->addForeignKey('fk-brands-company_id', '{{%brands}}', 'company_id', '{{%companies}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-brands-company_id', '{{%brands}}');
        $this->dropColumn('{{%brands}}', 'company_id');
        $this->dropTable('{{%brand_tags_assignment}}');
        $this->dropTable('{{%brand_tags}}');
        $this->dropForeignKey('fk-brands-main_photo_id', '{{%brands}}');
        $this->dropTable('{{%brand_photos}}');
        $this->dropTable('{{%brands}}');
        $this->dropTable('{{%brand_categories}}');
    }
}
