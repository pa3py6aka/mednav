<?php

use yii\db\Migration;

/**
 * Class m180528_154614_add_notification_date_to_boards
 */
class m180528_154614_add_notification_date_to_boards extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%boards}}', 'notification_date', $this->integer()->notNull()->defaultValue(0)->after('active_until'));

        /* @var $board \core\entities\Board\Board */
        foreach (\core\entities\Board\Board::find()->each() as $board) {
            $time = $board->term->getNotificationInSeconds();
            $board->notification_date = $board->active_until - $time;
            $board->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%boards}}', 'notification_date');
    }
}
