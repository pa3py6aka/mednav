<?php

use yii\db\Migration;

/**
 * Handles adding profile to table `users`.
 */
class m180515_123613_add_profile_columns_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'geo_id', $this->integer()->after('company_id'));
        $this->addColumn('{{%users}}', 'last_name', $this->string()->notNull()->defaultValue('')->after('geo_id'));
        $this->addColumn('{{%users}}', 'name', $this->string()->notNull()->defaultValue('')->after('last_name'));
        $this->addColumn('{{%users}}', 'patronymic', $this->string()->notNull()->defaultValue('')->after('name'));
        $this->addColumn('{{%users}}', 'gender', $this->tinyInteger()->notNull()->defaultValue(0)->after('patronymic'));
        $this->addColumn('{{%users}}', 'birthday', $this->date()->after('gender'));
        $this->addColumn('{{%users}}', 'phone', $this->string(25)->notNull()->defaultValue('')->after('birthday'));
        $this->addColumn('{{%users}}', 'site', $this->string()->notNull()->defaultValue('')->after('phone'));
        $this->addColumn('{{%users}}', 'skype', $this->string()->notNull()->defaultValue('')->after('site'));
        $this->addColumn('{{%users}}', 'organization', $this->string()->notNull()->defaultValue('')->after('skype'));

        $this->addForeignKey('fk-users-geo_id', '{{%users}}', 'geo_id', '{{%geo}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-users-geo_id', '{{%users}}');

        $this->dropColumn('{{%users}}', 'organization');
        $this->dropColumn('{{%users}}', 'skype');
        $this->dropColumn('{{%users}}', 'site');
        $this->dropColumn('{{%users}}', 'phone');
        $this->dropColumn('{{%users}}', 'birthday');
        $this->dropColumn('{{%users}}', 'gender');
        $this->dropColumn('{{%users}}', 'patronymic');
        $this->dropColumn('{{%users}}', 'name');
        $this->dropColumn('{{%users}}', 'last_name');
        $this->dropColumn('{{%users}}', 'geo_id');
    }
}
