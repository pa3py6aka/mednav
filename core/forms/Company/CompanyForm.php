<?php

namespace core\forms\Company;


use core\entities\Company\Company;
use core\entities\Company\CompanyCategory;
use core\helpers\Pluralize;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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

    public const SCENARIO_ADMIN_EDIT = 'adminEdit';
    public const SCENARIO_USER_CREATE = 'userCreate';
    public const SCENARIO_USER_EDIT = 'userEdit';

    public function __construct(Company $company = null, array $config = [])
    {
        if ($company) {
            $this->slug = $company->slug;
            $this->user_id = $company->user_id;
            $this->form = $company->form;
            $this->name = $company->name;
            $this->categories = $company->getCategories()->select('id')->column();
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
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['form', 'name', 'categories', 'categoriesHint', 'geoId', 'address', 'email', 'title'], 'required'],
            [['slug', 'name', 'site', 'address', 'email', 'title'], 'string', 'max' => 255],
            [['user_id', 'geoId'], 'integer'],
            ['logo', 'image', 'maxSize' => Yii::$app->params['maxFileSize'], 'extensions' => Yii::$app->params['imageExtensions']],
            ['categories', 'each', 'rule' => ['integer']],
            ['phones', 'each', 'rule' => ['string']],
            ['fax', 'string', 'max' => 50],
            ['email', 'email'],
            [['info', 'shortDescription', 'description'], 'string'],
            ['tags', 'each', 'rule' => ['string']],
            ['photos', 'string'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_USER_CREATE] = array_diff(array_keys($this->attributes), ['slug', 'user_id']);
        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'slug' => 'Slug',
            'user_id' => 'User Id',
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
        return $counts ? 'Выбрано ' . Pluralize::get($counts, 'раздел', 'раздела' , 'разделов') : 'Выбрать';
    }
}