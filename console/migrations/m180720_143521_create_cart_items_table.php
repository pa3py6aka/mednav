<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m180720_143521_create_cart_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%cart_items}}', [
            'user_id' => $this->integer()->notNull(),
            'trade_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->unsigned()->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->addPrimaryKey('pk-cart_items', '{{%cart_items}}', ['user_id', 'trade_id']);
        $this->createIndex('idx-cart_items-user_id', '{{%cart_items}}', 'user_id');
        $this->createIndex('idx-cart_items-trade_id', '{{%cart_items}}', 'trade_id');
        $this->addForeignKey('fk-cart_items-user_id', '{{%cart_items}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-cart_items-trade_id', '{{%cart_items}}', 'trade_id', '{{%trades}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cart_items}}');
    }
}
