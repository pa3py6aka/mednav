<?php

use yii\db\Migration;

/**
 * Class m180604_052918_add_company_categories_assignment
 */
class m180604_052918_add_company_categories_assignment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%company_categories_assignment}}', [
            'company_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-company_categories_assignment', '{{%company_categories_assignment}}', ['company_id', 'category_id']);
        $this->createIndex('idx-company_categories_assignment-company_id', '{{%company_categories_assignment}}', 'company_id');
        $this->createIndex('idx-company_categories_assignment-category_id', '{{%company_categories_assignment}}', 'category_id');
        $this->addForeignKey('fk-company_categories_assignment-company_id', '{{%company_categories_assignment}}', 'company_id', '{{%companies}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-company_categories_assignment-category_id', '{{%company_categories_assignment}}', 'category_id', '{{%company_categories}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%company_categories_assignment}}');
    }
}
