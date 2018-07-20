<?php

use yii\db\Migration;

/**
 * Handles the creation of table `trade_delivery`.
 */
class m180719_112125_create_trade_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%trade_deliveries}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'has_terms' => $this->boolean()->notNull()->defaultValue(false),
            'has_regions' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);

        $this->batchInsert('{{%trade_deliveries}}', ['id', 'name', 'has_terms', 'has_regions'], [
            [1, 'Самовывоз', 1, 0],
            [2, 'Транспортная компания', 1, 1],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%trade_deliveries}}');
    }
}
