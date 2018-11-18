<?php

namespace core\entities\Trade;


use core\entities\Currency;
use core\entities\User\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%trade_user_categories}}".
 *
 * @property int $id
 * @property int $user_id [int(11)]
 * @property string $name
 * @property int $category_id
 * @property int $uom_id
 * @property int $currency_id
 * @property int $wholesale
 *
 * @property User $user
 * @property TradeCategory $category
 * @property Currency $currency
 * @property TradeUoM $uom
 * @property Trade[] $trades
 * @property Trade[] $activeTrades
 */
class TradeUserCategory extends ActiveRecord
{
    public static function create($userId, $name, $categoryId, $uomId, $currencyId, $wholeSale): TradeUserCategory
    {
        $category = new self();
        $category->user_id = $userId;
        $category->name = $name;
        $category->category_id = $categoryId;
        $category->uom_id = $uomId;
        $category->currency_id = $currencyId;
        $category->wholesale = $wholeSale;
        return $category;
    }

    public function edit($name, $categoryId, $uomId, $currencyId, $wholeSale): void
    {
        $this->name = $name;
        $this->category_id = $categoryId;
        $this->uom_id = $uomId;
        $this->currency_id = $currencyId;
        $this->wholesale = $wholeSale;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%trade_user_categories}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['name', 'category_id', 'uom_id', 'currency_id'], 'required'],
            [['category_id', 'uom_id', 'currency_id', 'wholesale'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => TradeCategories::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'name' => 'Название',
            'category_id' => 'Раздел',
            'uom_id' => 'Ед. изм.',
            'currency_id' => 'Валюта',
            'wholesale' => 'Опт',
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(TradeCategory::class, ['id' => 'category_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id'])->where(['module' => Currency::MODULE_TRADE]);
    }

    public function getUom()
    {
        return $this->hasOne(TradeUoM::class, ['id' => 'uom_id']);
    }

    public function getTrades(): ActiveQuery
    {
        return $this->hasMany(Trade::class, ['user_category_id' => 'id']);
    }

    public function getActiveTrades(): ActiveQuery
    {
        return $this->hasMany(Trade::class, ['user_category_id' => 'id'])
            ->andWhere(['status' => Trade::STATUS_ACTIVE]);
    }
}
