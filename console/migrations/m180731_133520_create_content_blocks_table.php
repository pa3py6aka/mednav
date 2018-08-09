<?php

use yii\db\Migration;

/**
 * Handles the creation of table `content_blocks`.
 */
class m180731_133520_create_content_blocks_table extends Migration
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
        $this->createTable('content_blocks', [
            'id' => $this->primaryKey(),
            'type' => $this->tinyInteger()->notNull(),
            'name' => $this->string(),
            'enable' => $this->tinyInteger()->notNull()->defaultValue(0),
            'view' => $this->tinyInteger()->notNull(),
            'items' => $this->tinyInteger()->notNull()->defaultValue(5),
            'html' => $this->text(),
            'module' => $this->tinyInteger()->notNull(),
            'htmlCategories' => $this->text(),
            'htmlModules' => $this->text(),
            'place' => $this->tinyInteger()->notNull(),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('content_blocks');
    }
}
