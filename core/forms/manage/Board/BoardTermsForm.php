<?php

namespace core\forms\manage\Board;


use core\entities\Board\BoardTerm;
use yii\base\Model;

class BoardTermsForm extends Model
{
    public $id;
    public $days;
    public $daysHuman;
    public $default;
    public $notification;

    private $maxId;

    public function __construct(array $config = [])
    {
        $terms = BoardTerm::find()->all();
        foreach ($terms as $term) {
            $this->id[$term->id] = $term->id;
            $this->days[$term->id] = $term->days;
            $this->daysHuman[$term->id] = $term->daysHuman;
            $this->default[$term->id] = $term->default;
            $this->notification[$term->id] = $term->notification;
            $this->maxId = $term->id;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['id', 'days', 'daysHuman', 'default', 'notification'], 'required'],
            [['id', 'days', 'notification'], 'each', 'rule' => ['integer']],
            ['daysHuman', 'each', 'rule' => ['string']],
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
        foreach ($this->days as $id => $days) {
            if ($this->days[$id] && $this->daysHuman[$id] && $this->notification[$id]) {
                $term = BoardTerm::find()->where(['id' => $id])->one();
                if (!$term) {
                    $term = new BoardTerm();
                    $term->id = $id;
                }
                $term->days = $this->days[$id];
                $term->daysHuman = $this->daysHuman[$id];
                $term->default = $this->default[$id];
                $term->notification = $this->notification[$id];
                $term->save();
            } else {
                if ($term = BoardTerm::find()->where(['id' => $id])->one()) {
                    $term->delete();
                }
                if ($term->default) {
                    $defaultTerm = BoardTerm::find()->orderBy('id')->one();
                    $defaultTerm->default = 1;
                    $defaultTerm->save();
                }
            }
        }
    }

    public function getMaxId()
    {
        return $this->maxId;
    }
}