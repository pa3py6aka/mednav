<?php

use yii\db\Migration;

/**
 * Handles the creation of table `trades`.
 */
class m180628_113415_create_trades_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%trades}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'meta_title' => $this->string()->notNull()->defaultValue(''),
            'meta_description' => $this->text()->notNull(),
            'meta_keywords' => $this->string()->notNull()->defaultValue(''),
            'slug' => $this->string()->notNull(),
            'code' => $this->string()->notNull()->defaultValue(''),
            'price' => $this->integer(),
            'currency_id' => $this->smallInteger()->notNull(),
            'wholesale_prices' => $this->text()->notNull(),
            'stock' => $this->tinyInteger()->notNull()->defaultValue(0),
            'url' => $this->string()->notNull()->defaultValue(''),
            'note' => $this->string(80)->notNull()->defaultValue(''),
            'description' => $this->text()->notNull(),

            'views' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-trades-user_id', '{{%trades}}', 'user_id');
        $this->addForeignKey('fk-trades-user_id', '{{%trades}}', 'user_id', '{{%users}}', 'id', 'RESTRICT', 'CASCADE');
        $this->createIndex('idx-trades-slug', '{{%trades}}', 'slug', true);

        $this->createTable('{{%trade_category_assignments}}', [
            'trade_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-trade_category_assignments', '{{%trade_category_assignments}}', ['trade_id', 'category_id']);
        $this->addForeignKey('fk-trade_category_assignments-trade_id', '{{%trade_category_assignments}}', 'trade_id', '{{%trades}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%trade_category_assignments}}');
        $this->dropTable('{{%trades}}');
    }
}
