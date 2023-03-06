<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\RestaurantFixture;
use common\fixtures\WebLinkFixture;
use common\models\WebLink;

class WebLinkCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'weblinks' => WebLinkFixture::className(),
            'agents' => AgentFixture::className(),
            'agent_assignments' => AgentAssignmentFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->store = $this->agent->getAccountsManaged()->one();

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);
    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToList(FunctionalTester $I) {
        $I->wantTo('Validate weblink > list api');
        $I->sendGET('v1/web-link');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDetail(FunctionalTester $I) {
        $weblink = WebLink::find()->one();

        $I->wantTo('Validate weblink > detail api');
        $I->sendGET('v1/web-link/detail', [
            'web_link_id' => $weblink->web_link_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToCreate(FunctionalTester $I) {
        $I->wantTo('Validate weblink > create api');
        $I->sendPOST('v1/web-link/create', [
            'web_link_type' => 'Facebook',
            'url' => 'facebook.com',
            'web_link_title' => 'facebook.com',
            'web_link_title_ar' => 'facebook.com'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {
        $weblink = $this->store->getWebLinks()->one();

        $I->wantTo('Validate weblink > update api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/web-link/' . $weblink->web_link_id, [
            'web_link_type' => 'Facebook',
            'url' => 'facebook.com',
            'web_link_title' => 'facebook.com',
            'web_link_title_ar' => 'facebook.com'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {
        $weblink = $this->store->getWebLinks()->one();

        $I->wantTo('Validate weblink > delete api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendDELETE('v1/web-link/' . $weblink->web_link_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}