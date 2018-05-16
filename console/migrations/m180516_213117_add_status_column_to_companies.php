<?php

use yii\db\Migration;

/**
 * Class m180516_213117_add_status_column_to_companies
 */
class m180516_213117_add_status_column_to_companies extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%companies}}', 'status', $this->tinyInteger()->notNull()->defaultValue(5)->after('main_photo_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%companies}}', 'status');
    }
}
