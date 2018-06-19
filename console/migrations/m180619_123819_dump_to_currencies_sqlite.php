<?php

use yii\db\Migration;

/**
 * Class m180619_123819_dump_to_currencies_sqlite
 */
class m180619_123819_dump_to_currencies_sqlite extends Migration
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
        $this->batchInsert('currencies', ['id', 'name', 'sign', 'default', 'module'], [
            [1, 'Российский рубль', 'руб.', 1, 1],
            [2, 'Американский доллар', 'USD', 0, 1],
            [3, 'Евро', 'EUR', 0, 1],
            [4, 'Британский фунт стерлингов', 'GBP', 0, 1],

            [5, 'Российский рубль', 'руб.', 1, 2],
            [6, 'Американский доллар', 'USD', 0, 2],
            [7, 'Евро', 'EUR', 0, 2],
            [8, 'Британский фунт стерлингов', 'GBP', 0, 2],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('currencies');
    }
}
