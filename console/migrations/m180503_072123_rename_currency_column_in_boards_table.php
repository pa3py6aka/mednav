<?php

use yii\db\Migration;

/**
 * Class m180503_072123_rename_currency_column_in_boards_table
 */
class m180503_072123_rename_currency_column_in_boards_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%boards}}', 'currency', 'currency_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%boards}}', 'currency_id', 'currency');
    }
}
