<?php namespace common\tests;

use common\fixtures\StoreWebLinkFixture;
use common\models\StoreWebLink;

class StoreWebLinkTest extends \Codeception\Test\Unit
{
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
            'links' => StoreWebLinkFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(StoreWebLink::find()->one(), 'Check bank discount loaded');

        $model = new StoreWebLink();
        $this->assertFalse($model->validate(['web_link_id']), 'should not accept empty web_link_id');
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept empty restaurant_uuid');

        $model->web_link_id = 12312312313;
        $this->assertFalse($model->validate(['web_link_id']), 'should not accept invalid web_link_id');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');
    }
}