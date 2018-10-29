<?php

namespace core\forms\manage\Trade;


use core\entities\Trade\Trade;
use core\entities\Trade\TradeUserCategory;
use core\entities\User\User;
use core\helpers\PriceHelper;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class TradeManageForm extends Model
{
    public $userId;
    public $categoryId;
    public $name;
    public $metaTitle;
    public $metaDescription;
    public $metaKeywords;
    public $slug;
    public $code;
    public $price;
    public $wholeSalePrice;
    public $wholeSaleFrom;
    public $stock;
    public $externalLink;
    public $note;
    public $description;
    public $tags;
    public $photos;

    private $_trade;

    public const SCENARIO_ADMIN_CREATE = 'adminCreate';
    public const SCENARIO_ADMIN_EDIT = 'adminEdit';
    public const SCENARIO_USER_CREATE = 'userCreate';
    public const SCENARIO_USER_EDIT = 'userEdit';

    public function __construct(Trade $trade = null, array $config = [])
    {
        if ($trade) {
            $this->userId = $trade->user_id;
            $this->categoryId = $trade->user_category_id;
            $this->name = $trade->name;
            $this->metaTitle = $trade->meta_title;
            $this->metaDescription = $trade->meta_description;
            $this->metaKeywords = $trade->meta_keywords;
            $this->slug = $trade->slug;
            $this->code = $trade->code;
            $this->price = $trade->price ? $trade->price / 100 : '';
            foreach ($trade->getWholesales() as $n => $wholesale) {
                $this->wholeSalePrice[$n] = $wholesale['price'] / 100;
                $this->wholeSaleFrom[$n] = $wholesale['from'];
            }
            $this->stock = $trade->stock;
            $this->externalLink = $trade->external_link;
            $this->note = $trade->note;
            $this->description = $trade->description;
            $this->tags = implode(', ', $trade->getTags()->select('name')->column());
            $this->_trade = $trade;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['userId', 'integer'],

            ['categoryId', 'required'],
            ['categoryId', 'integer'],

            ['name', 'required'],
            ['name', 'string', 'max' => 255],

            [['metaTitle', 'metaKeywords'], 'string', 'max' => 255],
            ['metaDescription', 'string'],

            ['slug', 'trim'],
            ['slug', 'string', 'max' => 255],

            ['code', 'string', 'max' => 255],

            ['price', 'match', 'pattern' => PriceHelper::REGEXP],

            ['wholeSalePrice', 'each', 'rule' => ['match', 'pattern' => PriceHelper::REGEXP]],
            //['wholeSaleCurrency', 'each', 'rule' => ['integer']],
            ['wholeSaleFrom', 'each', 'rule' => ['integer', 'min' => 0]],

            ['stock', 'boolean'],

            ['externalLink', 'url'],

            ['note', 'string', 'max' => 80],

            ['description', 'required'],
            ['description', 'string'],

            ['tags', 'string'],

            ['photos', 'each', 'rule' => ['string']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_CREATE] = [
            'userId', 'categoryId', 'name', 'metaTitle', 'metaKeywords', 'metaDescription', 'slug', 'code', 'price',
            'wholeSalePrice', 'wholeSaleFrom', 'stock', 'externalLink', 'note', 'description', 'tags', 'photos'
        ];
        $scenarios[self::SCENARIO_ADMIN_EDIT] = array_diff($scenarios[self::SCENARIO_ADMIN_CREATE], ['photos']);
        $scenarios[self::SCENARIO_USER_CREATE] = array_diff($scenarios[self::SCENARIO_ADMIN_CREATE], [
            'userId', 'metaTitle', 'metaKeywords', 'metaDescription', 'slug'
        ]);
        $scenarios[self::SCENARIO_USER_EDIT] = array_diff($scenarios[self::SCENARIO_USER_CREATE], ['photos']);
        return $scenarios;
    }

    public function beforeValidate()
    {
        if (!$this->slug) {
            $this->slug = $this->name;
        }

        return parent::beforeValidate();
    }

    public function getUserId(): int
    {
        $userId = in_array($this->scenario, [self::SCENARIO_USER_CREATE, self::SCENARIO_USER_EDIT])
            ? Yii::$app->user->id
            : ($this->userId ?: Yii::$app->user->id);
        if ($userId !== Yii::$app->user->id) {
            $user = User::findOne($userId);
            if (!$user->isCompany()) {
                throw new \DomainException("Пользователь не является компанией.");
            }
        }
        return $userId;
    }

    public function showWholesales(): bool
    {
        return $this->categoryId ?
            TradeUserCategory::find()->select('wholesale')->where(['id' => $this->categoryId])->scalar()
            : false;
    }

    public function getUserCategories(): array
    {
        $userId = $this->getUserId();
        return ArrayHelper::map(TradeUserCategory::find()->where(['user_id' => $userId])->asArray()->all(), 'id', 'name');
    }

    public function attributeLabels()
    {
        return [
            'userId' => 'Пользователь',
            'categoryId' => 'Категория',
            'name' => 'Название',
            'metaTitle' => 'Meta title',
            'metaDescription' => 'Meta description',
            'metaKeywords' => 'Meta keywords',
            'slug' => 'URL',
            'code' => 'Артикул',
            'price' => 'Цена',
            'wholeSalePrice' => 'Оптовая цена',
            'wholeSaleFrom' => 'Оптовая цена от',
            'stock' => 'В наличии',
            'externalLink' => 'Товар на сайте продавца',
            'note' => 'Уточнение',
            'description' => 'Полное описание',
            'tags' => 'Теги',
            'photos' => 'Фото',
        ];
    }
}