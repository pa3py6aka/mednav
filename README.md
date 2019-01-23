<h3>Требования к серверу</h3>
 
 - Php 7.1 и выше
 - Mysql
 - SQLite
 - Redis
 - Composer

Для сжатия изображений необходимо установить утилиты. Необязательно все, смотрите документацию здесь - [https://github.com/spatie/image-optimizer](https://github.com/spatie/image-optimizer), там же есть короткая инструкция по установке.

 - JpegOptim
 - Optipng
 - Pngquant 2
 - SVGO
 - Gifsicle

<h3>Инструкция по установке на сервер</h3>

 - Заливаем в нужную папку исходники(вручную или клонированием через гит с репозитория)
 - Обновляем зависимости командой `composer update`
 - Инициализируем - `php init`, выбираем версию(продакшин или разработка)
 - Далее в файле `common/config/main-local.php` в секции `db` нужно прописать данные доступа к БД. Для этого там нужно отредактировать три строки:
 
 `'dsn' => 'mysql:host=localhost;dbname=ИМЯ_БАЗЫ_ДАННЫХ',`
 
`'username' => 'ПОЛЬЗОВАТЕЛЬ',`

`'password' => 'ПАРОЛЬ',`

 - В файле `common/config/params-local.php` прописываем домены основного сайта и админки (если сайт работает через ssl, путь к доменам нужно указывать через `https://`)
 - Запускаем миграции - `php yii migrate`
 - Инициализируем RBAC - `php yii rbac/init`
 - Добавляем администратора - `php yii user/create`: указываем e-mail, пароль, выбираем тип(пользователь или компания, по сути не важно), в поле `Role` указываем `admin`
 - Домены нужно настроить так, чтобы основной домен шел на папку `frontend/web`, а админка на `backend/web`
 - В крон нужно добавить следующие задачи:
 
 `0 * * * * php /home/admin/web/mednav/yii cron/temp-cleaner`
 
 `* * * * * php /home/admin/web/mednav/yii queue/run`
 
 `0 * * * * php /home/admin/web/mednav/yii cron/archivator`
 
 `0 * * * * php /home/admin/web/mednav/yii cron/board-extend-notificator`
 
 Здесь `/home/admin/web/mednav/` это путь к папке в которую установили сайт

 