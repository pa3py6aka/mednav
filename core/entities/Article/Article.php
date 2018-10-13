<?php

namespace core\entities\Article;

use core\entities\Article\queries\ArticlesQuery;
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
use yii\helpers\Url;

/**
 * This is the model class for table "{{%articles}}".
 *
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
 * @property ArticlePhoto[] $photos
 * @property ArticleTagsAssignment[] $articleTagsAssignments
 * @property ArticleTag[] $tags
 * @property ArticleCategory $category
 * @property ArticlePhoto $mainPhoto
 * @property User $user
 * @property Company $company
 */
class Article extends ActiveRecord implements StatusesInterface, UserOwnerInterface
{
    use StatusesTrait;

    public $userSlug;

    public static function create(
        $userId,
        $companyId,
        $categoryId,
        $title,
        $metaDescription,
        $metaKeywords,
        $name,
        $slug,
        $intro,
        $fullText,
        $indirectLinks,
        $status
    ): Article
    {
        $article = new Article();
        $article->user_id = $userId;
        $article->company_id = $companyId;
        $article->category_id = $categoryId;
        $article->title = $title;
        $article->meta_description = $metaDescription;
        $article->meta_keywords = $metaKeywords;
        $article->name = $name;
        $article->intro = $intro;
        $article->full_text = $fullText;
        $article->indirect_links = $indirectLinks;
        $article->status = $status;
        $article->views = 0;
        $article->userSlug = $slug;
        return $article;
    }

    public function edit(
        $userId,
        $companyId,
        $categoryId,
        $title,
        $metaDescription,
        $metaKeywords,
        $name,
        $slug,
        $intro,
        $fullText,
        $indirectLinks
    ): void
    {
        $this->user_id = $userId;
        $this->company_id = $companyId;
        $this->category_id = $categoryId;
        $this->title = $title;
        $this->meta_description = $metaDescription;
        $this->meta_keywords = $metaKeywords;
        $this->name = $name;
        $this->intro = $intro;
        $this->full_text = $fullText;
        $this->indirect_links = $indirectLinks;
        $this->userSlug = $slug;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getUrl()
    {
        return Url::to(['/article/article/view', 'id' => $this->id, 'slug' => $this->slug]);
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

    public function getMetaDescription(): string
    {
        return $this->meta_description ?
            Html::encode($this->meta_description)
            : $this->intro . ', ' . ($this->company_id ? $this->company->getFullName() : $this->user->getUserName());
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%articles}}';
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
            [['user_id', 'category_id', 'meta_description', 'name', 'slug', 'intro', 'full_text', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'category_id', 'indirect_links', 'main_photo_id', 'status', 'views', 'created_at', 'updated_at'], 'integer'],
            [['meta_description', 'intro', 'full_text'], 'string'],
            [['title', 'meta_keywords', 'name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['main_photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticlePhotos::className(), 'targetAttribute' => ['main_photo_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    } */

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

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(ArticlePhoto::class, ['article_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(ArticlePhoto::class, ['id' => 'main_photo_id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getArticleTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(ArticleTagsAssignment::class, ['article_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(ArticleTag::class, ['id' => 'tag_id'])->viaTable('{{%article_tags_assignment}}', ['article_id' => 'id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(ArticleCategory::class, ['id' => 'category_id']);
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
     * @return ArticlesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticlesQuery(get_called_class());
    }
}
