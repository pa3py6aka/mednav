<?php

use yii\db\Migration;

/**
 * Handles the creation of table `support_dialogs`.
 */
class m181115_124542_create_support_dialogs_table extends Migration
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

        $this->createTable('{{%support_dialogs}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'subject' => $this->string()->notNull()->defaultValue(''),
            'name' => $this->string()->notNull()->defaultValue(''),
            'phone' => $this->string()->notNull()->defaultValue(''),
            'email' => $this->string()->notNull()->defaultValue(''),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
        ], $tableOptions);
        $this->addForeignKey('fk-support_dialogs-user_id', '{{%support_dialogs}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%support_messages}}', [
            'id' => $this->primaryKey(),
            'dialog_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->null(),
            'text' => $this->text()->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(0), // 0 - непрочитанное, 1 - прочитанное

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-support_messages-dialog_id', '{{%support_messages}}', 'dialog_id');
        $this->addForeignKey('fk-support_messages-dialog_id', '{{%support_messages}}', 'dialog_id', '{{%support_dialogs}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-support_messages-user_id', '{{%support_messages}}', 'user_id');
        $this->addForeignKey('fk-support_messages-user_id', '{{%support_messages}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%support_messages}}');
        $this->dropTable('{{%support_dialogs}}');
    }
}
