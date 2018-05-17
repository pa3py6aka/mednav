<?php

namespace core\forms\Company;


use yii\base\Model;

class CompanyForm extends Model
{
    public $slug;

    public $form;
    public $name;
    public $logo;
    public $categories;
    public $site;
    public $geoId;
    public $address;
    public $phones;
    public $fax;
    public $email;
    public $info;
    public $title;
    public $shortDescription;
    public $description;
    public $tags;
    public $photos;

    public function rules()
    {
        return [

        ];
    }
}