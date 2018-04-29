<?php

use yii\db\Migration;

/**
 * Handles the creation of table `geo`.
 */
class m180429_075718_create_geo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%geo}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'popular' => $this->boolean()->notNull()->defaultValue(false),
            'active' => $this->boolean()->notNull()->defaultValue(true),

            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-geo-slug}}', '{{%geo}}', 'slug', true);

        $dump = file_get_contents(Yii::getAlias('@console/migrations/dumps/mednav_geo.sql'));
        $this->execute($dump);

        /*$this->insert('{{%geo}}', [
            'id' => 1,
            'name' => '',
            'slug' => 'root',
            'popular' => false,
            'active' => true,
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
        ]);*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%geo}}');
    }
}
