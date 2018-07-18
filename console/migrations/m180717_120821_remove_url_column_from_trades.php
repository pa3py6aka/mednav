<?php

use yii\db\Migration;

/**
 * Class m180717_120821_remove_url_column_from_trades
 */
class m180717_120821_remove_url_column_from_trades extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%trades}}', 'url');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%trades}}', 'url', $this->string()->notNull()->defaultValue('')->after('stock'));
    }
}
