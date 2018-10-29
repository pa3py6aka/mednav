<?php

use yii\db\Migration;

/**
 * Class m181029_101703_add_external_link_column_to_trades
 */
class m181029_101703_add_external_link_column_to_trades extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%trades}}', 'external_link', $this->string()->notNull()->defaultValue('')->after('stock'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%trades}}', 'external_link');
    }
}
