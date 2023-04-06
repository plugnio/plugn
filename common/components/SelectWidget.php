<?php

namespace common\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class SelectWidget extends Widget
{
    public $action = 'dropdown';

    public $form;
    public $formModal;
    public $formModalName;
    public $modalName;
    public $labelAttribute;
    public $valueAttribute;

    public function init()
    {
        parent::init();
        //ob_start();
    }

    public function run()
    {
        //$content = ob_get_clean();
        return $this->render('select', [
            'action' => Url::to([$this->action]),
            'form' => $this->form,
            'formModal' => $this->formModal,
            'labelAttribute' => $this->labelAttribute,
            'valueAttribute' => $this->valueAttribute,
            "formModalName" => $this->formModalName,
            "modalName" => $this->modalName
        ]);
    }
}