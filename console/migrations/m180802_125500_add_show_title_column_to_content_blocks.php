<?php

use yii\db\Migration;

/**
 * Class m180802_125500_add_show_title_column_to_content_blocks
 */
class m180802_125500_add_show_title_column_to_content_blocks extends Migration
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
        $this->addColumn('content_blocks', 'show_title', $this->tinyInteger()->notNull()->defaultValue(1)->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('content_blocks', 'show_title');
    }
}
