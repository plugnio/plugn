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
            'branchs' => RestaurantBranchFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                RestaurantBranch::find()->one()
            )->notNull();
        });

        $this->specify('RestaurantBranch model fields validation', function () {
            $model = new RestaurantBranch;

            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty branch_name_en', $model->validate(['branch_name_en']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
        });
    }
}