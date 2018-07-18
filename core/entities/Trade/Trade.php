<?php

namespace core\entities\Trade;

use core\entities\Currency;
use core\entities\Geo;
use core\entities\StatusesInterface;
use core\entities\StatusesTrait;
use core\entities\Trade\queries\TradeQuery;
use core\entities\User\User;
use core\entities\UserOwnerInterface;
use core\helpers\PriceHelper;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%trades}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id [int(11)]
 * @property int $user_category_id [int(11)]
 * @property int $geo_id [int(11)]
 * @property string $name
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $slug
 * @property string $code
 * @property int $price
 * @property string $wholesale_prices
 * @property int $stock
 * @property string $note
 * @property string $description
 * @property int $main_photo_id
 * @property int $status [tinyint(3)]
 * @property string $views
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $priceString
 *
 * @property Geo $geo
 * @property TradeCategory $category
 * @property TradeUserCategory $userCategory
 * @property TradePhoto[] $photos
 * @property TradePhoto $mainPhoto
 * @property TradeTagAssignment[] $tagsAssignments
 * @property TradeTag[] $tags
 * @property User $user
 */
class Trade extends ActiveRecord implements StatusesInterface, UserOwnerInterface
{
    use StatusesTrait;

    public $userSlug;

    public static function create(
        $userId,
        $categoryId,
        $userCategoryId,
        $geoId,
        $name,
        $metaTitle,
        $metaDescription,
        $metaKeywords,
        $slug,
        $code,
        $price,
        $stock,
        $note,
        $description,
        $status
    ): Trade
    {
        $trade = new self();
        $trade->user_id = $userId;
        $trade->category_id = $categoryId;
        $trade->user_category_id = $userCategoryId;
        $trade->geo_id = $geoId;
        $trade->name = $name;
        $trade->meta_title = $metaTitle;
        $trade->meta_description = $metaDescription;
        $trade->meta_keywords = $metaKeywords;
        $trade->user_id = $userId;
        $trade->userSlug = $slug;
        $trade->code = $code;
        $trade->price = $price ? PriceHelper::optimize($price) : null;
        $trade->stock = $stock;
        $trade->note = $note;
        $trade->description = $description;
        $trade->setStatus($status);
        $trade->views = 0;
        return $trade;
    }

    public function edit(
        $userId,
        $categoryId,
        $userCategoryId,
        $geoId,
        $name,
        $metaTitle,
        $metaDescription,
        $metaKeywords,
        $slug,
        $code,
        $price,
        $stock,
        $note,
        $description
    ): void
    {
        $this->user_id = $userId;
        $this->category_id = $categoryId;
        $this->user_category_id = $userCategoryId;
        $this->geo_id = $geoId;
        $this->name = $name;
        $this->meta_title = $metaTitle;
        $this->meta_description = $metaDescription;
        $this->meta_keywords = $metaKeywords;
        $this->user_id = $userId;
        $this->userSlug = $slug;
        $this->code = $code;
        $this->price = $price ? PriceHelper::optimize($price) : null;
        $this->stock = $stock;
        $this->note = $note;
        $this->description = $description;
    }

    /**
     * @return array Массив вида [['currency' => 100, 'from' => 10], ... ]
     */
    public function getWholesales(): array
    {
        return Json::decode($this->wholesale_prices);
    }

    public function setWholesales(array $wholesales): void
    {
        $this->wholesale_prices = Json::encode($wholesales);
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getStockString(): string
    {
        return $this->stock ? "В наличии" : "Под заказ";
    }

    public function getPriceString(): string
    {
        return PriceHelper::normalize($this->price) . ' ' . $this->userCategory->currency->sign;
    }

    public function getUrl(): string
    {
        return Url::to(['/trade/trade/view', 'slug' => $this->slug, 'id' => $this->id]);
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        // Удаляем изображения товара
        if ($this->photos) {
            foreach ($this->photos as $photo) {
                $photo->removePhotos();
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%trades}}';
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

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['user_id', 'name', 'meta_description', 'slug', 'currency_id', 'wholesale_prices', 'description', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'price', 'currency_id', 'stock', 'main_photo_id', 'views', 'created_at', 'updated_at'], 'integer'],
            [['meta_description', 'wholesale_prices', 'description'], 'string'],
            [['name', 'meta_title', 'meta_keywords', 'slug', 'code', 'url'], 'string', 'max' => 255],
            [['note'], 'string', 'max' => 80],
            [['slug'], 'unique'],
            [['main_photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TradePhotos::className(), 'targetAttribute' => ['main_photo_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'category_id' => 'Раздел',
            'user_category_id' => 'Категория',
            'name' => 'Наименование',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'slug' => 'Slug',
            'code' => 'Артикул',
            'price' => 'Цена',
            'currency_id' => 'Валюта',
            'wholesale_prices' => 'Оптовые цены',
            'stock' => 'В наличии',
            'note' => 'Уточнение',
            'description' => 'Описание',
            'main_photo_id' => 'Главное фото',
            'views' => 'Просмотров',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обовления',
        ];
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(TradePhoto::class, ['trade_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(TradePhoto::class, ['id' => 'main_photo_id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(TradeCategory::class, ['id' => 'category_id']);
    }

    public function getUserCategory(): ActiveQuery
    {
        return $this->hasOne(TradeUserCategory::class, ['id' => 'user_category_id']);
    }

    public function getTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(TradeTagAssignment::class, ['trade_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(TradeTag::class, ['id' => 'tag_id'])->viaTable('{{%trade_tags_assignment}}', ['trade_id' => 'id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getGeo(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'geo_id']);
    }

    /**
     * {@inheritdoc}
     * @return TradeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TradeQuery(get_called_class());
    }

    public function getOwnerId(): int
    {
        return $this->user_id;
    }
}
