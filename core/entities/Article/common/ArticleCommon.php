<?php

namespace core\entities\Article\common;


use core\entities\CategoryAssignmentInterface;
use core\entities\Company\Company;
use core\entities\StatusesInterface;
use core\entities\StatusesTrait;
use core\entities\User\User;
use core\entities\UserOwnerInterface;
use Yii;
use yii\behaviors\SluggableBehavior;
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
 */
class ArticleCommon extends ActiveRecord implements StatusesInterface, UserOwnerInterface
{
    use StatusesTrait;

    public $userSlug;

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getMainPhotoUrl($type = 'small', $absolute = false): string
    {
        return $this->main_photo_id ?
            $this->mainPhoto->getUrl($type, $absolute)
            : ($absolute ? Yii::$app->params['frontendHostInfo'] : '') . '/img/no-photo-250.jpg';
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
    public function attributeLabels()
    {
        return [
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
            'indirect_links' => 'Indirect Links',
            'main_photo_id' => 'Main Photo ID',
            'status' => 'Status',
            'views' => 'Просмотров',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Updated At',
        ];
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
}
