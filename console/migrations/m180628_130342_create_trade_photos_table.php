<?php

use yii\db\Migration;

/**
 * Handles the creation of table `trade_photos`.
 */
class m180628_130342_create_trade_photos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%trade_photos}}', [
            'id' => $this->primaryKey(),
            'trade_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull()->unique(),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('idx-trade_photos-trade_id', '{{%trade_photos}}', 'trade_id');
        $this->addForeignKey('fk-trade_photos-trade_id', '{{%trade_photos}}', 'trade_id', '{{%trades}}', 'id', 'CASCADE', 'CASCADE');

        $this->addColumn('{{%trades}}', 'main_photo_id', $this->integer()->after('description'));
        $this->addForeignKey('fk-trades-main_photo_id', '{{%trades}}', 'main_photo_id', '{{%trade_photos}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-trades-main_photo_id', '{{%trades}}');
        $this->dropColumn('{{%trades}}', 'main_photo_id');
        $this->dropTable('{{%trade_photos}}');
    }
}
