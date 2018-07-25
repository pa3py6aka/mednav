<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages`.
 */
class m180724_103912_create_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%dialogs}}', [
            'id' => $this->primaryKey(),
            'user_from' => $this->integer()->null(),
            'user_to' => $this->integer()->notNull(),
            'subject' => $this->string()->notNull()->defaultValue(''),
            'name' => $this->string()->notNull()->defaultValue(''),
            'phone' => $this->string()->notNull()->defaultValue(''),
            'email' => $this->string()->notNull()->defaultValue(''),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
        ], $tableOptions);
        $this->createIndex('idx-dialogs-users', '{{%dialogs}}', ['user_from', 'user_to', 'subject']);
        $this->addForeignKey('fk-dialogs-user_from', '{{%dialogs}}', 'user_from', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-dialogs-user_to', '{{%dialogs}}', 'user_to', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%messages}}', [
            'id' => $this->primaryKey(),
            'dialog_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->null(),
            'text' => $this->text()->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(0), // 0 - непрочитанное, 1 - прочитанное

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-messages-dialog_id', '{{%messages}}', 'dialog_id');
        $this->addForeignKey('fk-messages-dialog_id', '{{%messages}}', 'dialog_id', '{{%dialogs}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-messages-user_id', '{{%messages}}', 'user_id');
        $this->addForeignKey('fk-messages-user_id', '{{%messages}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%messages}}');
        $this->dropTable('{{%dialogs}}');
    }
}
