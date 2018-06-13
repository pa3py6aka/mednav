<?php

use yii\db\Migration;

/**
 * Class m180610_081224_add_views_column_to_companies
 */
class m180610_081224_add_views_column_to_companies extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%companies}}', 'views', $this->integer()->notNull()->unsigned()->defaultValue(0)->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%companies}}', 'views');
    }
}
