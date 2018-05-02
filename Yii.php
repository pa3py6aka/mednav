<?php
/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;
}

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 *
 * @property \core\components\SettingsManager $settings Settings component.
 * @property \yii\redis\Connection $redis
 * @property \yii2tech\filedb\Connection $filedb
 */
abstract class BaseApplication extends yii\base\Application
{
}

/**
 * Class WebApplication
 * Include only Web application related components here
 *
 * @property \core\components\YiiUser $user The user component. This property is read-only.
 * @property \yii\web\UrlManager $backendUrlManager
 * @property \yii\web\UrlManager $frontendUrlManager
 */
class WebApplication extends yii\web\Application
{
}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 *
 */
class ConsoleApplication extends yii\console\Application
{
}