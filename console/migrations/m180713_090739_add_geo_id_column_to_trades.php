<?php

use yii\db\Migration;

/**
 * Class m180713_090739_add_geo_id_column_to_trades
 */
class m180713_090739_add_geo_id_column_to_trades extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%trades}}', 'geo_id', $this->integer()->notNull()->after('user_id'));
        $this->createIndex('idx-trades-geo_id', '{{%trades}}', 'geo_id');
        $this->addForeignKey('fk-trades-geo_id', '{{%trades}}', 'geo_id', '{{%geo}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-trades-geo_id', '{{%trades}}');
        $this->dropIndex('idx-trades-geo_id', '{{%trades}}');
        $this->dropColumn('{{%trades}}', 'geo_id');
    }
}
