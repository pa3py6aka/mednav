<?php

use yii\db\Migration;

/**
 * Handles the creation of table `orders`.
 */
class m180720_124207_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'delivery_id' => $this->integer()->null(),
            'comment' => $this->text()->notNull(),
            'user_name' => $this->string()->notNull()->defaultValue(''),
            'user_phone' => $this->string()->notNull()->defaultValue(''),
            'user_email' => $this->string()->notNull()->defaultValue(''),
            'address' => $this->text()->notNull(),

            'status' => $this->tinyInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-orders-user_id', '{{%orders}}', 'user_id');
        $this->addForeignKey('fk-orders-user_id', '{{%orders}}', 'user_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('idx-orders-delivery_id', '{{%orders}}', 'delivery_id');
        $this->addForeignKey('fk-orders-delivery_id', '{{%orders}}', 'delivery_id', '{{%trade_deliveries}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%order_items}}', [
            'order_id' => $this->integer()->notNull(),
            'trade_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->unsigned()->notNull()->defaultValue(1),
        ], $tableOptions);
        $this->addPrimaryKey('pk-order_items', '{{%order_items}}', ['order_id', 'trade_id']);
        $this->createIndex('idx-order_items-order_id', '{{%order_items}}', 'order_id');
        $this->createIndex('idx-order_items-trade_id', '{{%order_items}}', 'trade_id');
        $this->addForeignKey('fk-order_items-order_id', '{{%order_items}}', 'order_id', '{{%orders}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-order_items-trade_id', '{{%order_items}}', 'trade_id', '{{%trades}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_items}}');
        $this->dropTable('{{%orders}}');
    }
}
