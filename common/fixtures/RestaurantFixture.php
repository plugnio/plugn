<?php
namespace common\fixtures;

use yii\test\ActiveFixture;

class RestaurantFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Restaurant';
    
    	public $depends = [
        'common\fixtures\VendorFixture',
    ];

}