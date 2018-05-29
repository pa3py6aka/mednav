<?php

use yii\db\Migration;

/**
 * Class m180529_105208_alter_price_column_in_boards
 */
class m180529_105208_alter_price_column_in_boards extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%boards}}', 'price', $this->integer()->null());
        \core\entities\Board\Board::updateAll(['price' => null], ['price' => 0]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        \core\entities\Board\Board::updateAll(['price' => 0], ['price' => null]);
        $this->alterColumn('{{%boards}}', 'price', $this->integer()->notNull()->defaultValue(0));
    }
}
