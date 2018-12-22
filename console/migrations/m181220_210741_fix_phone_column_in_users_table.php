<?php

use yii\db\Migration;

/**
 * Class m181220_210741_fix_phone_column_in_users_table
 */
class m181220_210741_fix_phone_column_in_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%users}}', 'phone', $this->string(255)->notNull()->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%users}}', 'phone', $this->string(40)->notNull()->defaultValue(''));
    }
}
