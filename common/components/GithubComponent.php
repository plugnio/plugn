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
     * Returns file SHA
     */
    public function getFileSHA($path, $branch = null) {

        if($branch == null)
          $branch = $this->branch;

        $lastCommitEndpoint = $this->apiEndpoint . "/contents/" . $path . "?ref=" . $branch;

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
     * Delete a file in a repository.
     * @param type $sha The SHA1 value for the last commit.
     * @param type $branch_name name of branch
     * @return type
     */
    public function deleteFile($filePath, $sha, $branch) {

        $deleteFileEndpoint = $this->apiEndpoint . "/contents/" . $filePath;

        $deleteFileParams = [
            "message" => "Delete " . $filePath,
            "sha" => $sha,
            "branch" => $branch
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('DELETE')
                ->setUrl($deleteFileEndpoint)
                ->setFormat(Client::FORMAT_JSON)
                ->setData($deleteFileParams)
                ->addHeaders([
                    'Authorization' => 'token ' . $this->token,
                    'User-Agent' => 'request',
                ])
                ->send();

        return $response;
    }

    /**
     * The Repo Merging API supports merging branches in a repository.
     * @param type $sha The SHA1 value for the last commit.
     * @param type $branch_name name of branch
     * @return type
     */
    public function mergeABranch($commitMessage, $base, $head) {

        $mergeABranchEndpoint = $this->apiEndpoint . "/merges";

        $mergeABranchParams = [
            "commit_message" =>  $commitMessage,
            "base" => $base,
            "head" => $head
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($mergeABranchEndpoint)
                ->setFormat(Client::FORMAT_JSON)
                ->setData($mergeABranchParams)
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
    public function createFileContent($content, $branch_name, $path, $commitMessage = null, $sha = null) {
        $createBranchEndpoint = $this->apiEndpoint . "/contents/" . $path;

        $branchParams = [
            "message" => $commitMessage ? $commitMessage : "first commit for $branch_name store",
            "content" => $content,
            "branch" => $branch_name,
            "sha" => $sha ? $sha : ''
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
