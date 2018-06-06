<?php

use yii\db\Migration;

/**
 * Class m180604_055111_move_user_company_relation
 */
class m180604_055111_move_user_company_relation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-users-company_id', '{{%users}}');
        $this->dropColumn('{{%users}}', 'company_id');

        $this->addColumn('{{%companies}}', 'user_id', $this->integer()->after('category_id'));
        $this->addForeignKey('fk-companies-user_id', '{{%companies}}', 'user_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-companies-user_id', '{{%companies}}');
        $this->dropColumn('{{%companies}}', 'user_id');

        $this->addColumn('{{%users}}', 'company_id', $this->integer()->after('type'));
        $this->addForeignKey('fk-users-company_id', '{{%users}}', 'company_id', '{{%companies}}', 'id', 'SET NULL', 'CASCADE');
    }
}
