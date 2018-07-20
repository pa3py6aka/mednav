<?php

namespace core\forms\manage\Trade;


use core\entities\Trade\TradeUoM;
use yii\base\Model;

class TradeUoMsForm extends Model
{
    public $id;
    public $name;
    public $sign;
    public $default;

    private $maxId;

    public function __construct(array $config = [])
    {
        $uoms = TradeUoM::find()->all();
        foreach ($uoms as $uom) {
            $this->id[$uom->id] = $uom->id;
            $this->name[$uom->id] = $uom->name;
            $this->sign[$uom->id] = $uom->sign;
            $this->default[$uom->id] = $uom->default;
            $this->maxId = $uom->id;
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
                $uom = TradeUoM::find()->where(['id' => $id])->one();
                if (!$uom) {
                    $uom = new TradeUoM();
                    $uom->id = $id;
                }
                $uom->name = $this->name[$id];
                $uom->sign = $this->sign[$id];
                $uom->default = $this->default[$id];
                $uom->save();
            } else {
                if ($uom = TradeUoM::find()->where(['id' => $id])->one()) {
                    $uom->delete();
                }
            }
        }
    }

    public function getMaxId()
    {
        return $this->maxId;
    }
}