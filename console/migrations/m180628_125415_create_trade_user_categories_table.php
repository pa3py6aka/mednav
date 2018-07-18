<?php

use yii\db\Migration;

/**
 * Handles the creation of table `trade_user_categories`.
 */
class m180628_125415_create_trade_user_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%trade_user_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'uom_id' => $this->integer()->notNull(),
            'currency_id' => $this->integer()->notNull(),
            'wholesale' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);

        $this->addForeignKey('fk-trade_user_categories-category_id', '{{%trade_user_categories}}', 'category_id', '{{%trade_categories}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%trade_user_category_assignments}}', [
            'trade_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-trade_user_category_assignments', '{{%trade_user_category_assignments}}', ['trade_id', 'category_id']);
        $this->addForeignKey('fk-trade_user_category_assignments-trade_id', '{{%trade_user_category_assignments}}', 'trade_id', '{{%trades}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropTable('{{%trade_category_assignments}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('trade_user_categories');
    }
}
