<?php namespace common\tests;

use common\fixtures\WebLinkFixture;
use common\models\WebLink;
use Codeception\Specify;

class WebLinkTest extends \Codeception\Test\Unit
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
            'weblinks' => WebLinkFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(WebLink::find()->one(), 'Check data loaded');
        
        $model = new WebLink();
        $this->assertFalse($model->validate(['url']), 'should not accept empty url');
        $this->assertFalse($model->validate(['web_link_title']), 'should not accept empty web_link_title');
        $this->assertFalse($model->validate(['web_link_title_ar']), 'should not accept empty web_link_title_ar');

        $model->url = 12312312313;
        $this->assertFalse($model->validate(['url']), 'should not accept invalid url');

        $model->restaurant_uuid = 12312312313;
        $this->assertFalse($model->validate(['restaurant_uuid']), 'should not accept invalid restaurant_uuid');

        $model->web_link_type = 12312312313;
        $this->assertFalse($model->validate(['web_link_type']), 'should not accept invalid web_link_type');

        $model->web_link_type = WebLink::WEB_LINK_TYPE_WEBSITE_URL;
        $this->assertTrue($model->validate(['web_link_type']), 'should accept valid web_link_type');
    }
}