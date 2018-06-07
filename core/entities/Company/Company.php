<?php

namespace core\entities\Company;

use core\entities\Company\queries\CompanyQuery;
use core\entities\Geo;
use core\entities\StatusesInterface;
use core\entities\StatusesTrait;
use core\entities\User\User;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;

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
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Geo $geo
 * @property CompanyPhoto $mainPhoto
 * @property CompanyPhoto[] $photos
 * @property CompanyTagsAssignment[] $companyTagsAssignments
 * @property CompanyTag[] $tags
 * @property User[] $user
 * @property CompanyCategoryAssignment[] $companyCategoryAssignments
 * @property CompanyCategory[] $categories
 */
class Company extends ActiveRecord implements StatusesInterface
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

    public function getPhones(): array
    {
        return $this->phones ? Json::decode($this->phones) : [];
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

    public function logoPath(): string
    {
        return Yii::getAlias('@frontend/web/i/company/lg');
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
            'form' => 'Form',
            'name' => 'Name',
            'logo' => 'Logo',
            'slug' => 'Slug',
            'site' => 'Site',
            'geo_id' => 'Geo ID',
            'address' => 'Address',
            'phones' => 'Phones',
            'fax' => 'Fax',
            'email' => 'Email',
            'info' => 'Info',
            'title' => 'Title',
            'short_description' => 'Short Description',
            'description' => 'Description',
            'main_photo_id' => 'Main Photo ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getGeo(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'geo_id']);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(CompanyPhoto::class, ['id' => 'main_photo_id']);
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(CompanyPhoto::class, ['company_id' => 'id']);
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

    /**
     * {@inheritdoc}
     * @return CompanyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompanyQuery(get_called_class());
    }
}
