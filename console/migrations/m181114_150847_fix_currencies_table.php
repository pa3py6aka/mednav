<?php

use yii\db\Migration;

/**
 * Class m181114_150847_fix_currencies_table
 */
class m181114_150847_fix_currencies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-boards-currency_id', '{{%boards}}');
        $this->dropForeignKey('fk-trade_user_categories-currency_id', '{{%trade_user_categories}}');
        $this->alterColumn('{{%currencies}}', 'id', $this->integer()->notNull());
        $this->dropPrimaryKey('PRIMARY', '{{%currencies}}');
        $this->addPrimaryKey('pk-currencies', '{{%currencies}}', ['id', 'module']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('PRIMARY', '{{%currencies}}');
        $this->alterColumn('{{%currencies}}', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
        $this->addPrimaryKey('PRIMARY', '{{%currencies}}', 'id');
        $this->addForeignKey('fk-boards-currency_id', '{{%boards}}', 'currency_id', '{{%currencies}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-trade_user_categories-currency_id', '{{%trade_user_categories}}', 'currency_id', '{{%currencies}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
