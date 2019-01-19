<?php

use yii\db\Migration;

/**
 * Class m190118_221848_add_prev_status_column_to_content_tables
 */
class m190118_221848_add_prev_status_column_to_content_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%boards}}', 'prev_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('status'));
        $this->addColumn('{{%articles}}', 'prev_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('status'));
        $this->addColumn('{{%brands}}', 'prev_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('status'));
        $this->addColumn('{{%cnews}}', 'prev_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('status'));
        $this->addColumn('{{%companies}}', 'prev_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('status'));
        $this->addColumn('{{%expositions}}', 'prev_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('status'));
        $this->addColumn('{{%news}}', 'prev_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('status'));
        $this->addColumn('{{%trades}}', 'prev_status', $this->tinyInteger()->notNull()->defaultValue(0)->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%boards}}', 'prev_status');
        $this->dropColumn('{{%articles}}', 'prev_status');
        $this->dropColumn('{{%brands}}', 'prev_status');
        $this->dropColumn('{{%cnews}}', 'prev_status');
        $this->dropColumn('{{%companies}}', 'prev_status');
        $this->dropColumn('{{%expositions}}', 'prev_status');
        $this->dropColumn('{{%news}}', 'prev_status');
        $this->dropColumn('{{%trades}}', 'prev_status');
    }
}
