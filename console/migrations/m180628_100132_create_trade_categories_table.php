<?php

use yii\db\Migration;

/**
 * Handles the creation of table `trade_categories`.
 */
class m180628_100132_create_trade_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%trade_categories}}', [
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

        $this->createIndex('{{%idx-trade_categories-slug}}', '{{%trade_categories}}', 'slug', true);
        $this->createIndex('lft', '{{%trade_categories}}', ['lft', 'rgt']);
        $this->createIndex('rgt', '{{%trade_categories}}', ['rgt']);

        $this->insert('{{%trade_categories}}', [
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

        $this->createTable('{{%trade_category_regions}}', [
            'category_id' => $this->integer()->notNull(),
            'geo_id' => $this->integer()->notNull(),
            'meta_title' => $this->string()->notNull()->defaultValue(''),
            'meta_description' => $this->text()->notNull(),
            'meta_keywords' => $this->text()->notNull(),
            'title' => $this->string()->notNull()->defaultValue(''),
            'description_top' => $this->text()->notNull(),
            'description_top_on' => $this->boolean()->notNull()->defaultValue(true),
            'description_bottom' => $this->text()->notNull(),
            'description_bottom_on' => $this->boolean()->notNull()->defaultValue(true),
        ], $tableOptions);

        $this->addPrimaryKey('pk-trade_category_regions', '{{%trade_category_regions}}', ['category_id', 'geo_id']);
        $this->createIndex('idx-trade_category_regions-category_id', '{{%trade_category_regions}}', 'category_id');
        $this->addForeignKey('fk-trade_category_regions-category_id', '{{%trade_category_regions}}', 'category_id', '{{%trade_categories}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-trade_category_regions-geo_id', '{{%trade_category_regions}}', 'geo_id', '{{%geo}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%trade_category_regions}}');
        $this->dropTable('{{%trade_categories}}');
    }
}
