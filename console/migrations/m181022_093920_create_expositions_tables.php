<?php

use yii\db\Migration;

/**
 * Class m181022_093920_create_expositions_tables
 */
class m181022_093920_create_expositions_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%exposition_categories}}', [
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

        $this->createIndex('{{%idx-exposition_categories-slug}}', '{{%exposition_categories}}', 'slug', true);
        $this->createIndex('lft', '{{%exposition_categories}}', ['lft', 'rgt']);
        $this->createIndex('rgt', '{{%exposition_categories}}', ['rgt']);

        $this->insert('{{%exposition_categories}}', [
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

        $this->createTable('{{%expositions}}', [
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

        $this->createIndex('idx-expositions-user_id', '{{%expositions}}', 'user_id');
        $this->addForeignKey('fk-expositions-user_id', '{{%expositions}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-expositions-slug', '{{%expositions}}', 'slug', true);
        $this->createIndex('idx-expositions-category_id', '{{%expositions}}', 'category_id');
        $this->addForeignKey('fk-expositions-category_id', '{{%expositions}}', 'category_id', '{{%exposition_categories}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%exposition_photos}}', [
            'id' => $this->primaryKey(),
            'exposition_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull()->unique(),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('idx-exposition_photos-exposition_id', '{{%exposition_photos}}', 'exposition_id');
        $this->addForeignKey('fk-exposition_photos-exposition_id', '{{%exposition_photos}}', 'exposition_id', '{{%expositions}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-expositions-main_photo_id', '{{%expositions}}', 'main_photo_id', '{{%exposition_photos}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%exposition_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%exposition_tags_assignment}}', [
            'exposition_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-exposition_tags_assignment', '{{%exposition_tags_assignment}}', ['exposition_id', 'tag_id']);
        $this->createIndex('idx-exposition_tags_assignment-exposition_id', '{{%exposition_tags_assignment}}', 'exposition_id');
        $this->addForeignKey('fk-exposition_tags_assignment-exposition_id', '{{%exposition_tags_assignment}}', 'exposition_id', '{{%expositions}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-exposition_tags_assignment-tag_id', '{{%exposition_tags_assignment}}', 'tag_id', '{{%exposition_tags}}', 'id', 'CASCADE', 'CASCADE');

        $this->addColumn('{{%expositions}}', 'company_id', $this->integer()->null()->after('user_id'));
        $this->addForeignKey('fk-expositions-company_id', '{{%expositions}}', 'company_id', '{{%companies}}', 'id', 'SET NULL', 'CASCADE');

        $this->addColumn('{{%exposition_categories}}', 'meta_title_other', $this->string()->notNull()->defaultValue('')->after('meta_description_item'));
        $this->addColumn('{{%exposition_categories}}', 'meta_description_other', $this->text()->after('meta_title_other'));
        $this->addColumn('{{%exposition_categories}}', 'meta_keywords_other', $this->text()->after('meta_description_other'));
        $this->addColumn('{{%exposition_categories}}', 'title_other', $this->string()->notNull()->defaultValue('')->after('meta_keywords_other'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-expositions-company_id', '{{%expositions}}');
        $this->dropColumn('{{%expositions}}', 'company_id');
        $this->dropTable('{{%exposition_tags_assignment}}');
        $this->dropTable('{{%exposition_tags}}');
        $this->dropForeignKey('fk-expositions-main_photo_id', '{{%expositions}}');
        $this->dropTable('{{%exposition_photos}}');
        $this->dropTable('{{%expositions}}');
        $this->dropTable('{{%exposition_categories}}');
        $this->dropColumn('{{%exposition_categories}}', 'meta_title_other');
        $this->dropColumn('{{%exposition_categories}}', 'meta_description_other');
        $this->dropColumn('{{%exposition_categories}}', 'meta_keywords_other');
        $this->dropColumn('{{%exposition_categories}}', 'title_other');
    }
}
