<?php namespace common\tests;

use common\fixtures\ItemImageFixture;
use common\models\ItemImage;

class ItemImageTest extends \Codeception\Test\Unit
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
            'images' => ItemImageFixture::class];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(ItemImage::find()->one(), 'Check data loaded');

        $model = new ItemImage();
        $this->assertFalse($model->validate(['item_uuid']), 'should not accept empty item_uuid');
        $this->assertFalse($model->validate(['product_file_name']), 'should not accept empty product_file_name');

        $model->item_uuid = 12312312313;
        $this->assertFalse($model->validate(['item_uuid']), 'should not accept invalid item_uuid');
    }
}