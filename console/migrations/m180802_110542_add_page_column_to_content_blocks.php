<?php

use yii\db\Migration;

/**
 * Class m180802_110542_add_page_column_to_content_blocks
 */
class m180802_110542_add_page_column_to_content_blocks extends Migration
{
    public function init()
    {
        $this->db = 'sqlite';
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('content_blocks', 'page', $this->tinyInteger()->notNull()->defaultValue(1)->after('module'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('content_blocks', 'page');
    }
}
