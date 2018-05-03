<?php

use yii\db\Migration;

/**
 * Class m180503_064444_create_currencies_file
 */
class m180503_064444_create_currencies_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $path = Yii::getAlias(Yii::$app->filedb->path) . '/currencies.json';
        file_put_contents($path, '{}');
        echo "currencies.json created" . PHP_EOL;

        for ($i = 1; $i <= 4; $i++) {
            $currency = new \core\entities\Currency();
            $currency->id = $i;
            $currency->default = 0;

            switch ($i) {
                case 1:
                    $currency->name = 'Российский рубль';
                    $currency->sign = 'руб.';
                    $currency->default = 1;
                    break;
                case 2:
                    $currency->name = 'Американский доллар';
                    $currency->sign = 'USD';
                    break;
                case 3:
                    $currency->name = 'Евро';
                    $currency->sign = 'EUR';
                    break;
                case 4:
                    $currency->name = 'Британский фунт стерлингов';
                    $currency->sign = 'GBP';
                    break;
            }

            $currency->save();
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $path = Yii::getAlias(Yii::$app->filedb->path) . '/currencies.json';
        unlink($path);
    }
}
