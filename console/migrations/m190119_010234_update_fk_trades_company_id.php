<?php

use yii\db\Migration;

/**
 * Class m190119_010234_update_fk_trades_company_id
 */
class m190119_010234_update_fk_trades_company_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-trades-company_id', '{{%trades}}');
        $this->addForeignKey('fk-trades-company_id', '{{%trades}}', 'company_id', '{{%companies}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-trades-company_id', '{{%trades}}');
        $this->addForeignKey('fk-trades-company_id', '{{%trades}}', 'company_id', '{{%companies}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
