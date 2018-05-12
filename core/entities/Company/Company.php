<?php

namespace core\entities\Company;

use core\entities\Company\queries\CompanyQuery;
use core\entities\Geo;
use core\entities\User\User;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%companies}}".
 *
 * @property int $id
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
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%companies}}';
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
        return $this->hasMany(CompanyTag::class, ['id' => 'tag_id'])->viaTable('{{%company_tags_assignment}}', ['company_id' => 'id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['company_id' => 'id']);
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
