<?php

use yii\db\Migration;

/**
 * Handles the creation of table `board_category_regions`.
 */
class m180501_064517_create_board_category_regions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%board_category_regions}}', [
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

        $this->addPrimaryKey('pk-board_category_regions', '{{%board_category_regions}}', ['category_id', 'geo_id']);
        $this->createIndex('idx-board_category_regions-category_id', '{{%board_category_regions}}', 'category_id');
        $this->addForeignKey('fk-board_category_regions-category_id', '{{%board_category_regions}}', 'category_id', '{{%board_categories}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-board_category_regions-geo_id', '{{%board_category_regions}}', 'geo_id', '{{%geo}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%board_category_regions}}');
    }
}
