<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use common\models\PaymentMethod;

/**
 * Github REST API class
 *
 * @author Saoud Al-Turki <saoud@plugn.io>
 * @link http://www.plugn.io
 */
class GithubComponent extends Component {

    private $apiEndpoint = 'https://api.github.com/repos/plugnio/plugn-ionic';

    public $token;

    public $branch;

    /**
     * @inheritdoc
     */
    public function init() {
        // Fields required by default
        $requiredAttributes = ['token', 'branch'];

        // Process Validation
        foreach ($requiredAttributes as $attribute) {
            if ($this->$attribute === null) {
                throw new InvalidConfigException(strtr('"{class}::{attribute}" cannot be empty.', [
                    '{class}' => static::className(),
                    '{attribute}' => '$' . $attribute
                ]));
            }
        }


        parent::init();
    }

    /**
     * Returns the contents of a single commit reference
     */
    public function getLastCommit($branch = null) {

        if($branch == null)
          $branch = $this->branch;


        $lastCommitEndpoint = $this->apiEndpoint . "/commits/" . $branch;


        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($lastCommitEndpoint)
                ->addHeaders([
                    'Authorization' => 'token ' . $this->token,
                    'User-Agent' => 'request',
                ])
                ->send();

        return $response;
    }

    /**
     * Creates a reference for repository.
     * @param type $sha The SHA1 value for the last commit.
     * @param type $branch_name name of branch
     * @return type
     */
    public function createBranch($sha, $branch_name) {
        $createBranchEndpoint = $this->apiEndpoint . "/git/refs";

        $branchParams = [
            "sha" => $sha,
            "ref" => $branch_name,
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($createBranchEndpoint)
                ->setFormat(Client::FORMAT_JSON)
                ->setData($branchParams)
                ->addHeaders([
                    'Authorization' => 'token ' . $this->token,
                    'User-Agent' => 'request',
                ])
                ->send();

        return $response;
    }

    /**
     * Creates a new file or replaces an existing file in a repository.
     * @param type $content The new file content, using Base64 encoding.
     * @return type
     */
    public function createFileContent($content, $branch_name, $path, $commitMessage = null) {
        $createBranchEndpoint = $this->apiEndpoint . "/contents/" . $path;

        if($commitMessage == null){
          $commitMessage = "first commit for $branch_name store";
        }
        $branchParams = [
            "message" => $commitMessage,
            "content" => $content,
            "branch" => $branch_name
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('PUT')
                ->setUrl($createBranchEndpoint)
                ->setFormat(Client::FORMAT_JSON)
                ->setData($branchParams)
                ->addHeaders([
                    'Authorization' => 'token ' . $this->token,
                    'User-Agent' => 'request',
                ])
                ->send();

        return $response;
    }

}
