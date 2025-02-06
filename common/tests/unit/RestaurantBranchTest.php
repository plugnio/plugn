<?php namespace common\tests;

use common\fixtures\RestaurantBranchFixture;
use Codeception\Specify;
use common\models\RestaurantBranch;

class RestaurantBranchTest extends \Codeception\Test\Unit
{
    use Specify;
    
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function _fixtures(){
        return [
            'branchs' => RestaurantBranchFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(RestaurantBranch::find()->one(), 'Check data loaded');

      //  $this->specify('RestaurantBranch model fields validation', function () {
            $model = new RestaurantBranch;

            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');
            $this->assertFalse($model->validate(['branch_name_en']), 'should not accept empty branch_name_en');

            $model->restaurant_uuid = 12312312313;
            $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
        //});
    }
}