<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_orders`.
 */
class m181027_144944_create_user_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('{{%orders}}');

        $this->createTable('{{%user_orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'user_name' => $this->string()->notNull()->defaultValue(''),
            'user_phone' => $this->string()->notNull()->defaultValue(''),
            'user_email' => $this->string()->notNull()->defaultValue(''),
            'address' => $this->text()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->createIndex('idx-user_orders-user_id', '{{%user_orders}}', 'user_id');
        $this->addForeignKey('fk-user_orders-user_id', '{{%user_orders}}', 'user_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');

        $this->addColumn('{{%orders}}', 'user_order_id', $this->integer()->notNull()->after('id'));
        $this->createIndex('idx-orders-user_order_id', '{{%orders}}', 'user_order_id');
        $this->addForeignKey('fk-orders-user_order_id', '{{%orders}}', 'user_order_id', '{{%user_orders}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropColumn('{{%orders}}', 'user_name');
        $this->dropColumn('{{%orders}}', 'user_phone');
        $this->dropColumn('{{%orders}}', 'user_email');
        $this->dropColumn('{{%orders}}', 'address');

        $this->execute("ALTER TABLE {{%user_orders}} AUTO_INCREMENT=834;");
        $this->execute("ALTER TABLE {{%orders}} AUTO_INCREMENT=1793;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%orders}}', 'user_name', $this->string()->notNull()->defaultValue('')->after('comment'));
        $this->addColumn('{{%orders}}', 'user_phone', $this->string()->notNull()->defaultValue('')->after('user_name'));
        $this->addColumn('{{%orders}}', 'user_email', $this->string()->notNull()->defaultValue('')->after('user_phone'));
        $this->addColumn('{{%orders}}', 'address', $this->text()->notNull()->after('user_email'));

        $this->dropForeignKey('fk-orders-user_order_id', '{{%orders}}');
        $this->dropIndex('idx-orders-user_order_id', '{{%orders}}');
        $this->dropColumn('{{%orders}}', 'user_order_id');

        $this->dropTable('{{%user_orders}}');
    }
}
