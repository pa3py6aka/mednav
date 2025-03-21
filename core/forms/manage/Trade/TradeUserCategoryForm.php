<?php

namespace core\forms\manage\Trade;


use core\entities\Currency;
use core\entities\Trade\TradeCategory;
use core\entities\Trade\TradeUoM;
use core\entities\Trade\TradeUserCategory;
use yii\base\Model;

class TradeUserCategoryForm extends Model
{
    public $name;
    public $categoryId;
    public $uomId;
    public $currencyId;
    public $wholeSale;

    public $userId;

    private $_userCategory;
    private $categories;

    public function __construct(TradeUserCategory $userCategory = null, array $config = [])
    {
        if ($userCategory) {
            $this->name = $userCategory->name;
            foreach ($userCategory->category->parents as $parent) {
                if (!$parent->isRoot()) {
                    $this->categoryId[] = $parent->id;
                }
            }
            $this->categoryId[] = $userCategory->category_id;
            $this->uomId = $userCategory->uom_id;
            $this->currencyId = $userCategory->currency_id;
            $this->wholeSale = $userCategory->wholesale;
            $this->userId = $userCategory->user_id;
        } else {
            $this->categoryId[] = '';
            $this->uomId = TradeUoM::getDefaultId();
            $this->currencyId = Currency::getDefaultIdFor(Currency::MODULE_TRADE);
        }
        $this->_userCategory = $userCategory;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'categoryId', 'uomId', 'currencyId'], 'required'],
            ['name', 'string', 'max' => 255],
            ['categoryId', 'exist', 'targetClass' => TradeCategory::class, 'targetAttribute' => 'id'],
            ['categoryId', 'validateCategory'],
            [['uomId', 'currencyId'], 'integer'],
            ['wholeSale', 'boolean'],
            ['userId', 'integer'],
        ];
    }

    public function validateCategory($attribute, $params)
    {
        $category = TradeCategory::findOne((int) $this->$attribute);
        if (!$category->enabled) {
            $attr = $category->depth == 1 ? $attribute : 'category-' . $category->id;
            $this->addError($attr, 'Раздел “' . $category->name . '” недоступен для добавления товара, выберите вложенный подраздел.');
        }
    }

    public function beforeValidate()
    {
        $this->categories = $this->categoryId;
        if (\is_array($this->categoryId)) {
            $this->categoryId = array_diff($this->categoryId, ['', 0]);
            $categoryId = array_pop($this->categoryId);
            $this->categoryId = $categoryId;
        }

        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        if ($this->hasErrors()) {
            $this->categoryId = $this->categories;
        }
        parent::afterValidate();
    }

    public function isNew(): bool
    {
        return $this->_userCategory === null;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'categoryId' => 'Раздел',
            'uomId' => 'Ед. измерения',
            'currencyId' => 'Валюта',
            'wholeSale' => 'Оптовые цены',
            'userId' => 'Пользователь',
        ];
    }
}