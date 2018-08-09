<?php

use yii\db\Migration;

/**
 * Class m180807_081439_update_content_blocks_table
 */
class m180807_081439_update_content_blocks_table extends Migration
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
        $this->dropTable('content_blocks');
        $this->createTable('content_blocks', [
            'id' => $this->primaryKey(),
            'type' => $this->tinyInteger()->notNull(),
            'name' => $this->string(),
            'enable' => $this->tinyInteger()->notNull()->defaultValue(0),
            'show_title' => $this->tinyInteger()->notNull()->defaultValue(1),
            'view' => $this->tinyInteger()->notNull(),
            'items' => $this->tinyInteger()->notNull()->defaultValue(5),
            'for_module' => $this->tinyInteger()->notNull()->defaultValue(0),
            'html' => $this->text(),
            'module' => $this->tinyInteger()->notNull(),
            'htmlCategories' => $this->text(),
            'place' => $this->tinyInteger()->notNull(),
            'page' => $this->tinyInteger()->notNull()->defaultValue(1),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
