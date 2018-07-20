<?php

namespace core\forms\manage\Trade;


use core\entities\Trade\TradeDelivery;
use yii\base\Model;

class TradeDeliveryForm extends Model
{
    public $id;
    public $name;
    public $terms;
    public $regions;

    private $maxId;

    public function __construct(array $config = [])
    {
        $deliveries = TradeDelivery::find()->all();
        foreach ($deliveries as $delivery) {
            $this->id[$delivery->id] = $delivery->id;
            $this->name[$delivery->id] = $delivery->name;
            $this->terms[$delivery->id] = $delivery->has_terms;
            $this->regions[$delivery->id] = $delivery->has_regions;
            $this->maxId = $delivery->id;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['id', 'name', 'terms', 'regions'], 'required'],
            [['id'], 'each', 'rule' => ['integer']],
            [['name'], 'each', 'rule' => ['string']],
            [['terms', 'regions'], 'each', 'rule' => ['boolean']],
        ];
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function save()
    {
        foreach ($this->name as $id => $name) {
            if ($this->name[$id]) {
                $delivery = TradeDelivery::find()->where(['id' => $id])->one();
                if (!$delivery) {
                    $delivery = new TradeDelivery();
                    $delivery->id = $id;
                }
                $delivery->name = $this->name[$id];
                $delivery->has_terms = $this->terms[$id];
                $delivery->has_regions = $this->regions[$id];
                $delivery->save();
            } else {
                if ($delivery = TradeDelivery::find()->where(['id' => $id])->one()) {
                    $delivery->delete();
                }
            }
        }
    }

    public function getMaxId()
    {
        return $this->maxId;
    }
}