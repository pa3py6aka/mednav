<?php

namespace console\controllers;


use core\access\Rbac;
use core\entities\Article\Article;
use core\entities\Article\ArticleCategory;
use core\entities\Article\ArticlePhoto;
use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Board\BoardCategoryParameter;
use core\entities\Board\BoardParameterAssignment;
use core\entities\Board\BoardPhoto;
use core\entities\Brand\Brand;
use core\entities\Brand\BrandCategory;
use core\entities\Brand\BrandPhoto;
use core\entities\CategoryInterface;
use core\entities\CNews\CNews;
use core\entities\CNews\CNewsCategory;
use core\entities\CNews\CNewsPhoto;
use core\entities\Company\Company;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryAssignment;
use core\entities\Company\CompanyCategoryRegion;
use core\entities\Company\CompanyDeliveryRegion;
use core\entities\Company\CompanyPhoto;
use core\entities\Company\CompanyTag;
use core\entities\Company\CompanyTagsAssignment;
use core\entities\Contact;
use core\entities\Dialog\Dialog;
use core\entities\Dialog\Message;
use core\entities\Expo\Expo;
use core\entities\Expo\ExpoCategory;
use core\entities\Expo\ExpoPhoto;
use core\entities\Geo;
use core\entities\News\News;
use core\entities\News\NewsCategory;
use core\entities\News\NewsPhoto;
use core\entities\Order\Order;
use core\entities\Order\OrderItem;
use core\entities\Order\UserOrder;
use core\entities\PhotoInterface;
use core\entities\SupportDialog\SupportDialog;
use core\entities\SupportDialog\SupportMessage;
use core\entities\Trade\Trade;
use core\entities\Trade\TradeCategory;
use core\entities\Trade\TradeCategoryRegion;
use core\entities\Trade\TradePhoto;
use core\entities\Trade\TradeUserCategory;
use core\entities\User\User;
use core\helpers\PaginationHelper;
use core\helpers\PriceHelper;
use core\services\RoleManager;
use core\services\TransactionManager;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\Console;
use yii\helpers\StringHelper;

class ImportController extends Controller
{
    private $command;
    private $transaction;
    private $oldImagesPath;
    private $imagesPath;

    public function __construct(string $id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->command = (new Query())->createCommand(Yii::$app->get('oldDb'));
        $this->transaction = new TransactionManager();
        $this->oldImagesPath = Yii::getAlias('@frontend/web/i-old/');
        $this->imagesPath = Yii::getAlias('@images/');
    }

    public function actionUsers(): void
    {
        echo 'Starting users import...' . PHP_EOL;
        $rbacManager = Yii::createObject(RoleManager::class);

        $this->transaction->wrap(function () use ($rbacManager) {
            User::deleteAll();

            $sql = 'SELECT `as`.`item_name`,`u`.* FROM `users` `u` LEFT JOIN `auth_assignment` `as` on `as`.`user_id`=`u`.`id` ORDER BY `u`.`id`';
            $query = $this->command->setSql($sql);
            $n = 0;
            foreach ($query->queryAll() as $oldUser) {
                if ($user = User::find()->where(['email' => $oldUser['email']])->limit(1)->one()) {
                    $user->type = $oldUser['item_name'] === 'company' ? User::TYPE_COMPANY : User::TYPE_USER;
                    $user->status = $oldUser['is_active'] == '1' ? User::STATUS_ACTIVE : User::STATUS_DELETED;
                    $user->auth_key = $oldUser['auth_key'];
                    $user->password_hash = $oldUser['password'];
                    $user->email_confirm_token = $oldUser['confirm_hash'];
                } else {
                    $user = new User([
                        'id' => $oldUser['id'],
                        'email' => $oldUser['email'],
                        'type' => $oldUser['item_name'] === 'company' ? User::TYPE_COMPANY : User::TYPE_USER,
                        'geo_id' => null,
                        'last_name' => '',
                        'name' => '',
                        'patronymic' => '',
                        'gender' => User::GENDER_NOT_SET,
                        'birthday' => null,
                        'phone' => '',
                        'site' => '',
                        'skype' => '',
                        'organization' => '',
                        'last_online' => 0,
                        'status' => $oldUser['is_active'] == '1' ? User::STATUS_ACTIVE : User::STATUS_DELETED,
                        'auth_key' => $oldUser['auth_key'],
                        'password_hash' => $oldUser['password'],
                        'password_reset_token' => null,
                        'email_confirm_token' => $oldUser['confirm_hash'],
                        'created_at' => Yii::$app->formatter->asTimestamp($oldUser['created_at']),
                        'updated_at' => Yii::$app->formatter->asTimestamp($oldUser['created_at']),
                    ]);
                }

                if ($profile = $this->command->setSql('select * from profile where userid=' . $oldUser['id'])->queryOne()) {
                    //print_r($profile);
                    $user->last_name = $profile['last_name'] ?: '';
                    $user->name = $profile['name'] ?: '';
                    $user->patronymic = $profile['middle_name'] ?: '';
                    $user->gender = $profile['gender'] == '1' ? User::GENDER_FEMALE : ($profile['gender'] === null ? User::GENDER_NOT_SET : User::GENDER_MALE);
                    $user->birthday = $profile['birthdate'] === '0000-00-00' ? null : $profile['birthdate'];
                    $user->phone = $profile['phone'] ?: '';
                    if ($profile['website'] && $profile['website'] !== 'нет') {
                        $user->site = strpos($profile['website'], '://') === false ? 'http://' . $profile['website'] : $profile['website'];
                    }
                    if ($profile['skype'] && $profile['skype'] !== 'нет') {
                        $user->skype = $profile['skype'];
                    }
                    $user->organization = $profile['organization'] ?: '';

                    if ($profile['region']) {
                        $user->geo_id = $this->getGeoId($profile['region']);
                    }
                }

                if (!$user->save()) {
                    throw new \RuntimeException('Ошибка сохранения пользователя в базу');
                }
                $user->updateAttributes([
                    'created_at' => Yii::$app->formatter->asTimestamp($oldUser['created_at']),
                    'updated_at' => Yii::$app->formatter->asTimestamp($oldUser['created_at']),
                ]);

                $rbacManager->assign($user->id, Rbac::ROLE_USER);
                $n++;
                echo 'Добавлен пользователь "' . $user->getVisibleName() . '"' . PHP_EOL;
            }

            $this->stdout('Импортировано ' . $n . ' пользователей.' . PHP_EOL, Console::FG_GREEN);
        });

    }

    public function actionContacts(): void
    {
        $this->stdout('Стартуем импорт контактов пользователей...' . PHP_EOL, Console::FG_YELLOW);
        $this->transaction->wrap(function () {
            Contact::deleteAll();
            $sql = 'SELECT * FROM `contacts` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $n = 0;
            foreach ($query->queryAll() as $oldContact) {
                $is = $this->command->setSql('SELECT `id` FROM `users` WHERE `id` IN('. $oldContact['userid'] .','. $oldContact['contact_id'] .')')->queryAll();
                if (!\count($is) || ($oldContact['userid'] !== $oldContact['contact_id'] && \count($is) < 2)) {
                    echo 'Контакт ' . $oldContact['id'] . ' пропущен' . PHP_EOL;
                    continue;
                }
                $contact = Contact::create($oldContact['userid'], $oldContact['contact_id']);
                $this->save($contact);
                $n++;
            }
            $this->stdout('Импортировано ' . $n . ' контактов.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    public function actionCompanyCategories(): void
    {
        echo 'Starting company categories import...' . PHP_EOL;

        $this->transaction->wrap(function () {
            CompanyCategory::deleteAll(['>', 'id', 1]);

            $root = CompanyCategory::find()->where(['id' => 1])->one();
            $sql = 'SELECT * FROM `company_sections` ORDER BY `level`,`id`';
            $query = $this->command->setSql($sql);
            $newCategories = [];
            $n = 0;
            foreach ($query->queryAll() as $oldCategory) {
                $category = new CompanyCategory([
                    'id' => $oldCategory['id'],
                    'name' => $oldCategory['name'],
                    'context_name' => '',
                    'enabled' => 1,
                    'not_show_on_main' => $oldCategory['not_publish_on_main'],
                    'children_only_parent' => 0,
                    'slug' => $oldCategory['url'],
                    'meta_title' => $oldCategory['meta_title'] ?: '',
                    'meta_description' => $oldCategory['meta_description'] ?: '',
                    'meta_keywords' => $oldCategory['meta_keywords'] ?: '',
                    'title' => $oldCategory['title'],
                    'description_top' => $oldCategory['description_top'] ?: '',
                    'description_top_on' => $oldCategory['description_top_on'],
                    'description_bottom' => $oldCategory['description_bottom'] ?: '',
                    'description_bottom_on' => $oldCategory['description_bottom_on'],
                    'meta_title_item' => $oldCategory['meta_title_item'] ?: '',
                    'meta_description_item' => $oldCategory['meta_description_item'] ?: '',
                    'meta_title_other' => $oldCategory['meta_title_other'] ?: '',
                    'meta_description_other' => $oldCategory['meta_description_other'] ?: '',
                    'meta_keywords_other' => $oldCategory['meta_keywords_other'] ?: '',
                    'title_other' => $oldCategory['name_other'] ?: '',
                    'pagination' => !$oldCategory['pagination'] ? PaginationHelper::PAGINATION_SCROLL : PaginationHelper::PAGINATION_NUMERIC,
                    'active' => 1,
                ]);

                if ($oldCategory['level'] === '0') {
                    $category->appendTo($root);
                } else {
                    if (!isset($newCategories[$oldCategory['parent']])) {
                        throw new \DomainException('Не найден родитель категории id:' . $category->id. ', родитель id:' . $oldCategory['parent']);
                    }
                    $category->appendTo($newCategories[$oldCategory['parent']]);
                }
                if (!$category->save()) {
                    throw new \RuntimeException('Ошибка записи в базу');
                }

                foreach ($this->command->setSql('select * from `geo_company` WHERE `section_id`=' . $category->id)->queryAll() as $oldCategoryRegion) {
                    if (!$geoId = $this->getGeoId($oldCategoryRegion['region_id'])) {
                        continue;
                    }
                    $categoryRegion = new CompanyCategoryRegion([
                        'category_id' => $category->id,
                        'geo_id' => $geoId,
                        'meta_title' => $oldCategoryRegion['meta_title'],
                        'meta_description' => $oldCategoryRegion['meta_description'],
                        'meta_keywords' => $oldCategoryRegion['meta_keywords'],
                        'title' => $oldCategoryRegion['title'],
                        'description_top' => $oldCategoryRegion['description_top'],
                        'description_top_on' => $oldCategoryRegion['description_top_on'],
                        'description_bottom' => $oldCategoryRegion['description_bottom'],
                        'description_bottom_on' => $oldCategoryRegion['description_bottom_on'],
                    ]);
                    $this->save($categoryRegion);
                }

                $newCategories[$category->id] = $category;
                $n++;
                echo 'Категория "' . $category->name . '" успешно добавлена.' . PHP_EOL;
            }

            $this->stdout('Импортировано ' . $n . ' категорий.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    public function actionCompanies(): void
    {
        echo 'Starting companies import...' . PHP_EOL;
        $this->transaction->wrap(function () {
            Company::deleteAll();
            $sql = 'SELECT * FROM `company` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $n = 0;
            foreach ($query->queryAll() as $oldCompany) {
                $company = new Company([
                    'id' => $oldCompany['id'],
                    'user_id' => $oldCompany['userid'],
                    'form' => $oldCompany['form'],
                    'name' => $oldCompany['name'],
                    'logo' => '',
                    'slug' => $oldCompany['alias'],
                    'site' => '',
                    'geo_id' => $this->getGeoId($oldCompany['region']),
                    'address' => $oldCompany['address'],
                    'phones' => '[]',
                    'fax' => $oldCompany['fax'],
                    'email' => $oldCompany['email'],
                    'info' => $oldCompany['addition'],
                    'title' => $oldCompany['title'],
                    'short_description' => $oldCompany['introtext'],
                    'description' => $oldCompany['fulltext'],
                    'main_photo_id' => null,
                    'status' => Company::STATUS_ACTIVE,
                    'views' => $oldCompany['hits'],
                ]);
                $company->setPhones([$oldCompany['phone'], $oldCompany['second_phone'], $oldCompany['third_phone']]);
                if ($oldCompany['website'] && $oldCompany['website'] !== 'нет') {
                    $company->site = strpos($oldCompany['website'], '://') === false ? 'http://' . $oldCompany['website'] : $oldCompany['website'];
                }

                if ($oldLogo = $this->command->setSql('SELECT * FROM `images` WHERE `item_id`=' . $company->id . ' AND `type`=\'company_logo\' LIMIT 1')->queryOne()) {
                    $fileName = $company->id . '-' . time() . '.' . pathinfo($oldLogo['name'], PATHINFO_EXTENSION);

                    $oldFileName = $this->oldImagesPath . $oldLogo['folder'] . 'small/' . $oldLogo['name'];
                    //echo $oldFileName . PHP_EOL; exit;
                    if (is_file($this->oldImagesPath . $oldLogo['folder'] . 'small/' . $oldLogo['name'])) {
                        copy($this->oldImagesPath . $oldLogo['folder'] . 'small/' . $oldLogo['name'], $this->imagesPath . 'company/lg/small_' . $fileName);
                        copy($this->oldImagesPath . $oldLogo['folder'] . 'big/' . $oldLogo['name'], $this->imagesPath . 'company/lg/big_' . $fileName);
                        copy($this->oldImagesPath . $oldLogo['folder'] . 'max/' . $oldLogo['name'], $this->imagesPath . 'company/lg/max_' . $fileName);
                        $company->logo = $fileName;
                    }
                }

                $this->save($company);
                $company->updateAttributes([
                    'created_at' => Yii::$app->formatter->asTimestamp($oldCompany['date']),
                    'updated_at' => Yii::$app->formatter->asTimestamp($oldCompany['date']),
                ]);

                if ($oldCompany['tags']) {
                    $tags = explode(',', $oldCompany['tags']);
                    foreach ($tags as $oldTag) {
                        $oldTag = str_replace('.', '', trim($oldTag));
                        if (!$tag = CompanyTag::find()->where(['name' => $oldTag])->limit(1)->one()) {
                            $tag = CompanyTag::create($oldTag);
                            $this->save($tag);
                        }
                        $assignment = CompanyTagsAssignment::create($company->id, $tag->id);
                        $this->save($assignment);
                    }
                }

                $this->importImages('company', $company, CompanyPhoto::class);

                // Импорт привязанных категорий к компании
                foreach ($this->command->setSql('SELECT `section_id` FROM `sections_item` WHERE `item_id`=' . $company->id)->queryColumn() as $categoryId) {
                    $assignment = CompanyCategoryAssignment::create($company->id, $categoryId);
                    $this->save($assignment);
                }

                // Импорт регионов доставки
                foreach ($this->command->setSql('SELECT `region_id` FROM `company_delivery_regions` WHERE `company_id`=' . $company->id)->queryColumn() as $regionId) {
                    $assignment = CompanyDeliveryRegion::create($company->id, 2, $this->getGeoId($regionId));
                    $this->save($assignment);
                }

                $n++;
                echo 'Компания "' . $company->name . '" добавлена.' . PHP_EOL;
            }

            $this->stdout('Импортировано ' . $n . ' компаний.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    public function actionBoardCategories(): void
    {
        echo 'Starting board categories import...' . PHP_EOL;

        $this->transaction->wrap(function () {
            BoardCategory::deleteAll(['>', 'id', 1]);

            $root = BoardCategory::find()->where(['id' => 1])->one();
            $sql = 'SELECT * FROM `board_sections` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $newCategories = [];
            $n = 0;
            foreach ($query->queryAll() as $oldCategory) {
                $category = new BoardCategory([
                    'id' => $oldCategory['id'],
                    'name' => $oldCategory['name'],
                    'context_name' => '',
                    'enabled' => 1,
                    'not_show_on_main' => $oldCategory['not_publish_on_main'],
                    'children_only_parent' => 0,
                    'slug' => $oldCategory['url'],
                    'meta_title' => $oldCategory['meta_title'],
                    'meta_description' => $oldCategory['meta_description'],
                    'meta_keywords' => $oldCategory['meta_keywords'],
                    'title' => $oldCategory['title'],
                    'description_top' => $oldCategory['description_top'],
                    'description_top_on' => $oldCategory['description_top_on'],
                    'description_bottom' => $oldCategory['description_bottom'],
                    'description_bottom_on' => $oldCategory['description_bottom_on'],
                    'meta_title_item' => $oldCategory['meta_title_item'],
                    'meta_description_item' => $oldCategory['meta_description_item'],
                    'meta_title_other' => $oldCategory['meta_title_other'],
                    'meta_description_other' => $oldCategory['meta_description_other'],
                    'meta_keywords_other' => $oldCategory['meta_keywords_other'],
                    'title_other' => $oldCategory['title_other'],
                    'pagination' => !$oldCategory['pagination'] ? PaginationHelper::PAGINATION_SCROLL : PaginationHelper::PAGINATION_NUMERIC,
                    'active' => 1,
                ]);

                if ($oldCategory['level'] === '0') {
                    $category->appendTo($root);
                } else {
                    if (!isset($newCategories[$oldCategory['parent']])) {
                        throw new \DomainException('Не найден родитель категории id:' . $category->id. ', родитель id:' . $oldCategory['parent']);
                    }
                    $category->appendTo($newCategories[$oldCategory['parent']]);
                }
                if (!$category->save()) {
                    throw new \RuntimeException('Ошибка записи в базу');
                }

                if ($oldCategory['types'] === '["5"]') {
                    $assignment = BoardCategoryParameter::create($category->id, 1);
                    if (!$assignment->save()) {
                        throw new \RuntimeException('Ошибка записи в базу');
                    }
                }

                $newCategories[$category->id] = $category;
                $n++;
                echo 'Категория "' . $category->name . '" успешно добавлена.' . PHP_EOL;
            }

            $this->stdout('Импортировано ' . $n . ' категорий.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    public function actionBoards(): void
    {
        echo 'Starting boards import...' . PHP_EOL;
        $this->transaction->wrap(function () {
            Board::deleteAll();
            $sql = 'SELECT * FROM `board` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $terms = [10 => 1, 30 => 2, 60 => 3, 90 => 4];
            $options = [35 => 1, 36 => 2, 37 => 3];
            $n = 0;
            foreach ($query->queryAll() as $oldBoard) {
                $board = new Board([
                    'id' => $oldBoard['id'],
                    'author_id' => $oldBoard['userid'],
                    'name' => $oldBoard['name'],
                    //'slug' => $oldBoard['alias'],
                    'userSlug' => $oldBoard['alias'],
                    'category_id' => $oldBoard['category'],
                    'title' => $oldBoard['title'],
                    'description' => $oldBoard['meta_description'],
                    'keywords' => $oldBoard['meta_keywords'],
                    'note' => StringHelper::truncate($oldBoard['introtext'], 100, ''),
                    'price' => null,
                    'currency_id' => 1,
                    'price_from' => 0,
                    'full_text' => $oldBoard['fulltext'],
                    'term_id' => $terms[$oldBoard['period']],
                    'main_photo_id' => null,
                    'status' => $oldBoard['end_date'] > time() ? Board::STATUS_ACTIVE : Board::STATUS_ARCHIVE,
                    'views' => $oldBoard['hits'],
                    'active_until' => $oldBoard['end_date'],
                    'notification_date' => $oldBoard['end_date'] - 86400,
                    'created_at' => $oldBoard['datetime'],
                    'updated_at' => $oldBoard['updated'],
                ]);

                if (!$geoId = $this->getGeoId($oldBoard['geo'])) {
                    throw new \DomainException('Регион не найден в базе: id - ' . $oldBoard['geo']);
                }
                $board->geo_id = $geoId;

                $this->save($board);
                $board->updateAttributes([
                    'created_at' => $oldBoard['datetime'],
                    'updated_at' => $oldBoard['updated'],
                ]);

                $paramAssignment = new BoardParameterAssignment([
                    'board_id' => $board->id,
                    'parameter_id' => 1,
                    'option_id' => $options[$oldBoard['type']],
                    'value' => '',
                ]);
                $this->save($paramAssignment);

                // Импорт фотографий
                $addedPhotos = [];
                $photoSort = 0;
                foreach ($this->command->setSql('select * from `images` WHERE `type`=\'board\' AND `item_id`=' . $board->id . ' ORDER BY `id`')->queryAll() as $oldPhoto) {
                    $file = $oldPhoto['folder'] . 'max/' . $oldPhoto['name'];
                    if (\in_array($file, $addedPhotos, true)) {
                        continue;
                    }
                    $photo = new BoardPhoto([
                        'board_id' => $board->id,
                        'file' => $oldPhoto['folder'] . 'max/' . $oldPhoto['name'],
                        'sort' => $photoSort,
                    ]);

                    $addedPhotos[] = $file;
                    $this->save($photo);

                    if ($photoSort === 0) {
                        $board->updateAttributes(['main_photo_id' => $photo->id]);
                    }

                    $photoSort++;
                }

                $n++;
                echo 'Объявление "' . $board->name . '" добавлено.' . PHP_EOL;
            }

            $this->stdout('Импортировано ' . $n . ' объявлений.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    public function actionArticles(): void
    {
        $this->stdout('Стартуем импорт статей...' . PHP_EOL, Console::FG_YELLOW);
        $this->transaction->wrap(function () {
            ArticleCategory::deleteAll(['>', 'id', 1]);
            $root = ArticleCategory::find()->where(['id' => 1])->one();
            $sql = 'SELECT * FROM `sections` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $newCategories = [];
            $n = 0;
            foreach ($query->queryAll() as $oldCategory) {
                $category = new ArticleCategory([
                    'id' => $oldCategory['id'] + 1,
                    'name' => $oldCategory['name'],
                    'context_name' => '',
                    'enabled' => 1,
                    'not_show_on_main' => 0,
                    'children_only_parent' => 0,
                    'slug' => $oldCategory['url'],
                    'meta_title' => $oldCategory['meta_title'],
                    'meta_description' => $oldCategory['meta_description'],
                    'meta_keywords' => $oldCategory['meta_keywords'],
                    'title' => $oldCategory['name'],
                    'description_top' => $oldCategory['description_top'],
                    'description_top_on' => $oldCategory['description_top_on'],
                    'description_bottom' => $oldCategory['description_bottom'],
                    'description_bottom_on' => $oldCategory['description_bottom_on'],
                    'meta_title_item' => '',
                    'meta_description_item' => '',
                    'meta_title_other' => $oldCategory['meta_title_other'],
                    'meta_description_other' => '',
                    'meta_keywords_other' => $oldCategory['meta_keywords_other'],
                    'title_other' => '',
                    'pagination' => !$oldCategory['pagination'] ? PaginationHelper::PAGINATION_SCROLL : PaginationHelper::PAGINATION_NUMERIC,
                    'active' => 1,
                ]);

                if ($oldCategory['level'] === '0') {
                    $category->appendTo($root);
                } else {
                    if (!isset($newCategories[$oldCategory['parent']])) {
                        throw new \DomainException('Не найден родитель категории id:' . $category->id. ', родитель id:' . $oldCategory['parent']);
                    }
                    $category->appendTo($newCategories[$oldCategory['parent']]);
                }
                if (!$category->save()) {
                    throw new \RuntimeException('Ошибка записи в базу');
                }

                $newCategories[$category->id] = $category;
                $n++;
                echo 'Категория "' . $category->name . '" успешно добавлена.' . PHP_EOL;
            }
            $this->stdout('Импортировано ' . $n . ' категорий.' . PHP_EOL, Console::FG_GREEN);

            // Импорт самих статей
            Article::deleteAll();
            $sql = 'SELECT * FROM `articles` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $n = 0;
            foreach ($query->queryAll() as $oldArticle) {
                $oldArticle['category']++;
                $article = new Article([
                    'id' => $oldArticle['id'],
                    'user_id' => $oldArticle['user'],
                    'company_id' => null,
                    'category_id' => $oldArticle['category'],
                    'title' => $oldArticle['title'],
                    'meta_description' => $oldArticle['meta_description'],
                    'meta_keywords' => $oldArticle['meta_keywords'],
                    'name' => $oldArticle['name'],
                    'slug' => $oldArticle['url'],
                    'intro' => $oldArticle['introtext'],
                    'full_text' => $oldArticle['fulltext'],
                    'indirect_links' => 1,
                    'main_photo_id' => null,
                    'status' => Article::STATUS_ACTIVE,
                    'views' => $oldArticle['hits'],
                ]);
                $this->save($article);
                $article->updateAttributes([
                    'created_at' => Yii::$app->formatter->asTimestamp($oldArticle['date']),
                    'updated_at' => Yii::$app->formatter->asTimestamp($oldArticle['date']),
                ]);

                $this->importImages('articles', $article, ArticlePhoto::class);

                $n++;
            }
            $this->stdout('Импортировано ' . $n . ' статей.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    public function actionBrands(): void
    {
        $this->articlesImport('брендов', BrandCategory::class, 'brand_sections', Brand::class, 'brand', 'brand', BrandPhoto::class);
        /*$this->stdout('Стартуем импорт брендов...' . PHP_EOL, Console::FG_YELLOW);
        $this->transaction->wrap(function () {
            BrandCategory::deleteAll(['>', 'id', 1]);
            $root = BrandCategory::find()->where(['id' => 1])->one();
            $sql = 'SELECT * FROM `brand_sections` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $newCategories = [];
            $n = 0;
            foreach ($query->queryAll() as $oldCategory) {
                $category = new BrandCategory([
                    'id' => $oldCategory['id'],
                    'name' => $oldCategory['name'],
                    'context_name' => '',
                    'enabled' => 1,
                    'not_show_on_main' => 0,
                    'children_only_parent' => 0,
                    'slug' => $oldCategory['url'],
                    'meta_title' => $oldCategory['meta_title'],
                    'meta_description' => $oldCategory['meta_description'],
                    'meta_keywords' => $oldCategory['meta_keywords'],
                    'title' => $oldCategory['name2'],
                    'description_top' => $oldCategory['description_top'],
                    'description_top_on' => $oldCategory['description_top_on'],
                    'description_bottom' => $oldCategory['description_bottom'],
                    'description_bottom_on' => $oldCategory['description_bottom_on'],
                    'meta_title_item' => '',
                    'meta_description_item' => '',
                    'meta_title_other' => $oldCategory['meta_title_other'],
                    'meta_description_other' => '',
                    'meta_keywords_other' => $oldCategory['meta_keywords_other'],
                    'title_other' => '',
                    'pagination' => !$oldCategory['pagination'] ? PaginationHelper::PAGINATION_SCROLL : PaginationHelper::PAGINATION_NUMERIC,
                    'active' => 1,
                ]);

                if ($oldCategory['level'] === '0') {
                    $category->appendTo($root);
                } else {
                    if (!isset($newCategories[$oldCategory['parent']])) {
                        throw new \DomainException('Не найден родитель категории id:' . $category->id. ', родитель id:' . $oldCategory['parent']);
                    }
                    $category->appendTo($newCategories[$oldCategory['parent']]);
                }
                if (!$category->save()) {
                    throw new \RuntimeException('Ошибка записи в базу');
                }

                $newCategories[$category->id] = $category;
                $n++;
                echo 'Категория "' . $category->name . '" успешно добавлена.' . PHP_EOL;
            }
            $this->stdout('Импортировано ' . $n . ' категорий.' . PHP_EOL, Console::FG_GREEN);

            // Импорт самих статей
            Brand::deleteAll();
            $sql = 'SELECT * FROM `brand` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $n = 0;
            foreach ($query->queryAll() as $oldBrand) {
                $user = User::findOne($oldBrand['user']);
                $brand = new Brand([
                    'id' => $oldBrand['id'],
                    'user_id' => $oldBrand['user'],
                    'company_id' => $user->isCompany() ? $user->company->id : null,
                    'category_id' => $oldBrand['category'],
                    'title' => $oldBrand['title'],
                    'meta_description' => $oldBrand['meta_description'],
                    'meta_keywords' => $oldBrand['meta_keywords'],
                    'name' => $oldBrand['name'],
                    'slug' => $oldBrand['url'],
                    'intro' => $oldBrand['introtext'],
                    'full_text' => $oldBrand['fulltext'],
                    'indirect_links' => 1,
                    'main_photo_id' => null,
                    'status' => Brand::STATUS_ACTIVE,
                    'views' => $oldBrand['hits'],
                ]);
                $this->save($brand);
                $brand->updateAttributes([
                    'created_at' => Yii::$app->formatter->asTimestamp($oldBrand['date']),
                    'updated_at' => Yii::$app->formatter->asTimestamp($oldBrand['date']),
                ]);

                $this->importImages('brand', $brand, BrandPhoto::class);

                $n++;
            }
            $this->stdout('Импортировано ' . $n . ' брендов.' . PHP_EOL, Console::FG_GREEN);
        });
        */
    }

    public function actionNews(): void
    {
        $this->articlesImport('новостей', NewsCategory::class, 'news_sections', News::class, 'news', 'news', NewsPhoto::class);
        $this->articlesImport('новостей компаний', CNewsCategory::class, 'cnews_sections', CNews::class, 'cnews', 'cnews', CNewsPhoto::class);
    }

    public function actionExpos(): void
    {
        $this->articlesImport('выставок', ExpoCategory::class, 'expo_sections', Expo::class, 'expo', 'expo', ExpoPhoto::class);
    }

    public function actionTradeCategories(): void
    {
        $this->stdout('Стартуем импорт категорий товаров...' . PHP_EOL, Console::FG_YELLOW);
        $this->transaction->wrap(function () {
            TradeCategory::deleteAll(['>', 'id', 1]);
            $root = TradeCategory::find()->where(['id' => 1])->one();
            $sql = 'SELECT * FROM `trade_sections` ORDER BY `level`,`id`';
            $query = $this->command->setSql($sql);
            $newCategories = [];
            $n = 0;
            foreach ($query->queryAll() as $oldCategory) {
                $category = new TradeCategory([
                    'id' => $oldCategory['id'],
                    'name' => $oldCategory['name'],
                    'context_name' => '',
                    'enabled' => 1,
                    'not_show_on_main' => $oldCategory['not_publish_on_main'],
                    'children_only_parent' => 0,
                    'slug' => $oldCategory['url'],
                    'meta_title' => $oldCategory['meta_title'] ?: '',
                    'meta_description' => $oldCategory['meta_description'] ?: '',
                    'meta_keywords' => $oldCategory['meta_keywords'] ?: '',
                    'title' => $oldCategory['name2'],
                    'description_top' => $oldCategory['description_top'] ?: '',
                    'description_top_on' => $oldCategory['description_top_on'],
                    'description_bottom' => $oldCategory['description_bottom'] ?: '',
                    'description_bottom_on' => $oldCategory['description_bottom_on'],
                    'meta_title_item' => $oldCategory['meta_title_goods'] ?: '',
                    'meta_description_item' => $oldCategory['meta_description_goods'] ?: '',
                    'meta_title_other' => $oldCategory['meta_title_other'] ?: '',
                    'meta_description_other' => $oldCategory['meta_description_other'] ?: '',
                    'meta_keywords_other' => $oldCategory['meta_keywords_other'] ?: '',
                    'title_other' => $oldCategory['meta_title_name'] ?: '',
                    'pagination' => !$oldCategory['pagination'] ? PaginationHelper::PAGINATION_SCROLL : PaginationHelper::PAGINATION_NUMERIC,
                    'active' => $oldCategory['state'],
                ]);

                if ($oldCategory['level'] === '0') {
                    $category->appendTo($root);
                } else {
                    if (!isset($newCategories[$oldCategory['parent']])) {
                        throw new \DomainException('Не найден родитель категории id:' . $category->id. ', родитель id:' . $oldCategory['parent']);
                    }
                    $category->appendTo($newCategories[$oldCategory['parent']]);
                }
                if (!$category->save()) {
                    throw new \RuntimeException('Ошибка записи в базу');
                }

                foreach ($this->command->setSql('select * from `geo_trade` WHERE `section_id`=' . $category->id)->queryAll() as $oldCategoryRegion) {
                    if (!$geoId = $this->getGeoId($oldCategoryRegion['region_id'])) {
                        continue;
                    }
                    $categoryRegion = new TradeCategoryRegion([
                        'category_id' => $category->id,
                        'geo_id' => $geoId,
                        'meta_title' => $oldCategoryRegion['meta_title'],
                        'meta_description' => $oldCategoryRegion['meta_description'],
                        'meta_keywords' => $oldCategoryRegion['meta_keywords'],
                        'title' => $oldCategoryRegion['title'],
                        'description_top' => $oldCategoryRegion['description_top'],
                        'description_top_on' => $oldCategoryRegion['description_top_on'],
                        'description_bottom' => $oldCategoryRegion['description_bottom'],
                        'description_bottom_on' => $oldCategoryRegion['description_bottom_on'],
                    ]);
                    $this->save($categoryRegion);
                }

                $newCategories[$category->id] = $category;
                $n++;
                echo 'Категория "' . $category->name . '" успешно добавлена.' . PHP_EOL;
            }

            // Импорт пользовательских категорий
            TradeUserCategory::deleteAll();
            $sql = 'SELECT * FROM `trade_category` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $n2 = 0;
            foreach ($query->queryAll() as $oldUserCategory) {
                if (!TradeCategory::find()->where(['id' => $oldUserCategory['section']])->limit(1)->exists()) {
                    continue;
                }
                if (!User::find()->where(['id' => $oldUserCategory['user_id']])->limit(1)->exists()) {
                    continue;
                }

                $userCategory = new TradeUserCategory([
                    'id' => $oldUserCategory['id'],
                    'user_id' => $oldUserCategory['user_id'],
                    'name' => $oldUserCategory['name'],
                    'category_id' => $oldUserCategory['section'],
                    'uom_id' => $oldUserCategory['unit_measure'],
                    'currency_id' => $oldUserCategory['money_unit'] + 4,
                    'wholesale' => $oldUserCategory['wholesale'],
                ]);
                $this->save($userCategory);
                $n2++;
            }

            $this->stdout('Импортировано ' . $n . ' категорий и ' . $n2 . ' пользовательских категорий.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    public function actionTrades(): void
    {
        $this->stdout('Стартуем импорт товаров...' . PHP_EOL, Console::FG_YELLOW);
        $this->transaction->wrap(function () {
            Trade::deleteAll();
            $sql = 'SELECT * FROM `trade` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $n = 0;
            $addedUserCategories = [];
            foreach ($query->queryAll() as $oldTrade) {
                $user = User::findOne($oldTrade['userid']);

                // Вычисляем пользовательскую категорию. Если её нет, создаём.
                if (!$uc = TradeUserCategory::find()->where(['id' => $oldTrade['section']])->limit(1)->one()) {
                    if (!isset($addedUserCategories[$oldTrade['userid']])) {
                        $uc = new TradeUserCategory([
                            'user_id' => $oldTrade['userid'],
                            'name' => 'Автоматически сгенерированная категория',
                            'category_id' => 1,
                            'uom_id' => 1,
                            'currency_id' => 5,
                            'wholesale' => 1,
                        ]);
                        $this->save($uc);
                        $addedUserCategories[$oldTrade['userid']] = $uc;
                    } else {
                        $uc = $addedUserCategories[$oldTrade['userid']];
                    }
                }

                if (!$user->company) {
                    $company = new Company([
                        'user_id' => $user->id,
                        'form' => '',
                        'name' => $user->last_name,
                        'logo' => '',
                        'userSlug' => $user->last_name,
                        'site' => $user->site,
                        'geo_id' => $user->geo_id,
                        'address' => '',
                        'phones' => '[]',
                        'fax' => '',
                        'email' => $user->email,
                        'info' => '',
                        'title' => $user->last_name,
                        'short_description' => '',
                        'description' => '',
                        'main_photo_id' => null,
                        'status' => Company::STATUS_ACTIVE,
                        'views' => 0,
                    ]);
                    $company->setPhones([$user->phone]);
                    $this->save($company);
                }

                $trade = new Trade([
                    'id' => $oldTrade['id'],
                    'user_id' => $oldTrade['userid'],
                    'company_id' => $user->company->id,
                    'category_id' => $uc->category_id,//TradeUserCategory::find()->select('category_id')->where(['id' => $oldTrade['section']])->limit(1)->scalar(),//$this->command->setSql('SELECT `category_id` FROM `sections_trade_items` WHERE `item_id`=' . $oldTrade['id'])->queryScalar(),
                    'user_category_id' => $uc->id,
                    'geo_id' => $this->getGeoId($oldTrade['geo']),
                    'name' => $oldTrade['name'],
                    'meta_title' => $oldTrade['meta_title'] ?: '',
                    'meta_description' => $oldTrade['meta_description'] ?: '',
                    'meta_keywords' => $oldTrade['meta_keywords'] ?: '',
                    'slug' => $oldTrade['url'],
                    'code' => $oldTrade['scu'],
                    'price' => $oldTrade['price'] ? PriceHelper::optimize($oldTrade['price']) : null,
                    'wholesale_prices' => '[]',
                    'stock' => $oldTrade['presence'],
                    'external_link' => '',
                    'note' => StringHelper::truncate($oldTrade['introtext'], 77),
                    'description' => $oldTrade['fulltext'],
                    'main_photo_id' => null,
                    'status' => Trade::STATUS_ACTIVE,
                    'views' => $oldTrade['hits'],
                ]);

                $oldWholesales = [];
                $newWholesales = [];
                $oldWholesales[] = ['price' => $oldTrade['wholesale_price1'], 'from' => $oldTrade['wholesale_pcs1']];
                $oldWholesales[] = ['price' => $oldTrade['wholesale_price2'], 'from' => $oldTrade['wholesale_pcs2']];
                $oldWholesales[] = ['price' => $oldTrade['wholesale_price3'], 'from' => $oldTrade['wholesale_pcs3']];
                foreach ($oldWholesales as $i => $wholesale) {
                    if ((float) $wholesale['price'] && $wholesale['from']) {
                        $newWholesales[] = ['price' => PriceHelper::optimize($wholesale['price']),'from' => (int) $wholesale['from']];
                    }
                }
                $trade->setWholesales($newWholesales);

                if ($oldTrade['trade_url'] && $oldTrade['trade_url'] !== 'нет') {
                    $trade->external_link = strpos($oldTrade['trade_url'], '://') === false ? 'http://' . $oldTrade['trade_url'] : $oldTrade['trade_url'];
                }

                $this->save($trade);
                $trade->updateAttributes([
                    'created_at' => $oldTrade['date'],
                    'updated_at' => Yii::$app->formatter->asTimestamp($oldTrade['updated']),
                ]);

                $this->importImages('trade', $trade, TradePhoto::class);

                $n++;
                echo 'Товар "' . $trade->name . '" добавлен.' . PHP_EOL;
            }

            $this->stdout('Импортировано ' . $n . ' товаров.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    public function actionSupportMessages(): void
    {
        $this->stdout('Стартуем импорт сообщений в службу поддержки...' . PHP_EOL, Console::FG_YELLOW);
        $this->transaction->wrap(function () {
            SupportDialog::deleteAll();
            $sql = 'SELECT * FROM `subject` WHERE `to`=1 OR `from`=1';
            foreach ($this->command->setSql($sql)->queryAll() as $subject) {
                if ($subject['from'] && !User::find()->where(['id' => $subject['from']])->limit(1)->exists()) {
                    echo 'Пропущен диалог: id' . $subject['id'] . PHP_EOL;
                    continue;
                }
                if ($subject['to'] && !User::find()->where(['id' => $subject['to']])->limit(1)->exists()) {
                    echo 'Пропущен диалог: id' . $subject['id'] . PHP_EOL;
                    continue;
                }
                $dialog = new SupportDialog([
                    'user_id' => $subject['from'] ?: null,
                    'subject' => strip_tags(trim(str_replace('(Служба поддержки)', '', $subject['subject']))),
                    'name' => $subject['from_name'] ?: '',
                    'phone' => $subject['phone'] ?: '',
                    'email' => $subject['email'] ?: '',
                    'status' => SupportDialog::STATUS_ACTIVE,
                    'text' => strip_tags(trim($subject['text'])),
                    'date' => Yii::$app->formatter->asTimestamp($subject['date']),
                ]);
                $this->save($dialog);

                /*$message = new SupportMessage([
                    'dialog_id' => $dialog->id,
                    'user_id' => $dialog->user_id,
                    'text' => strip_tags($subject['text']),
                    'status' => 1,
                ]);
                $this->save($message);
                $message->updateAttributes([
                    'created_at' => Yii::$app->formatter->asTimestamp($subject['date']),
                    'updated_at' => Yii::$app->formatter->asTimestamp($subject['date']),
                ]);*/

                // Импорт сообщений
                $sql = 'SELECT * FROM `messages` WHERE `subject_id`=' . $subject['id']. ' ORDER BY `id`';
                $n = 0;
                foreach ($this->command->setSql($sql)->queryAll() as $oldMessage) {
                    if (!\in_array($oldMessage['user_id'], [1, $dialog->user_id])) {
                        continue;
                    }
                    $message = new SupportMessage([
                        'dialog_id' => $dialog->id,
                        'user_id' => $oldMessage['user_id'],
                        'text' => strip_tags(trim($oldMessage['text'])),
                        'status' => 1,
                    ]);
                    $this->save($message);
                    $message->updateAttributes([
                        'created_at' => Yii::$app->formatter->asTimestamp($oldMessage['date']),
                        'updated_at' => Yii::$app->formatter->asTimestamp($oldMessage['date']),
                    ]);
                    $n++;
                }
            }
        });
        $this->stdout('Импорт сообщений в службу поддержки завершён.' . PHP_EOL, Console::FG_GREEN);
    }

    public function actionDialogs(): void
    {
        $this->stdout('Стартуем импорт сообщений...' . PHP_EOL, Console::FG_YELLOW);
        $this->transaction->wrap(function () {
            Dialog::deleteAll();
            $sql = 'SELECT * FROM `subject` WHERE `to`>1';
            $n = 0;
            foreach ($this->command->setSql($sql)->queryAll() as $subject) {
                if ($subject['to'] && !User::find()->where(['id' => $subject['to']])->limit(1)->exists()) {
                    echo 'Пропущен диалог: id' . $subject['id'] . ', to: ' . $subject['to'] . PHP_EOL;
                    continue;
                }
                if ($subject['from'] && !User::find()->where(['id' => $subject['from']])->limit(1)->exists()) {
                    echo 'Пропущен диалог: id' . $subject['id'] . ', from: ' . $subject['from'] . PHP_EOL;
                    continue;
                }
                $dialog = new Dialog([
                    'user_from' => $subject['from'] ?: null,
                    'user_to' => $subject['to'],
                    'subject' => strip_tags(trim($subject['subject'])),
                    'name' => $subject['from_name'] ?: '',
                    'phone' => $subject['phone'] ?: '',
                    'email' => $subject['email'] ?: '',
                    'status' => Dialog::STATUS_ACTIVE,
                    'text' => strip_tags(trim($subject['text'])),
                    'date' => Yii::$app->formatter->asTimestamp($subject['date']),
                ]);
                $this->save($dialog);

                /*$message = new Message([
                    'dialog_id' => $dialog->id,
                    'user_id' => $dialog->user_from,
                    'text' => strip_tags($subject['text']),
                    'status' => 1,
                ]);
                $this->save($message);
                $message->updateAttributes([
                    'created_at' => Yii::$app->formatter->asTimestamp($subject['date']),
                    'updated_at' => Yii::$app->formatter->asTimestamp($subject['date']),
                ]);*/

                // Импорт сообщений
                $sql = 'SELECT * FROM `messages` WHERE `subject_id`=' . $subject['id']. ' ORDER BY `id`';
                foreach ($this->command->setSql($sql)->queryAll() as $oldMessage) {
                    if (!\in_array($oldMessage['user_id'], [$dialog->user_to, $dialog->user_from])) {
                        continue;
                    }
                    $message = new Message([
                        'dialog_id' => $dialog->id,
                        'user_id' => $oldMessage['user_id'],
                        'text' => strip_tags(trim($oldMessage['text'])),
                        'status' => 1,
                    ]);
                    $this->save($message);
                    $message->updateAttributes([
                        'created_at' => Yii::$app->formatter->asTimestamp($oldMessage['date']),
                        'updated_at' => Yii::$app->formatter->asTimestamp($oldMessage['date']),
                    ]);
                    $n++;
                }
            }
            $this->stdout('Импортировано ' . $n . ' сообщений.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    public function actionOrders(): void
    {
        $this->stdout('Стартуем импорт заказов...' . PHP_EOL, Console::FG_YELLOW);
        $this->transaction->wrap(function () {
            UserOrder::deleteAll();
            $sql = 'SELECT * FROM `orders` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $n = 0;
            foreach ($query->queryAll() as $oldOrder) {
                $text = $oldOrder['text'];
                preg_match('/^.+ <a href="\/trade\/([0-9]+)-.+Количество: <b>([0-9]+)<\/b>.+Комментарий: ([^<]*)<br\/><br\/>Имя: <b>([^<]*)<\/b>.*Телефон: <b>([^<]*)<\/b>.*E-mail: <b>([^<]*)<\/b>/uis', $text, $out);
                //print_r($out);
                //echo PHP_EOL;
                //break;
                $tradeId = $out[1];
                $amount = $out[2];
                $comment = $out[3];
                $name = $out[4];
                $phone = $out[5];
                $email = $out[6];

                if (!Trade::find()->where(['id' => $tradeId])->limit(1)->exists()) {
                    echo 'Товар id ' . $tradeId . ' пропущен(не найден)' . PHP_EOL;
                    continue;
                }

                if (!$forUser = User::find()->where(['id' => $oldOrder['to']])->limit(1)->one()) {
                    echo 'Пользователь с id ' . $oldOrder['to'] . ' пропущен(не найден)' . PHP_EOL;
                    continue;
                }

                if (!$forUser->company) {
                    echo 'Компания пользователя не найдена. Id: ' . $forUser->id . PHP_EOL;
                    continue;
                }

                $fromUserId = User::find()->where(['id' => $oldOrder['from']])->limit(1)->exists() ? $oldOrder['from'] : null;
                $userOrder = UserOrder::create($fromUserId, $name, $phone, $email, '');
                $userOrder->status = Order::STATUS_SENT;
                $this->save($userOrder);
                $userOrder->updateAttributes([
                    'created_at' => Yii::$app->formatter->asTimestamp($oldOrder['date']),
                    'updated_at' => Yii::$app->formatter->asTimestamp($oldOrder['date']),
                ]);

                $order = Order::create($userOrder->id, $forUser->company->id, $fromUserId, null, $comment);
                $order->status = Order::STATUS_SENT;
                $this->save($order);
                $userOrder->updateAttributes([
                    'created_at' => Yii::$app->formatter->asTimestamp($oldOrder['date']),
                    'updated_at' => Yii::$app->formatter->asTimestamp($oldOrder['date']),
                ]);

                $orderItem = OrderItem::create($order->id, $tradeId, $amount);
                $this->save($orderItem);
                $n++;
            }
            $this->stdout('Импортировано ' . $n . ' заказов.' . PHP_EOL, Console::FG_GREEN);
        });
    }

    private function getGeoId($oldGeoId): ?int
    {
        $regionName = $this->command->setSql('select name from geo where id=' . $oldGeoId)->queryScalar();
        if ($regionName && ($geoId = Geo::find()->select('id')->where(['name' => $regionName])->scalar())) {
            return $geoId;
        }
        return null;
    }

    private function save(ActiveRecord $entity): void
    {
        if (!$entity->save()) {
            throw new \RuntimeException('Ошибка записи в базу');
        }
    }

    private function importImages($type, ActiveRecord $entity, $photoClass): void
    {
        $addedPhotos = [];
        $photoSort = 0;
        foreach ($this->command->setSql('select * from `images` WHERE `type`=\'' . $type . '\' AND `item_id`=' . $entity->id . ' ORDER BY `id`')->queryAll() as $oldPhoto) {
            $file = $oldPhoto['folder'] . 'max/' . $oldPhoto['name'];
            if (\in_array($file, $addedPhotos, true)) {
                continue;
            }
            /* @var $photo PhotoInterface */
            $photo = new $photoClass([
                //'board_id' => $entity->id,
                'file' => $oldPhoto['folder'] . 'max/' . $oldPhoto['name'],
                'sort' => $photoSort,
            ]);
            $relationAttribute = $photo::getRelationAttribute();
            $photo->$relationAttribute = $entity->id;

            $addedPhotos[] = $file;
            $this->save($photo);

            if ($photoSort === 0) {
                $entity->updateAttributes(['main_photo_id' => $photo->id]);
            }

            $photoSort++;
        }
    }

    private function articlesImport($name, $categoryClass, $oldCategoryTable, $entityClass, $oldTable, $photoFolder, $photoClass): void
    {
        $this->stdout('Стартуем импорт ' . $name . '...' . PHP_EOL, Console::FG_YELLOW);

        /* @var $categoryClass CategoryInterface */
        $this->transaction->wrap(function () use ($categoryClass, $oldCategoryTable, $entityClass, $oldTable, $photoFolder, $photoClass, $name) {
            $categoryClass::deleteAll(['>', 'id', 1]);
            $root = $categoryClass::find()->where(['id' => 1])->one();
            $sql = 'SELECT * FROM `' . $oldCategoryTable . '` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $newCategories = [];
            $n = 0;
            foreach ($query->queryAll() as $oldCategory) {
                $category = new $categoryClass([
                    'id' => $oldCategory['id'],
                    'name' => $oldCategory['name'],
                    'context_name' => '',
                    'enabled' => 1,
                    'not_show_on_main' => 0,
                    'children_only_parent' => 0,
                    'slug' => $oldCategory['url'],
                    'meta_title' => $oldCategory['meta_title'],
                    'meta_description' => $oldCategory['meta_description'],
                    'meta_keywords' => $oldCategory['meta_keywords'],
                    'title' => $oldCategory['name2'],
                    'description_top' => $oldCategory['description_top'],
                    'description_top_on' => $oldCategory['description_top_on'],
                    'description_bottom' => $oldCategory['description_bottom'],
                    'description_bottom_on' => $oldCategory['description_bottom_on'],
                    'meta_title_item' => $oldCategory['meta_title_articles'],
                    'meta_description_item' => $oldCategory['meta_description_articles'],
                    'meta_title_other' => $oldCategory['meta_title_other'],
                    'meta_description_other' => '',
                    'meta_keywords_other' => $oldCategory['meta_keywords_other'],
                    'title_other' => '',
                    'pagination' => !$oldCategory['pagination'] ? PaginationHelper::PAGINATION_SCROLL : PaginationHelper::PAGINATION_NUMERIC,
                    'active' => 1,
                ]);

                if ($oldCategory['level'] === '0') {
                    $category->appendTo($root);
                } else {
                    if (!isset($newCategories[$oldCategory['parent']])) {
                        throw new \DomainException('Не найден родитель категории id:' . $category->id. ', родитель id:' . $oldCategory['parent']);
                    }
                    $category->appendTo($newCategories[$oldCategory['parent']]);
                }
                if (!$category->save()) {
                    throw new \RuntimeException('Ошибка записи в базу');
                }

                $newCategories[$category->id] = $category;
                $n++;
                echo 'Категория "' . $category->name . '" успешно добавлена.' . PHP_EOL;
            }
            $this->stdout('Импортировано ' . $n . ' категорий.' . PHP_EOL, Console::FG_GREEN);

            // Импорт самих статей
            /* @var $entityClass Article|Brand|Expo|News|CNews */
            $entityClass::deleteAll();
            $sql = 'SELECT * FROM `' . $oldTable . '` ORDER BY `id`';
            $query = $this->command->setSql($sql);
            $n = 0;
            foreach ($query->queryAll() as $oldEntity) {
                $user = User::findOne($oldEntity['user']);
                $entity = new $entityClass([
                    'id' => $oldEntity['id'],
                    'user_id' => $oldEntity['user'],
                    'company_id' => $user->isCompany() ? $user->company->id : null,
                    'category_id' => $oldEntity['category'] ?: 1,
                    'title' => $oldEntity['title'],
                    'meta_description' => $oldEntity['meta_description'] ?: '',
                    'meta_keywords' => $oldEntity['meta_keywords'] ?: '',
                    'name' => $oldEntity['name'] ?: '',
                    'slug' => $oldEntity['url'],
                    'intro' => $oldEntity['introtext'] ?: '',
                    'full_text' => $oldEntity['fulltext'] ?: '',
                    'indirect_links' => 1,
                    'main_photo_id' => null,
                    'status' => $entityClass::STATUS_ACTIVE,
                    'views' => $oldEntity['hits'],
                ]);

                if ($oldTable === 'expo') {
                    $entity->show_dates = 1;
                    $entity->start_date = Yii::$app->formatter->asTimestamp($oldEntity['date_with']);
                    $entity->end_date = Yii::$app->formatter->asTimestamp($oldEntity['date_on']);
                }

                $this->save($entity);
                $entity->updateAttributes([
                    'created_at' => Yii::$app->formatter->asTimestamp($oldEntity['date']),
                    'updated_at' => Yii::$app->formatter->asTimestamp($oldEntity['date']),
                ]);

                $this->importImages($photoFolder, $entity, $photoClass);

                $n++;
            }
            $this->stdout('Импортировано ' . $n . ' ' . $name . PHP_EOL, Console::FG_GREEN);
        });
    }
}