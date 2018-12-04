<?php

namespace core\entities\Board;

use core\behaviors\SluggableBehavior;
use core\components\ContentBlocks\ContentBlockInterface;
use core\entities\Board\queries\BoardQuery;
use core\entities\CategoryAssignmentInterface;
use core\entities\Currency;
use core\entities\Geo;
use core\entities\SearchInterface;
use core\entities\User\User;
use core\entities\UserOwnerInterface;
use core\helpers\PriceHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%boards}}".
 *
 * @property int $id [int(11)]
 * @property int $author_id [int(11)]
 * @property string $name
 * @property string $slug
 * @property int $category_id [int(11)]
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $note
 * @property int $price
 * @property int $currency_id
 * @property int $price_from
 * @property string $full_text
 * @property int $term_id [int(11)]
 * @property int $geo_id [int(11)]
 * @property int $main_photo_id [int(11)]
 * @property int $status
 * @property int $views [int(11)]
 * @property int $active_until [int(11)]
 * @property int $notification_date [int(11)]
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 *
 * @property string $priceString
 * @property string $statusName
 * @property string|null $defaultType
 *
 * @property BoardParameterAssignment[] $boardParameters
 * @property BoardParameterAssignment $typeBoardParameter
 * @property BoardTagAssignment[] $tagsAssignments
 * @property BoardTag[] $tags
 * @property User $author
 * @property BoardCategory $category
 * @property Geo $geo
 * @property BoardTerm $term
 * @property Currency $currency
 * @property BoardPhoto[] $photos
 * @property BoardPhoto $mainPhoto
 */
class Board extends ActiveRecord implements UserOwnerInterface, ContentBlockInterface, CategoryAssignmentInterface, SearchInterface
{
    public const STATUS_DELETED = 0;
    public const STATUS_ON_MODERATION = 1;
    public const STATUS_NOT_ACTIVE = 3;
    public const STATUS_ACTIVE = 5;
    public const STATUS_ARCHIVE = 8;

    public $userSlug;

    public static function create
    (
        $authorId,
        $name,
        $slug,
        $categoryId,
        $title,
        $description,
        $keywords,
        $note,
        $price,
        $currencyId,
        $priceFrom,
        $fullText,
        $termId,
        $geoId,
        $status
    ): Board
    {
        $board = new static();
        $board->author_id = $authorId;
        $board->name = $name;
        $board->userSlug = $slug;
        $board->category_id = $categoryId;
        $board->title = $title;
        $board->description = $description;
        $board->keywords = $keywords;
        $board->note = $note;
        $board->price = $price ? PriceHelper::optimize($price) : null;
        $board->currency_id = $currencyId;
        $board->price_from = $priceFrom;
        $board->full_text = $fullText;
        $board->term_id = $termId;
        $board->geo_id = $geoId;
        $board->setStatus($status);
        return $board;
    }

    public function edit
    (
        $authorId,
        $name,
        $slug,
        $categoryId,
        $title,
        $description,
        $keywords,
        $note,
        $price,
        $currencyId,
        $priceFrom,
        $fullText,
        $geoId
    ): void
    {
        $this->author_id = $authorId;
        $this->name = $name;
        $this->userSlug = $slug;
        $this->slug = null;
        $this->category_id = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->note = $note;
        $this->price = $price ? PriceHelper::optimize($price) : null;
        $this->currency_id = $currencyId;
        $this->price_from = $priceFrom;
        $this->full_text = $fullText;
        $this->geo_id = $geoId;
    }

    /**
     * Продлевает объявление на указанный срок. Если текущий срок не вышел, то срок увеличивается,
     * если вышел, то объявление продлевается от текущего времени
     * @param null $termId Если не указан, продлевается на срок указанный ранее
     */
    public function extend($termId = null): void
    {
        $term = $termId ? BoardTerm::findOne($termId) : $this->getSafeTerm();
        $from = !$this->active_until || $this->active_until < time() ? time() : $this->active_until;
        $this->active_until = $from + ($term->days * 24 * 60 * 60);
        $this->notification_date = $this->active_until - $term->getNotificationInSeconds();
        $this->term_id = $term->id;
        $this->setStatus(self::STATUS_ACTIVE);
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function updateStatus(): void
    {
        if (time() >= $this->active_until && $this->status == self::STATUS_ACTIVE) {
            $this->status = self::STATUS_ARCHIVE;
        }
    }

    public function getPriceString(): string
    {
        if (!$this->price) {
            return 'По запросу';
        }
        return ($this->price_from ? 'от ' : '') . PriceHelper::normalize($this->price) . ' ' . $this->currency->sign;
    }

    public function getFullPriceString(): string
    {
        return $this->getPriceString();
    }

    public function getUrl(): string
    {
        return Url::to(['/board/board/view', 'slug' => $this->slug, 'id' => $this->id]);
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE && time() < $this->active_until;
    }

    public function isArchive(): bool
    {
        return $this->status == self::STATUS_ARCHIVE || time() > $this->active_until;
    }

    public function isActually(): bool
    {
        return time() < ($this->updated_at + (15 * 24 * 3600));
    }

    public function isOnModeration(): bool
    {
        return $this->status == self::STATUS_ON_MODERATION;
    }

    public function hasExtendNotification(): bool
    {
        $termTime = $this->getSafeTerm()->getNotificationInSeconds();
        return $this->isActive() && time() >= ($this->active_until - $termTime);
    }

    public function canExtend($userId): bool
    {
        return $this->author_id === $userId && $this->status > self::STATUS_ON_MODERATION;
    }

    public function getTitle(): string
    {
        return $this->title ?: Html::encode($this->name);
    }

    public function getMainPhotoUrl($type = 'small', $absolute = false): string
    {
        return $this->main_photo_id ?
            $this->mainPhoto->getUrl($type, $absolute)
            : ($absolute ? Yii::$app->params['frontendHostInfo'] : '') . '/img/no-photo-250.jpg';
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        // Удаляем изображения объявления
        if ($this->photos) {
            foreach ($this->photos as $photo) {
                $photo->removePhotos();
            }
        }

        return true;
    }

    public static function tableName(): string
    {
        return '{{%boards}}';
    }

    public function getOwnerId(): int
    {
        return $this->author_id;
    }

    public function getOwnerUser(): User
    {
        return $this->author;
    }

    public function getOwnerName(): string
    {
        return $this->author->getVisibleName();
    }

    public static function statusesArray(): array
    {
        return [
            self::STATUS_DELETED => 'Удалён',
            self::STATUS_ON_MODERATION => 'На модерации',
            self::STATUS_NOT_ACTIVE => 'Не активно',
            self::STATUS_ACTIVE => 'Опубликовано',
            self::STATUS_ARCHIVE => 'В архиве',
        ];
    }

    public function getStatusName(): string
    {
        return ArrayHelper::getValue(self::statusesArray(), $this->status, '-');
    }

    /**
     * Значение типа объявления(куплю/продам/etc)
     * @return null|string
     */
    public function getDefaultType(): ?string
    {
        if ($type = $this->typeBoardParameter) {
            return $type->option->name;
        }
        return null;
    }

    public function getContentDescription() : string
    {
        return Html::encode($this->note);
    }

    public function getContentName(): string
    {
        return Html::encode($this->name);
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
     * @inheritdoc

    public function rules()
    {
        return [
            [['author_id', 'name', 'slug', 'category_id', 'title', 'currency', 'term_id', 'geo_id', 'active_until', 'created_at', 'updated_at'], 'required'],
            [['author_id', 'category_id', 'price', 'currency', 'term_id', 'geo_id', 'status', 'active_until', 'created_at', 'updated_at'], 'integer'],
            [['description', 'keywords', 'full_text'], 'string'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['note'], 'string', 'max' => 100],
            [['price_from'], 'string', 'max' => 1],
            [['slug'], 'unique'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BoardCategory::class, 'targetAttribute' => ['category_id' => 'id']],
            [['geo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Geo::class, 'targetAttribute' => ['geo_id' => 'id']],
        ];
    }*/

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'author_id' => 'Автор',
            'name' => 'Название',
            'slug' => 'Slug',
            'category_id' => 'Раздел',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'note' => 'Примечание',
            'price' => 'Цена',
            'priceString' => 'Цена',
            'currency' => 'Ден. единица',
            'price_from' => 'Цена от',
            'full_text' => 'Полное описание',
            'term_id' => 'Срок опубликования',
            'geo_id' => 'Регион',
            'status' => 'Статус',
            'statusName' => 'Статус',
            'views' => 'Просмотров',
            'active_until' => 'Активно до',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(BoardCategory::class, ['id' => 'category_id']);
    }

    public function getGeo(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'geo_id']);
    }

    public function getTerm()
    {
        return $this->hasOne(BoardTerm::class, ['id' => 'term_id']);
    }

    public function getSafeTerm(): BoardTerm
    {
        if ($this->term) {
            return $this->term;
        }
        return BoardTerm::find()->where(['default' => 1])->one();
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id'])->where(['module' => Currency::MODULE_BOARD]);
    }

    public function getTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(BoardTagAssignment::class, ['board_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(BoardTag::class, ['id' => 'tag_id'])->viaTable('{{%board_tags_assignment}}', ['board_id' => 'id']);
    }

    public function getBoardParameters(): ActiveQuery
    {
        return $this->hasMany(BoardParameterAssignment::class, ['board_id' => 'id']);
    }

    public function getTypeBoardParameter(): ActiveQuery
    {
        return $this->hasOne(BoardParameterAssignment::class, ['board_id' => 'id'])
            ->andWhere(['parameter_id' => 1]);
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(BoardPhoto::class, ['board_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(BoardPhoto::class, ['id' => 'main_photo_id'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @inheritdoc
     * @return BoardQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BoardQuery(get_called_class());
    }

    public static function getSearchQuery($text): ActiveQuery
    {
        return self::find()->andWhere(['like', 'name', $text]);
    }
}
