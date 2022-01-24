<?php namespace common\tests;

use common\fixtures\ItemImageFixture;

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
            'images' => ItemImageFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                ItemImage::find()->one()
            )->notNull();
        });

        $this->specify('ItemImage model fields validation', function () {
            $model = new ItemImage;

            expect('should not accept empty item_uuid', $model->validate(['item_uuid']))->false();
            expect('should not accept empty product_file_name', $model->validate(['product_file_name']))->false();

            $model->item_uuid = 12312312313;
            expect('should not accept invalid item_uuid', $model->validate(['item_uuid']))->false();
        });
    }
}