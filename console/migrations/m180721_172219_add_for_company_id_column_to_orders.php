<?php

use yii\db\Migration;

/**
 * Class m180721_172219_add_for_company_id_column_to_orders
 */
class m180721_172219_add_for_company_id_column_to_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%orders}}', 'for_company_id', $this->integer()->null()->after('id'));
        $this->createIndex('idx-orders-for_company_id', '{{%orders}}', 'for_company_id');
        $this->addForeignKey('fk-orders-for_company_id', '{{%orders}}', 'for_company_id', '{{%companies}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-orders-for_company_id', '{{%orders}}');
        $this->dropIndex('idx-orders-for_company_id', '{{%orders}}');
        $this->dropColumn('{{%orders}}', 'for_company_id');
    }
}
