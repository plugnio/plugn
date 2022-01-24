<?php namespace common\tests;

use common\fixtures\CategoryItemFixture;

class CategoryItemTest extends \Codeception\Test\Unit
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
            'bankDiscounts' => CategoryItemFixture::className()];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->specify('Fixtures should be loaded', function() {
            expect('Check data loaded',
                CategoryItem::find()->one()
            )->notNull();
        });

        $this->specify('CategoryItem model fields validation', function () {
            $model = new CategoryItem;

            expect('should not accept empty category_id', $model->validate(['category_id']))->false();
            expect('should not accept empty item_uuid', $model->validate(['item_uuid']))->false();

            $model->category_id = 12312312313;
            expect('should not accept invalid category_id', $model->validate(['category_id']))->false();

            $model->item_uuid = 12312312313;
            expect('should not accept invalid item_uuid', $model->validate(['item_uuid']))->false();
        });
    }
}