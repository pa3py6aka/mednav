<?php

namespace core\forms\Company;


use core\entities\Company\Company;
use core\entities\Company\CompanyCategory;
use core\entities\Geo;
use core\helpers\Pluralize;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\UploadedFile;

class CompanyForm extends Model
{
    public $slug;
    public $user_id;

    public $form;
    public $name;
    public $logo;
    public $categories = [];
    public $site;
    public $geoId;
    public $address;
    public $phones;
    public $fax;
    public $email;
    public $info;
    public $title;
    public $shortDescription;
    public $description;
    public $tags;
    public $photos;

    public $categoriesHint;

    public $company;

    public const SCENARIO_ADMIN_EDIT = 'adminEdit';
    public const SCENARIO_USER_MANAGE = 'userManage';

    public function __construct(Company $company = null, array $config = [])
    {
        if ($company) {
            $this->slug = $company->slug;
            $this->user_id = $company->user_id;
            $this->form = $company->form;
            $this->name = $company->name;
            $this->categories = $company->getCategories()->select('id')->indexBy('id')->column();
            $this->categoriesHint = $this->categories ? 1 : '';
            $this->site = $company->site;
            $this->geoId = $company->geo_id;
            $this->address = $company->address;
            $this->phones = $company->getPhones();
            $this->fax = $company->fax;
            $this->email = $company->email;
            $this->info = $company->info;
            $this->title = $company->title;
            $this->shortDescription = $company->short_description;
            $this->description = $company->description;
            $this->tags = implode(', ', $company->getTags()->select('name')->column());

            $this->company = $company;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['form', 'name', 'geoId', 'address', 'email', 'title', 'description'], 'required'],
            ['categoriesHint', 'required', 'message' => 'Выберите нужные разделы'],
            [['slug', 'name', 'site', 'address', 'email', 'title'], 'string', 'max' => 255],
            [['user_id', 'geoId'], 'integer'],
            ['logo', 'image', 'maxSize' => Yii::$app->params['maxFileSize'], 'extensions' => Yii::$app->params['imageExtensions']],
            ['categories', 'each', 'rule' => ['integer']],
            ['phones', 'each', 'rule' => ['string']],
            ['fax', 'string', 'max' => 50],
            ['email', 'email'],
            [['info', 'shortDescription', 'description'], 'string'],
            ['tags', 'string'],
            ['photos', 'each', 'rule' => ['string']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_USER_MANAGE] = array_diff(array_keys($this->attributes), ['slug', 'user_id']);
        return $scenarios;
    }

    public function beforeValidate()
    {
        $this->logo = UploadedFile::getInstance($this, 'logo');
        $this->slug = $this->slug ?: $this->name;
        $this->categories = array_diff($this->categories, ['0', '']);
        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return [
            'slug' => 'Slug',
            'user_id' => 'Администратор',
            'form' => 'Форма',
            'name' => 'Название компании',
            'logo' => 'Логотип',
            'categories' => 'Разделы',
            'categoriesHint' => 'Разделы',
            'site' => 'Сайт',
            'geoId' => 'Регион',
            'address' => 'Адрес',
            'phones' => 'Телефоны',
            'fax' => 'Факс',
            'email' => 'E-mail',
            'info' => 'Доп. информация',
            'title' => 'Заголовок стр. компании',
            'shortDescription' => 'Краткое описание',
            'description' => 'Описание компании',
            'tags' => 'Теги',
            'photos' => 'Фотографии',
        ];
    }

    public function categorySelectionString(): string
    {
        $counts = count($this->categories);
        return $counts ?
            Pluralize::get($counts, 'Выбран', 'Выбрано' , 'Выбрано', true) . ' ' . Pluralize::get($counts, 'раздел', 'раздела' , 'разделов')
            : 'Выбрать';
    }

    public function geoName(): string
    {
        return $this->company && $this->company->geo_id ? $this->company->geo->name
            : ($this->geoId ? Geo::find()->select('name')->where(['id' => $this->geoId])->scalar() : 'Выбрать регион');
    }
}