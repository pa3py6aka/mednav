<?php

namespace core\forms\manage\Board;


use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Board\BoardTerm;
use core\entities\Currency;
use core\entities\Geo;
use core\helpers\BoardHelper;
use core\helpers\PriceHelper;
use yii\base\Model;

class BoardManageForm extends Model
{
    public $authorId;
    public $name;
    public $slug;
    public $categoryId;
    public $title;
    public $description;
    public $keywords;
    public $note;
    public $price;
    public $currency;
    public $priceFrom;
    public $fullText;
    public $tags;
    public $termId;
    public $geoId;
    public $params;
    public $photos;

    private $_board;

    const SCENARIO_ADMIN_EDIT = 'adminEdit';
    const SCENARIO_USER_CREATE = 'userCreate';
    const SCENARIO_USER_EDIT = 'userEdit';

    public function __construct(Board $board = null, array $config = [])
    {
        if ($board) {
            $this->authorId = $board->author_id;
            $this->name = $board->name;
            $this->slug = $board->slug;
            foreach ($board->category->parents as $parent) {
                if (!$parent->isRoot()) {
                    $this->categoryId[] = $parent->id;
                }
            }
            $this->categoryId[] = $board->category_id;
            $this->title = $board->title;
            $this->description = $board->description;
            $this->keywords = $board->keywords;
            $this->note = $board->note;
            $this->price = $board->price ? $board->price / 100 : '';
            $this->currency = $board->currency_id;
            $this->priceFrom = $board->price_from;
            $this->fullText = $board->full_text;
            $this->tags = implode(', ', $board->getTags()->select('name')->column());
            $this->termId = $board->term_id;
            $this->geoId = $board->geo_id;
            foreach ($board->boardParameters as $boardParameter) {
                $this->params[$boardParameter->parameter_id] = $boardParameter->getValueByType(true);
            }

            $this->_board = $board;
        } else {
            $this->categoryId[] = '';
            $this->termId = BoardTerm::getDefaultId();
            $this->currency = Currency::getDefaultIdFor(Currency::MODULE_BOARD);
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'categoryId', 'fullText', 'termId', 'geoId'], 'required'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['authorId', 'currency', 'termId', 'geoId'], 'integer'],
            [['description', 'keywords', 'fullText', 'tags'], 'string'],
            ['note', 'string', 'max' => 100],
            ['priceFrom', 'boolean'],
            ['categoryId', 'integer'],
            ['categoryId', 'exist', 'targetClass' => BoardCategory::class, 'targetAttribute' => 'id'],
            [['params'], 'each', 'rule' => ['safe']],
            ['photos', 'each', 'rule' => ['string']],
            ['price', 'match', 'pattern' => PriceHelper::REGEXP]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_EDIT] = [
            'authorId', 'name', 'slug', 'categoryId', 'title', 'description', 'keywords', 'note', 'price',
            'currency', 'priceFrom', 'fullText', 'tags', 'geoId', 'params'
        ];
        $scenarios[self::SCENARIO_USER_EDIT] = array_diff($scenarios[self::SCENARIO_ADMIN_EDIT], ['authorId', 'slug', 'title', 'description', 'keywords']);
        $scenarios[self::SCENARIO_USER_CREATE] = array_merge($scenarios[self::SCENARIO_USER_EDIT], ['photos', 'termId']);
        return $scenarios;
    }

    public function beforeValidate()
    {
        if (!$this->slug) {
            $this->slug = $this->name;
        }
        if (is_array($this->categoryId)) {
            $this->categoryId = array_diff($this->categoryId, ['', 0]);
            $categoryId = array_pop($this->categoryId);
            $this->categoryId = $categoryId;
        }

        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return [
            'authorId' => 'Id автора',
            'name' => 'Заголовок',
            'slug' => 'Slug',
            'categoryId' => 'Раздел',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'note' => 'Уточнение',
            'price' => 'Цена',
            'currency' => 'Ден. единица',
            'priceFrom' => 'Цена от',
            'fullText' => 'Полное описание',
            'tags' => 'Теги',
            'termId' => 'Срок размещения',
            'geoId' => 'Регион',
            'params' => 'Параметры',
            'photos' => 'Фотографии',
        ];
    }

    public function getParametersBlock(): string
    {
        $lastCategory = end($this->categoryId);
        if ($lastCategory) {
            $lastCategory = BoardCategory::findOne($lastCategory);
            if (is_array($this->params) && $lastCategory) {
                return BoardHelper::generateParameterFields($lastCategory, $this->formName(), $this->params);
            }
        }
        return '';
    }

    public function geoName(): string
    {
        return $this->_board && $this->_board->geo_id ? $this->_board->geo->name
            : ($this->geoId ? Geo::find()->select('name')->where(['id' => $this->geoId])->scalar() : 'Выбрать регион');
    }
}