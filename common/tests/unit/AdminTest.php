<?php
namespace common\tests;

use backend\models\Admin;
use common\fixtures\AdminFixture;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    protected $tester;
    
    public function _fixtures()
    {
        return [
            'admin' => AdminFixture::class
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test fixtures are loaded
     */
    public function testFixturesAreLoaded()
    {
        $admin = Admin::find()->one();
        $this->assertNotNull($admin, 'Admin fixture should be loaded');
    }

    /**
     * Test field validations
     */
    public function testFieldValidations()
    {
        $admin = new Admin();
        
        // Test empty fields
        $this->assertFalse($admin->validate(['admin_name']), 'Should not accept empty admin_name');
        $this->assertFalse($admin->validate(['admin_email']), 'Should not accept empty admin_email');
        
        // Test invalid email
        $admin->admin_email = 'randomString';
        $this->assertFalse($admin->validate(['admin_email']), 'Should not accept invalid email');
        
        // Test valid email
        $admin->admin_email = 'demo@admin.com';
        $this->assertTrue($admin->validate(['admin_email']), 'Should accept valid email');
    }

    /**
     * Test CRUD operations
     */
    public function testCrudOperations()
    {
        // Test Create
        $admin = new Admin();
        $admin->admin_name = 'Magan';
        $admin->admin_email = 'unique@admin.com';
        $admin->admin_auth_key = '';
        $admin->setPassword('admin2');
        $admin->admin_role = Admin::ROLE_ADMIN;
        
        $this->assertTrue($admin->save(), 'Admin should be created successfully');
        $this->assertNotNull(
            Admin::findOne(['admin_name' => 'Magan']),
            'Should find newly created admin'
        );

        // Test Update
        $admin = Admin::findOne(['admin_id' => 1]);
        $this->assertNotNull($admin, 'Should find admin with ID 1');
        
        $admin->admin_name = 'Chhagan';
        $admin->admin_auth_key = '';
        
        $this->assertTrue($admin->save(), 'Admin should be updated successfully');
        $this->assertNotNull(
            Admin::findOne(['admin_name' => 'Chhagan']),
            'Should find updated admin'
        );
    }
}
