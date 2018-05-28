<?php

use yii\db\Migration;

/**
 * Class m180528_172000_add_views_column_to_boards
 */
class m180528_172000_add_views_column_to_boards extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%boards}}', 'views', $this->integer()->notNull()->defaultValue(0)->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%boards}}', 'views');
    }
}
