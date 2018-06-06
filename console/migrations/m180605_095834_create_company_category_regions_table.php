<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company_category_regions`.
 */
class m180605_095834_create_company_category_regions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%company_category_regions}}', [
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

        $this->addPrimaryKey('pk-company_category_regions', '{{%company_category_regions}}', ['category_id', 'geo_id']);
        $this->createIndex('idx-company_category_regions-category_id', '{{%company_category_regions}}', 'category_id');
        $this->addForeignKey('fk-company_category_regions-category_id', '{{%company_category_regions}}', 'category_id', '{{%company_categories}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-company_category_regions-geo_id', '{{%company_category_regions}}', 'geo_id', '{{%geo}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%company_category_regions}}');
    }
}
