<?php

use yii\db\Migration;

/**
 * Class m180428_083910_add_email_confirm_token_column
 */
class m180428_083910_add_email_confirm_token_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'email_confirm_token', $this->string()->after('password_reset_token')->unique());

    }

    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'email_confirm_token');
    }
}
