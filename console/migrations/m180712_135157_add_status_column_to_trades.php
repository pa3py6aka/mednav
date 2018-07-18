<?php

use yii\db\Migration;

/**
 * Class m180712_135157_add_status_column_to_trades
 */
class m180712_135157_add_status_column_to_trades extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%trades}}', 'status', $this->tinyInteger()->notNull()->defaultValue(5)->after('main_photo_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%trades}}', 'status');
    }
}
