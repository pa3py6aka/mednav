<?php

namespace frontend\widgets;


use yii\base\Widget;

class CompanyMenuWidget extends Widget
{
    public $company;

    public function run()
    {
        return $this->render('company_menu', ['company' => $this->company]);
    }
}