<?php

use yii\db\Migration;

/**
 * Handles the creation of table `context_blocks`.
 */
class m180731_105709_create_context_blocks_table extends Migration
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
        $this->createTable('context_blocks', [
            'id' => $this->primaryKey(),
            'html' => $this->text(),
            'enable' => $this->tinyInteger()->notNull()->defaultValue(0),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->batchInsert('context_blocks', ['html'], [
            [''], [''], [''], [''], ['']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('context_blocks');
    }
}
