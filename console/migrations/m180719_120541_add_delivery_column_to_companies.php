<?php

use yii\db\Migration;

/**
 * Class m180719_120541_add_delivery_column_to_companies
 */
class m180719_120541_add_delivery_column_to_companies extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%company_deliveries}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'delivery_id' => $this->integer()->notNull(),
            'terms' => $this->text()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-company_deliveries-company_id', '{{%company_deliveries}}', 'company_id');
        $this->addForeignKey('fk-company_deliveries-company_id', '{{%company_deliveries}}', 'company_id', '{{%companies}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-company_deliveries-delivery_id', '{{%company_deliveries}}', 'delivery_id');
        $this->addForeignKey('fk-company_deliveries-delivery_id', '{{%company_deliveries}}', 'delivery_id', '{{%trade_deliveries}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%company_deliveries_regions}}', [
            'company_deliveries_id' => $this->integer()->notNull(),
            'geo_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-company_deliveries_regions', '{{%company_deliveries_regions}}', ['company_deliveries_id', 'geo_id']);
        $this->addForeignKey('fk-company_deliveries_regions-company_deliveries_id', '{{%company_deliveries_regions}}', 'company_deliveries_id', '{{%company_deliveries}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-company_deliveries_regions-geo_id', '{{%company_deliveries_regions}}', 'geo_id', '{{%geo}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%company_deliveries_regions}}');
        $this->dropTable('{{%company_deliveries}}');
    }
}
