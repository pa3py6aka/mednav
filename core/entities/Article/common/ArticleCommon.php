<?php

namespace core\entities\Article\common;


use core\behaviors\SluggableBehavior;
use core\entities\Company\Company;
use core\entities\PhotoInterface;
use core\entities\SearchInterface;
use core\entities\StatusesInterface;
use core\entities\StatusesTrait;
use core\entities\User\User;
use core\entities\UserOwnerInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * @property int $id
 * @property int $user_id
 * @property int $company_id [int(11)]
 * @property int $category_id
 * @property string $title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $name
 * @property string $slug
 * @property string $intro
 * @property string $full_text
 * @property int $indirect_links
 * @property int $main_photo_id
 * @property int $status
 * @property string $views
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Company $company
 * @property string $tagsString
 * @property string $metaDescription
 * @property int $ownerId
 * @property \core\entities\User\User $ownerUser
 * @property PhotoInterface mainPhoto
 */
class ArticleCommon extends ActiveRecord implements StatusesInterface, UserOwnerInterface, SearchInterface
{
    use StatusesTrait;

    public $userSlug;

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getMainPhotoUrl($type = 'small', $absolute = false): string
    {
        return $this->hasMainPhoto() ?
            $this->mainPhoto->getUrl($type, $absolute)
            : ($absolute ? Yii::$app->params['frontendHostInfo'] : '') . '/img/no-photo-250.jpg';
    }

    public function hasMainPhoto(): bool
    {
        return (bool) $this->main_photo_id;
    }

    public function getTitle(): string
    {
        return Html::encode($this->name);
    }

    public function getTagsString(): string
    {
        return implode(', ', $this->getTags()->select('name')->column());
    }

    public function getOwnerId(): int
    {
        return $this->user_id;
    }

    public function getOwnerUser(): User
    {
        return $this->user;
    }

    public function getMetaDescription(): string
    {
        return $this->meta_description ?
            Html::encode($this->meta_description)
            : $this->intro . ', ' . ($this->company_id ? $this->company->getFullName() : $this->user->getUserName());
    }


    public function getFullPriceString(): string
    {
        return '';
    }

    public function getContentDescription(): string
    {
        return Html::encode($this->intro);
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
     * {@inheritdoc}
     */
    public function attributeLabels($attributeLabels = [])
    {
        return array_merge([
            'id' => 'ID',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'title' => 'Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'name' => 'Заголовок',
            'slug' => 'Slug',
            'intro' => 'Анонс',
            'full_text' => 'Полный текст',
            'indirect_links' => 'Непрямые ссылки',
            'main_photo_id' => 'Main Photo ID',
            'status' => 'Статус',
            'views' => 'Просмотров',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ], $attributeLabels);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }

    /**
     * {@inheritdoc}
     * @return ArticlesQueryCommon the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticlesQueryCommon(get_called_class());
    }

    public static function getSearchQuery($text): ActiveQuery
    {
        return self::find()->andWhere(['like', 'title', $text]);
    }
}
