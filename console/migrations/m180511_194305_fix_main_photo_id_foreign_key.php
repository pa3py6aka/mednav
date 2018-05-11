<?php

use yii\db\Migration;

/**
 * Class m180511_194305_fix_main_photo_id_foreign_key
 */
class m180511_194305_fix_main_photo_id_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-boards-main_photo_id', '{{%boards}}');
        $this->addForeignKey('fk-boards-main_photo_id', '{{%boards}}', 'main_photo_id', '{{%board_photos}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-boards-main_photo_id', '{{%boards}}');
        $this->addForeignKey('fk-boards-main_photo_id', '{{%boards}}', 'main_photo_id', '{{%board_photos}}', 'id', 'CASCADE', 'CASCADE');
    }
}
