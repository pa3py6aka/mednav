<?php

use yii\db\Migration;

/**
 * Class m180619_115937_create_currencies_table_sqlite
 */
class m180619_115937_create_currencies_table_sqlite extends Migration
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
        $this->createTable('currencies', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'sign' => $this->string()->notNull(),
            'default' => $this->boolean()->notNull()->defaultValue(false),
            'module' => $this->smallInteger()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('currencies');
    }
}
