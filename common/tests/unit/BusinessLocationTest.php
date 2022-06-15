<?php namespace common\tests;

use common\fixtures\BusinessLocationFixture;
use Codeception\Specify;
use common\models\BusinessLocation;

class BusinessLocationTest extends \Codeception\Test\Unit
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
            'locations' => BusinessLocationFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                BusinessLocation::find()->one()
            )->notNull();
        });

        $this->specify('BusinessLocation model fields validation', function () {
            $model = new BusinessLocation;

            expect('should not accept empty country_id', $model->validate(['country_id']))->false();
            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();
            expect('should not accept empty business_location_name', $model->validate(['business_location_name']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->country_id = 12312312313;
            expect('should not accept invalid country_id', $model->validate(['country_id']))->false();
        });
    }
}