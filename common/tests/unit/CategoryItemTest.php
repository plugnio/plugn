<?php namespace common\tests;

use common\fixtures\CategoryItemFixture;
use common\models\CategoryItem;
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
            'categoryItems' => CategoryItemFixture::class
        ];
    }

    /**
     * Tests validator
     */
    public function testValidators()
    {
        $this->assertNotNull(CategoryItem::find()->one(), 'Check data loaded');

        $model = new CategoryItem;
        $this->assertFalse($model->validate(['category_id']), 'should not accept empty category_id');
        $this->assertFalse($model->validate(['item_uuid']), 'should not accept empty item_uuid');

        $model->category_id = 12312312313;
        $this->assertFalse($model->validate(['category_id']), 'should not accept invalid category_id');

        $model->item_uuid = 12312312313;
        $this->assertFalse($model->validate(['item_uuid']), 'should not accept invalid item_uuid');
    }
}