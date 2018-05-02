<?php

use yii\db\Migration;

/**
 * Class m180502_083100_create_board_terms_file
 */
class m180502_083100_create_board_terms_file extends Migration
{
    public function up()
    {
        $path = Yii::getAlias(Yii::$app->filedb->path) . '/board-terms.json';
        file_put_contents($path, '{}');
        echo "board-terms.json created" . PHP_EOL;

        for ($i = 1; $i <= 4; $i++) {
            $term = new \core\entities\Board\BoardTerm();
            $term->id = $i;
            $term->notification = 1;
            $term->default = 0;

            switch ($i) {
                case 1:
                    $term->days = 10;
                    $term->daysHuman = '10 дней';
                    break;
                case 2:
                    $term->days = 30;
                    $term->daysHuman = '1 мес.';
                    break;
                case 3:
                    $term->days = 60;
                    $term->daysHuman = '2 мес.';
                    $term->default = 1;
                    break;
                case 4:
                    $term->days = 90;
                    $term->daysHuman = '3 мес.';
                    break;
            }

            $term->save();
        }

    }

    public function down()
    {
        $path = Yii::getAlias(Yii::$app->filedb->path) . '/board-terms.json';
        unlink($path);
    }
}
