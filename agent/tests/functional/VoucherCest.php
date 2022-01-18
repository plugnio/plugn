<?php

namespace agent\tests;

use agent\models\Voucher;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\RestaurantFixture;
use common\fixtures\WebLinkFixture;

class VoucherCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'weblinks' => WebLinkFixture::className(),
            'agents' => AgentFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->store = $this->agent->getStores()->one();

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToList(FunctionalTester $I) {
        $I->wantTo('Validate voucher > list api');
        $I->sendGET('v1/voucher');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDetail(FunctionalTester $I) {
        $model = Voucher::find()->one();

        $I->wantTo('Validate voucher > view api');
        $I->sendGET('v1/voucher', [
            'voucher_id' => $model->voucher_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToCreate(FunctionalTester $I) {
        $I->wantTo('Validate voucher > create api');
        $I->sendPOST('v1/voucher/create', [
            'code' => 'testv',
            'description' => 'test',
            'description_ar' => 'test',
            'discount_type' => Voucher::DISCOUNT_TYPE_PERCENTAGE,
            'discount_amount' => 5,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {
        $model = Voucher::find()->one();

        $I->wantTo('Validate voucher > update api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/voucher/' . $model->voucher_id, [
            'code' => 'testvv',
            'description' => 'test',
            'description_ar' => 'test',
            'discount_type' => Voucher::DISCOUNT_TYPE_PERCENTAGE,
            'discount_amount' => 5,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdateStatus(FunctionalTester $I) {
        $model = Voucher::find()->one();

        $I->wantTo('Validate voucher > update status api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/voucher/update-status' , [
            'voucher_id' => $model->voucher_id,
            'voucherStatus' => Voucher::VOUCHER_STATUS_EXPIRED
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {
        $model = Voucher::find()->one();

        $I->wantTo('Validate voucher > delete api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendDELETE('v1/voucher/' . $model->voucher_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}