<?php

namespace core\forms\manage;


use core\entities\Currency;
use yii\base\Model;

class CurrenciesForm extends Model
{
    public $id;
    public $name;
    public $sign;
    public $default;

    private $maxId;

    public function __construct(array $config = [])
    {
        $currencies = Currency::find()->all();
        foreach ($currencies as $currency) {
            $this->id[$currency->id] = $currency->id;
            $this->name[$currency->id] = $currency->name;
            $this->sign[$currency->id] = $currency->sign;
            $this->default[$currency->id] = $currency->default;
            $this->maxId = $currency->id;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['id', 'name', 'sign', 'default'], 'required'],
            [['id'], 'each', 'rule' => ['integer']],
            [['name', 'sign'], 'each', 'rule' => ['string']],
            ['default', 'each', 'rule' => ['boolean']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'default' => 'Выбран',
        ];
    }

    public function save()
    {
        foreach ($this->name as $id => $name) {
            if ($this->name[$id] && $this->sign[$id]) {
                $currency = Currency::find()->where(['id' => $id])->one();
                if (!$currency) {
                    $currency = new Currency();
                    $currency->id = $id;
                }
                $currency->name = $this->name[$id];
                $currency->sign = $this->sign[$id];
                $currency->default = $this->default[$id];
                $currency->save();
            } else {
                if ($currency = Currency::find()->where(['id' => $id])->one()) {
                    $currency->delete();
                }
            }
        }
    }

    public function getMaxId()
    {
        return $this->maxId;
    }
}