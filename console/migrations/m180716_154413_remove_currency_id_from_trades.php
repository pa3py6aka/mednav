<?php

use yii\db\Migration;

/**
 * Class m180716_154413_remove_currency_id_from_trades
 */
class m180716_154413_remove_currency_id_from_trades extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%trades}}', 'currency_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%trades}}', 'currency_id', $this->smallInteger()->notNull()->after('price'));
    }
}
