<?php

use yii\db\Migration;

/**
 * Class m181012_091830_create_currencies_table_mysql
 */
class m181012_091830_create_currencies_table_mysql extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currencies}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'sign' => $this->string()->notNull(),
            'default' => $this->boolean()->notNull()->defaultValue(false),
            'module' => $this->smallInteger()->notNull(),
        ]);

        $this->batchInsert('{{%currencies}}', ['id', 'name', 'sign', 'default', 'module'], [
            [1, 'Российский рубль', 'руб.', 1, 1],
            [2, 'Американский доллар', 'USD', 0, 1],
            [3, 'Евро', 'EUR', 0, 1],
            [4, 'Британский фунт стерлингов', 'GBP', 0, 1],

            [5, 'Российский рубль', 'руб.', 1, 2],
            [6, 'Американский доллар', 'USD', 0, 2],
            [7, 'Евро', 'EUR', 0, 2],
            [8, 'Британский фунт стерлингов', 'GBP', 0, 2],
        ]);

        $this->alterColumn('{{%boards}}', 'currency_id', $this->integer()->notNull());
        $this->alterColumn('{{%trade_user_categories}}', 'currency_id', $this->integer()->notNull());

        $this->addForeignKey('fk-boards-currency_id', '{{%boards}}', 'currency_id', '{{%currencies}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-trade_user_categories-currency_id', '{{%trade_user_categories}}', 'currency_id', '{{%currencies}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currencies}}');
    }
}
