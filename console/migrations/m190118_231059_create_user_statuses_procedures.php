<?php

use yii\db\Migration;

/**
 * Class m190118_231059_create_user_statuses_procedures
 */
class m190118_231059_create_user_statuses_procedures extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
CREATE PROCEDURE `deactivate_user_content` (IN userId INT)
LANGUAGE SQL
SQL SECURITY DEFINER
COMMENT 'Деактивирует весь контент пользователя когда его безопасно удаляют(статус пользователя - удалён)'
BEGIN
  UPDATE {{%boards}} SET `prev_status`=`status`, `status`=1 WHERE `author_id`=userId;
  UPDATE {{%articles}} SET `prev_status`=`status`, `status`=1 WHERE `user_id`=userId;
  UPDATE {{%brands}} SET `prev_status`=`status`, `status`=1 WHERE `user_id`=userId;
  UPDATE {{%cnews}} SET `prev_status`=`status`, `status`=1 WHERE `user_id`=userId;
  UPDATE {{%companies}} SET `prev_status`=`status`, `status`=1 WHERE `user_id`=userId;
  UPDATE {{%expositions}} SET `prev_status`=`status`, `status`=1 WHERE `user_id`=userId;
  UPDATE {{%news}} SET `prev_status`=`status`, `status`=1 WHERE `user_id`=userId;
  UPDATE {{%trades}} SET `prev_status`=`status`, `status`=1 WHERE `user_id`=userId;
END;
SQL;
        $this->execute($sql);

        $sql = <<<SQL
CREATE PROCEDURE `activate_user_content` (IN userId INT)
LANGUAGE SQL
SQL SECURITY DEFINER
COMMENT 'Активирует весь контент пользователя когда его восстанавливают после безопасного удаления/блокировки'
BEGIN
  UPDATE {{%boards}} SET `status`=`prev_status`, `prev_status`=1 WHERE `author_id`=userId AND `status`=1;
  UPDATE {{%articles}} SET `status`=`prev_status`, `prev_status`=1 WHERE `user_id`=userId AND `status`=1;
  UPDATE {{%brands}} SET `status`=`prev_status`, `prev_status`=1 WHERE `user_id`=userId AND `status`=1;
  UPDATE {{%cnews}} SET `status`=`prev_status`, `prev_status`=1 WHERE `user_id`=userId AND `status`=1;
  UPDATE {{%companies}} SET `status`=`prev_status`, `prev_status`=1 WHERE `user_id`=userId AND `status`=1;
  UPDATE {{%expositions}} SET `status`=`prev_status`, `prev_status`=1 WHERE `user_id`=userId AND `status`=1;
  UPDATE {{%news}} SET `status`=`prev_status`, `prev_status`=1 WHERE `user_id`=userId AND `status`=1;
  UPDATE {{%trades}} SET `status`=`prev_status`, `prev_status`=1 WHERE `user_id`=userId AND `status`=1;
END;
SQL;
        $this->execute($sql);

        $sql = <<<SQL
CREATE TRIGGER update_user BEFORE UPDATE ON users
  FOR EACH ROW
  BEGIN
    IF NEW.status = 0 AND OLD.status > 0 THEN
      CALL deactivate_user_content(NEW.id);
    ELSEIF OLD.status = 0 AND NEW.status > 0 THEN
      CALL activate_user_content(NEW.id);
    END IF;
  END;
SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP TRIGGER update_user;');
        $this->execute('DROP PROCEDURE activate_user_content;');
        $this->execute('DROP PROCEDURE deactivate_user_content;');
    }
}
