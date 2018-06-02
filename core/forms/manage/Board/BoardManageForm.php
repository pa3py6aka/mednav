<?php

namespace core\forms\manage\Board;


use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Board\BoardTerm;
use core\entities\Currency;
use core\entities\Geo;
use core\helpers\BoardHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
                $this->categoryId[] = $parent->id;
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
            $this->currency = Currency::getDefaultId();
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
            ['price', 'match', 'pattern' => '/^[0-9]+((\.|,)[0-9]{2})?$/uis']
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

    public function getCategoryDropdowns(ActiveForm $form): string
    {
        $dropdowns = [];
        $n = 0;
        foreach ($this->categoryId as $n => $categoryId) {
            if ($n == 0) {
                $categories = ArrayHelper::map(BoardCategory::find()->enabled()->roots()->asArray()->all(), 'id', 'name');
            } else if ($n == 2) {
                $categories = ArrayHelper::map(BoardCategory::findOne($this->categoryId[$n - 1])->getDescendants()->active()->enabled()->all(), 'id', function ($item) {
                    return ($item['depth'] > 2 ? str_repeat('-', $item['depth'] - 2) . ' ' : '') . $item['name'];
                });
                $this->categoryId[$n] = end($this->categoryId);
            } else if ($n == 1) {
                $categories = ArrayHelper::map(BoardCategory::findOne($this->categoryId[$n - 1])->getChildren()->active()->enabled()->all(), 'id', 'name');
            }

            if ($n < 3) {
                if ($n == 0) {
                    $dropdowns[] = $form->field($this, 'categoryId[' . $n . ']')
                        ->dropDownList($categories, ['prompt' => 'Выберите раздел']);
                } else {
                    $dropdown = Html::activeDropDownList($this, 'categoryId[' . $n . ']',$categories, ['class' => 'form-control', 'prompt' => '']);
                    $dropdowns[] = Html::tag('div', $dropdown, ['class' => 'form-group category-dropdown']);
                }
            }
        }

        if ($n < 2 && $this->categoryId[$n]) {
            $category = BoardCategory::findOne($this->categoryId[$n]);
            if ($category->depth == 1) {
                $children = ArrayHelper::map($category->getDescendants()->active()->enabled()->all(), 'id', function ($item) {
                    return ($item['depth'] > 2 ? str_repeat('-', $item['depth'] - 2) . ' ' : '') . $item['name'];
                });
            } else {
                $children = ArrayHelper::map($category->getChildren()->active()->enabled()->all(), 'id', 'name');
            }

            $dropdown = Html::activeDropDownList($this, 'categoryId[' . ($n + 1) . ']', $children, ['class' => 'form-control', 'prompt' => '']);
            $dropdowns[] = Html::tag('div', $dropdown, ['class' => 'form-group category-dropdown']);
        }
        return implode("\n", $dropdowns);
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

    public function getJs()
    {
        $url = Url::to(['select-category']);
        return <<<JS
var formName = '{$this->formName()}';
$(document).on('change', 'select[name*=categoryId]', function () {
    var select = $(this);
    var box = $(select.parents('.box')[0]);
    select.parent().nextAll('.form-group').remove();
    var id = select.val();
    var emptySelect = false;
    if (!id) {
        var prev = select.parent().prev('.form-group').find('select');
        if (prev.length) {
            id = prev.val();
            emptySelect = true;
        } else {
            $('#parameters-block').html('');
            return;
        }
    }
    $.ajax({
        url: '{$url}',
        method: "post",
        dataType: "json",
        data: {id: id, formName: formName},
        beforeSend: function () {
            box.prepend('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        },
        success: function(data, textStatus, jqXHR) {
            if (!emptySelect && data.items.length) {
                var dropdownBlock = $('#templates').find('.category-dropdown').clone();
                var dropdown = dropdownBlock.find('select');
                var num = $('#category-block').find('.form-group').length;
                dropdown.attr('name', formName + '[categoryId][' + num + ']');
                $.each(data.items, function (k, item) {
                    dropdown.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
                select.parent().after(dropdownBlock);
            }
            refreshParameters(data.params);
        },
        complete: function () {
            box.find('.overlay').remove();
         }
    });
});

function refreshParameters(params) {
    var paramsBlock = $('#parameters-block');
    paramsBlock.html(params);
}
JS;
    }
}