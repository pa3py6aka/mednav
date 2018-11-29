<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contacts`.
 */
class m181119_214439_create_contacts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contacts}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'contact_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-contacts-user_id-contact_id', '{{%contacts}}', ['user_id', 'contact_id'], true);
        $this->addForeignKey('fk-contacts-user_id', '{{%contacts}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-contacts-contact_id', '{{%contacts}}', 'contact_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contacts}}');
    }
}
