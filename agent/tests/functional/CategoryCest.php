<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\BusinessLocationFixture;
use common\fixtures\CategoryFixture;
use common\fixtures\CountryFixture;
use common\fixtures\RestaurantFixture;

class CategoryCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'agent_assignments' => AgentAssignmentFixture::className(),
            'categories' => CategoryFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->store = $this->agent->getAccountsManaged()->one();

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToList(FunctionalTester $I) {
        $I->wantTo('Validate category > list api');
        $I->sendGET('v1/category');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToViewDetail(FunctionalTester $I) {

        $model = $this->store->getCategories()->one();

        $I->wantTo('Validate category > detail api');
        $I->sendGET('v1/category/detail', [
            'category_id' => $model->category_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToViewItemList(FunctionalTester $I) {

        $model = $this->store->getCategories()->one();

        $I->wantTo('Validate category > detail api');
        $I->sendGET('v1/category/item-list', [
            'category_id' => $model->category_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToAdd(FunctionalTester $I) {

        $model = $this->store->getCategories()->one();

        $I->wantTo('Validate category > add api');
        $I->sendPOST('v1/category', [
            'title' => 'Cat name',
            'title_ar' => 'Cat name',
            'subtitle' => 'Sub title',
            'subtitle_ar' => 'Sub title',
            'sort_number' => 1,
            'category_image' => null
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {

        $model = $this->store->getCategories()->one();

        $I->wantTo('Validate category > update api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/category/'. $model->category_id, [
            'title' => 'Cat name',
            'title_ar' => 'Cat name',
            'subtitle' => 'Sub title',
            'subtitle_ar' => 'Sub title',
            'sort_number' => 1,
            'category_image' => null
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {

        $model = $this->store->getCategories()->one();

        $I->wantTo('Validate category > delete api');
        $I->sendDELETE('v1/category/'. $model->category_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdatePosition(FunctionalTester $I) {

        $model = $this->store->getCategories()->one();
        $model2 = $this->store->getCategories()->offset(1)->one();

        $I->wantTo('Validate category > update position api');
        $I->sendPOST('v1/category/update-position', [
            'items' => [
                $model->category_id,
                $model2->category_id,
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUploadImage(FunctionalTester $I) {

        $model = $this->store->getCategories()->one();
//var_dump(codecept_data_dir() . 'files/sample.jpg');
//die();

        $response = \Yii::$app->temporaryBucketResourceManager->save(
            null,
            'sample.jpg',
            [],
            codecept_data_dir() . 'files/sample.jpg',
            'image/jpg'
        );

        $I->wantTo('Validate category > upload image api');
        $I->sendPOST('v1/category/upload-image', [
            'category_image' => basename($response['ObjectURL']),
            'category_id' => $model->category_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}