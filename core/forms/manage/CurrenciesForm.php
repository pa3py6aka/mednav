<?php

namespace core\forms\manage;


use core\entities\Currency;
use core\services\TransactionManager;
use yii\base\Model;
use yii\base\UserException;

class CurrenciesForm extends Model
{
    public $id;
    public $name;
    public $sign;
    public $default;

    private $maxId;
    private $module;

    public function __construct(int $module, array $config = [])
    {
        $currencies = Currency::find()->where(['module' => $module])->all();
        $this->module = $module;
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
            [['name', 'sign', 'default'], 'required'],
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
        (new TransactionManager())->wrap(function () {
            foreach ($this->name as $id => $name) {
                if ($this->name[$id] && $this->sign[$id]) {
                    $currency = Currency::find()->where(['id' => $id, 'module' => $this->module])->one();
                    if (!$currency) {
                        $currency = new Currency();
                        $currency->id = $id;
                        $currency->module = $this->module;
                    }
                    $currency->name = $this->name[$id];
                    $currency->sign = $this->sign[$id];
                    $currency->default = $this->default[$id];
                    $currency->save();
                } else {
                    if ($currency = Currency::find()->where(['id' => $id, 'module' => $this->module])->one()) {
                        $this->checkUsing($currency);
                        $currency->delete();
                        if ($currency->default) {
                            Currency::find()
                                ->where(['module' => $this->module])
                                ->orderBy(['id' => SORT_ASC])
                                ->limit(1)
                                ->one()
                                ->updateAttributes(['default' => 1]);
                        }
                    }
                }
            }
        });
    }

    private function checkUsing(Currency $currency)
    {
        $class = $currency->getModuleClass();
        $query = $class::find()
            ->alias('e')
            ->leftJoin(Currency::tableName() . ' c', 'c.id=e.currency_id AND c.module=' . $currency->module)
            ->where(['e.currency_id' => $currency->id]);
        if ($query->exists()) {
            throw new \DomainException("Денежная единица {$currency->name} используется и не может быть удалена.");
        }
    }

    public function getMaxId()
    {
        return $this->maxId;
    }
}