<?php

namespace common\components;

use yii\base\InvalidConfigException;
use yii\httpclient\Client;

class BlogManager
{
    public $apiEndpoint = 'http://localhost:8080/v1';

    public $token;

    /**
     * @inheritdoc
     */
    public function init() {
        // Fields required by default
        $requiredAttributes = ['token'];

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

    public function listPost($page, $query = '') {

        $deploySiteEndpoint = $this->apiEndpoint . "/post?per_page=2&page" . $page . '&query=' . $query;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($deploySiteEndpoint)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

    public function viewPost($id) {

        $deploySiteEndpoint = $this->apiEndpoint . "/post/" . $id;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($deploySiteEndpoint)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

    /**
     * creates blog post
     * ---------------------------
    {
    "post_image": null,
    "post_video": null,
    "sort_number": 1,
    "slug": "fashion2",
    "blogPostCategories" : [{
    "blog_category_id": 1
    }],
    "blogPostDescriptions" : [{
    "language_code": "en",
    "title": "Fashion 75",
    "description": "fashion items 22"
    }]
    }
     */
    public function createPost($params) {

        $endpoint = $this->apiEndpoint . "/post";

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($endpoint)
            ->setFormat(Client::FORMAT_JSON)
            ->setData($params)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

    /**
     * update blog post
     * ---------------------------
     {
        "post_image": null,
        "post_video": null,
        "sort_number": 1,
        "slug": "fashion2",
        "blogPostCategories" : [{
            "blog_category_id": 1
        }],
        "blogPostDescriptions" : [{
            "language_code": "en",
            "title": "Fashion 75",
            "description": "fashion items 22"
        }]
     }
     */
    public function updatePost($id, $params) {

        $endpoint = $this->apiEndpoint . "/post/" . $id;

        $client = new Client();

        return $client->createRequest()
            ->setMethod('PATCH')
            ->setUrl($endpoint)
            ->setFormat(Client::FORMAT_JSON)
            ->setData($params)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();
    }

    /**
     * delete blog post
     * @param $id
     * @return \yii\httpclient\Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function deletePost($id) {

        $apiEndpoint = $this->apiEndpoint . "/post/" . $id;

        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('DELETE')
            ->setUrl($apiEndpoint)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

    /**
     * list categories
     * @param $page
     * @param $query
     * @return \yii\httpclient\Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function listCategory($page, $query = '') {

        $deploySiteEndpoint = $this->apiEndpoint . "/category?per_page=2&page" . $page . '&query=' . $query;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($deploySiteEndpoint)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

    /**
     * return category details
     * @param $id
     * @return \yii\httpclient\Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function viewCategory($id) {

        $deploySiteEndpoint = $this->apiEndpoint . "/category/" . $id;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($deploySiteEndpoint)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

    /**
     * creates blog category
     * ---------------------------
    {
        "category_image": null,
        "parent_category_id": null,
        "sort_number": 1,
        "slug": "fashion2",
        "blogCategoryDescriptions" : [{
            "id": 19,
            "language_code": "en",
            "title": "Fashion 75",
            "description": "fashion items 22"
        }]
    }
     */
    public function createCategory($params) {

        $endpoint = $this->apiEndpoint . "/category";

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($endpoint)
            ->setFormat(Client::FORMAT_JSON)
            ->setData($params)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

    /**
     * update blog category
     * ---------------------------
    {
        "category_image": null,
        "parent_category_id": null,
        "sort_number": 1,
        "slug": "fashion2",
        "blogCategoryDescriptions" : [{
            "id": 19,
            "language_code": "en",
            "title": "Fashion 75",
            "description": "fashion items 22"
        }]
    }
     */
    public function updateCategory($id, $params) {

        $endpoint = $this->apiEndpoint . "/category/" . $id;

        $client = new Client();

        return $client->createRequest()
            ->setMethod('PATCH')
            ->setUrl($endpoint)
            ->setFormat(Client::FORMAT_JSON)
            ->setData($params)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();
    }

    /**
     * delete blog category
     * @param $id
     * @return \yii\httpclient\Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function deleteCategory($id) {

        $apiEndpoint = $this->apiEndpoint . "/category/" . $id;

        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('DELETE')
            ->setUrl($apiEndpoint)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

}