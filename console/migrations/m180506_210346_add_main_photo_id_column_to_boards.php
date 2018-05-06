<?php

use yii\db\Migration;

/**
 * Class m180506_210346_add_main_photo_id_column_to_boards
 */
class m180506_210346_add_main_photo_id_column_to_boards extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%boards}}', 'main_photo_id', $this->integer()->null()->after('geo_id'));
        $this->addForeignKey('fk-boards-main_photo_id', '{{%boards}}', 'main_photo_id', '{{%board_photos}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%boards}}', 'main_photo_id');
    }
}
