<?php

use yii\db\Migration;

/**
 * Class m180619_142211_create_trade_uoms_table_sqlite
 */
class m180619_142211_create_trade_uoms_table_sqlite extends Migration
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
        $this->createTable('trade_uoms', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'sign' => $this->string()->notNull(),
            'default' => $this->boolean()->notNull()->defaultValue(false),
        ]);

        $this->batchInsert('trade_uoms', ['id', 'name', 'sign', 'default'], [
            [1, 'Штуки', 'шт.', 1],
            [2, 'Килограмм', 'кг', 0],
            [3, 'Литр', 'л', 0],
            [4, 'Упаковка', 'упак.', 0],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('trade_uoms');
    }
}
