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
            'weblinks' => WebLinkFixture::className()
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check weblink loaded',
                WebLink::find()->one()
            )->notNull();
        });

        $this->specify('WebLink model fields validation', function () {
            $model = new WebLink;

            expect('should not accept empty url', $model->validate(['url']))->false();
            expect('should not accept empty web_link_title', $model->validate(['web_link_title']))->false();
            expect('should not accept empty web_link_title_ar', $model->validate(['web_link_title_ar']))->false();

            $model->restaurant_uuid = 12312312313;
            expect('should not accept invalid restaurant_uuid', $model->validate(['restaurant_uuid']))->false();

            $model->web_link_type = 12312312313;
            expect('should not accept invalid web_link_type', $model->validate(['web_link_type']))->false();

            $model->web_link_type = WebLink::WEB_LINK_TYPE_WEBSITE_URL;
            expect('should accept valid web_link_type', $model->validate(['web_link_type']))->true();

        });
    }
}