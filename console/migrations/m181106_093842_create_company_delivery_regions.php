<?php

use yii\db\Migration;

/**
 * Class m181106_093842_create_company_delivery_regions
 */
class m181106_093842_create_company_delivery_regions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%company_delivery_regions}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'country_id' => $this->integer()->notNull(),
            'geo_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-company_delivery_regions-company_id', '{{%company_delivery_regions}}', 'company_id');
        $this->addForeignKey('fk-company_delivery_regions-company_id', '{{%company_delivery_regions}}', 'company_id', '{{%companies}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-company_delivery_regions-country_id', '{{%company_delivery_regions}}', 'country_id', '{{%geo}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-company_delivery_regions-geo_id', '{{%company_delivery_regions}}', 'geo_id', '{{%geo}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%company_delivery_regions}}');
    }
}
