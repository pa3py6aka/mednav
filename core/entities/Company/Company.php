<?php

namespace core\entities\Company;

use core\behaviors\SluggableBehavior;
use core\components\ContentBlocks\ContentBlockInterface;
use core\entities\Article\Article;
use core\entities\Board\Board;
use core\entities\CNews\CNews;
use core\entities\Company\queries\CompanyQuery;
use core\entities\Geo;
use core\entities\StatusesInterface;
use core\entities\StatusesTrait;
use core\entities\Trade\Trade;
use core\entities\User\User;
use core\entities\UserOwnerInterface;
use core\helpers\FileHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%companies}}".
 *
 * @property int $id
 * @property int $user_id [int(11)]
 * @property string $form
 * @property string $name
 * @property string $logo
 * @property string $slug
 * @property string $site
 * @property int $geo_id
 * @property string $address
 * @property string $phones
 * @property string $fax
 * @property string $email
 * @property string $info
 * @property string $title
 * @property string $short_description
 * @property string $description
 * @property int $main_photo_id
 * @property int $views [int(11) unsigned]
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $phone
 *
 * @property Geo $geo
 * @property CompanyPhoto $mainPhoto
 * @property CompanyPhoto[] $photos
 * @property CompanyTagsAssignment[] $companyTagsAssignments
 * @property CompanyTag[] $tags
 * @property User $user
 * @property CompanyCategoryAssignment[] $companyCategoryAssignments
 * @property CompanyCategory[] $categories
 * @property Board[] $boards
 * @property CNews[] $cNews
 * @property CompanyDelivery[] $deliveries
 * @property CompanyDeliveryRegion[] $deliveryRegions
 * @property Trade[] $trades
 * @property Article[] $articles
 */
class Company extends ActiveRecord implements StatusesInterface, UserOwnerInterface, ContentBlockInterface
{
    use StatusesTrait;

    public $userSlug;

    public static function create(
        $userId,
        $form,
        $name,
        $site,
        $geoId,
        $address,
        $phones,
        $fax,
        $email,
        $info,
        $title,
        $shortDescription,
        $description,
        $status,
        $slug
    ): Company
    {
        $company = new self();
        $company->user_id = $userId;
        $company->form = $form;
        $company->name = $name;
        $company->site = $site;
        $company->geo_id = $geoId;
        $company->address = $address;
        $company->setPhones($phones);
        $company->fax = $fax;
        $company->email = $email;
        $company->info = $info;
        $company->title = $title;
        $company->short_description = $shortDescription;
        $company->description = $description;
        $company->setStatus($status);
        $company->userSlug = $slug;
        //$company->delivery = '[]';
        return $company;
    }

    public function edit(
        $userId,
        $form,
        $name,
        $site,
        $geoId,
        $address,
        $phones,
        $fax,
        $email,
        $info,
        $title,
        $shortDescription,
        $description,
        $slug
    ): void
    {
        $this->user_id = $userId;
        $this->form = $form;
        $this->name = $name;
        $this->site = $site;
        $this->geo_id = $geoId;
        $this->address = $address;
        $this->setPhones($phones);
        $this->fax = $fax;
        $this->email = $email;
        $this->info = $info;
        $this->title = $title;
        $this->short_description = $shortDescription;
        $this->description = $description;
        $this->userSlug = $slug;
    }

    /**
     * @param bool $asString
     * @return array|string
     */
    public function getPhones($asString = false)
    {
        $phones = $this->phones ? Json::decode($this->phones) : [];
        return $asString ? Html::encode(implode(', ', $phones)) : $phones;
    }

    public function getPhone(): string
    {
        $phones = $this->getPhones();
        return $phones ? current($phones) : '';
    }

    private function setPhones($phones): void
    {
        if (!is_array($phones)) {
            $phones = (array) $phones;
        }
        $phones = array_diff($phones, ['']);
        $this->phones = Json::encode($phones);
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getUrl($page = null, $absolute = false, $params = []): string
    {
        $urlArray = array_merge(['/company/company/' . ($page ?: 'view'), 'slug' => $this->slug, 'id' => $this->id], $params);
        return ($absolute ? Yii::$app->params['frontendHostInfo'] : '')
            . Url::to($urlArray);
    }

    public function getLogoUrl($absolute = false, $type = 'small'): string
    {
        return ($absolute ? Yii::$app->params['frontendHostInfo'] : '')
            . ($this->logo ? '/i/company/lg/' . $type . '_' . $this->logo
                : '/img/no-photo-250.jpg');
    }

    public function logoPath(): string
    {
        return Yii::getAlias('@frontend/web/i/company/lg');
    }

    public function getTitle()
    {
        return $this->title ?: Html::encode($this->name);
    }

    public function getMainPhotoUrl($type = 'small', $absolute = false)
    {
        return $this->main_photo_id ?
            $this->mainPhoto->getUrl($type, $absolute)
            : ($absolute ? Yii::$app->params['frontendHostInfo'] : '') . '/img/no-photo-250.jpg';
    }

    public function getFullName(): string
    {
        return Html::encode(trim($this->form . ' ' . $this->name));
    }

    public function getTagsString(): string
    {
        return implode(', ', $this->getTags()->select('name')->column());
    }

    private $boardsCount = null;
    private $tradesCount = null;
    private $articlesCount = null;
    private $cNewsCount = null;
    public function getCountFor(string $module): int
    {
        switch ($module) {
            case 'boards':
                $this->boardsCount = $this->boardsCount === null ? $this->getBoards()->count() : $this->boardsCount;
                return $this->boardsCount;
            case 'trades':
                $this->tradesCount = $this->tradesCount === null ? $this->getTrades()->count() : $this->tradesCount;
                return $this->tradesCount;
            case 'articles':
                $this->articlesCount = $this->articlesCount === null ? $this->getArticles()->count() : $this->articlesCount;
                return $this->articlesCount;
            case 'cnews':
                $this->cNewsCount = $this->cNewsCount === null ? $this->getCNews()->count() : $this->cNewsCount;
                return $this->cNewsCount;
            default: return 0;
        }
    }

    public function afterDelete()
    {
        if ($this->logo && is_file($this->logoPath() . '/' . $this->logo)) {
            FileHelper::unlink($this->logoPath() . '/' . $this->logo);
        }
        parent::afterDelete();
    }

    public static function tableName(): string
    {
        return '{{%companies}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            'slug' => [
                'class' => SluggableBehavior::class,
                'slugAttribute' => 'slug',
                'attribute' => 'userSlug',
                'ensureUnique' => true,
                //'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ]
        ];
    }

    public function getOwnerId(): int
    {
        return $this->user_id;
    }

    public function getOwnerUser(): User
    {
        return $this->user;
    }

    public function getVendInfo()
    {
        return '';
    }

    public function getFullPriceString(): string
    {
        return '';
    }

    public function getContentDescription() : string
    {
        return Html::encode($this->short_description);
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['form', 'name', 'slug', 'geo_id', 'address', 'email', 'title', 'description', 'created_at', 'updated_at'], 'required'],
            [['geo_id', 'main_photo_id', 'created_at', 'updated_at'], 'integer'],
            [['info', 'short_description', 'description'], 'string'],
            [['form', 'fax'], 'string', 'max' => 50],
            [['name', 'logo', 'slug', 'site', 'address', 'phones', 'email', 'title'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['geo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Geo::className(), 'targetAttribute' => ['geo_id' => 'id']],
            [['main_photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyPhotos::className(), 'targetAttribute' => ['main_photo_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'form' => 'Форма',
            'name' => 'Название',
            'logo' => 'Логотип',
            'slug' => 'Slug',
            'site' => 'Сайт',
            'geo_id' => 'Регион',
            'address' => 'Адрес',
            'phones' => 'Телефоны',
            'fax' => 'Факс',
            'email' => 'Email',
            'info' => 'Информация',
            'title' => 'Заголовок',
            'short_description' => 'Краткое описание',
            'description' => 'Описание',
            'main_photo_id' => 'Главное фото',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getGeo(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'geo_id']);
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(CompanyPhoto::class, ['company_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(CompanyPhoto::class, ['id' => 'main_photo_id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getCompanyTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(CompanyTagsAssignment::class, ['company_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(CompanyTag::class, ['id' => 'tag_id'])
            ->viaTable('{{%company_tags_assignment}}', ['company_id' => 'id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getCompanyCategoriesAssignments(): ActiveQuery
    {
        return $this->hasMany(CompanyCategoryAssignment::class, ['company_id' => 'id']);
    }

    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(CompanyCategory::class, ['id' => 'category_id'])
            ->viaTable('{{%company_categories_assignment}}', ['company_id' => 'id']);
    }

    public function getBoards($active = true): ActiveQuery
    {
        $query = $this->hasMany(Board::class, ['author_id' => 'user_id']);
        return $active ? $query->active() : $query;
    }

    public function getDeliveries(): ActiveQuery
    {
        return $this->hasMany(CompanyDelivery::class, ['company_id' => 'id']);
    }

    public function getDeliveryRegions(): ActiveQuery
    {
        return $this->hasMany(CompanyDeliveryRegion::class, ['company_id' => 'id']);
    }

    public function getTrades($active = true): ActiveQuery
    {
        $query = $this->hasMany(Trade::class, ['company_id' => 'id']);
        return $active ? $query->active() : $query;
    }

    public function getArticles($active = true): ActiveQuery
    {
        $query = $this->hasMany(Article::class, ['company_id' => 'id']);
        return $active ? $query->active() : $query;
    }

    public function getCNews($active = true): ActiveQuery
    {
        $query = $this->hasMany(CNews::class, ['company_id' => 'id']);
        return $active ? $query->active() : $query;
    }

    /**
     * {@inheritdoc}
     * @return CompanyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompanyQuery(get_called_class());
    }
}
