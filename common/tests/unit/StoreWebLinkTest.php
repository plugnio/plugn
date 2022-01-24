<?php namespace common\tests;

use common\fixtures\StoreWebLinkFixture;
use Codeception\Specify;
use common\models\StoreWebLink;

class StoreWebLinkTest extends \Codeception\Test\Unit
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
            'links' => StoreWebLinkFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check bank discount loaded',
                StoreWebLink::find()->one()
            )->notNull();
        });

        $this->specify('StoreWebLink model fields validation', function () {
            $model = new StoreWebLink;

            expect('should not accept empty web_link_id', $model->validate(['web_link_id']))->false();
            expect('should not accept empty restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->web_link_id = 12312312313;
            expect('should not accept invalid web_link_id', $model->validate(['web_link_id']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

        });
    }
}