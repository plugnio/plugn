<?php

namespace backend\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class ChartWidget extends Widget
{ 

  public $id;
  public $title;
  public $subtitle;
  public $type;
  public $color;
  public $currency_code;

    public $seriesData = [];
    public $categories = [];
    public $chartdata = [];

    public function init()
    {
        parent::init();
        //ob_start();
    }

    public function run()
    {  
        $this->categories = [];
         
        $this->seriesData = [];
         
        foreach ($this->chartdata as $row) {
          if (isset($row['month'])) {
            $this->categories[] = $row['month'];
          } else if (isset($row['day'])) {
            $this->categories[] = $row['day'];
          } else if (isset($row['item_name'])) {
            $this->categories = $row['item_name'];
          }

          $this->seriesData[] = $row['total'];
        }  

        //$content = ob_get_clean();
        return $this->render('chart', [
            //'action' => Url::to([$this->action]),
            'id' => $this->id,
            'currency_code' => $this->currency_code,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            "type" => $this->type,
            "color" => $this->color,
            "seriesData" => $this->seriesData,
            "categories" => $this->categories
        ]);
    }
}
