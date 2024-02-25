<?php

use yii\db\Migration;

/**
 * Class m230618_115655_cities
 */
class m230618_115655_cities extends Migration
{
    //yii migrate/to m230618_115655_cities
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("country", "currency_code", $this->char(3));

        $this->addColumn('city', 'state_id', $this->integer(11)->after('city_id'));

        $this->createIndex(
            'idx-city-state_id',
            'city',
            'state_id'
        );

        $this->addForeignKey(
            'fk-city-state_id',
            'city',
            'state_id',
            'state',
            'state_id'
        );

        $json = file_get_contents(__DIR__ .'/world-cities_json.json');

        $json_data = json_decode($json,true);

        //{"country": "India", "geonameid": 1253573, "name": "Vadodara", "subcountry": "Gujarat"},

        $countries = \yii\helpers\ArrayHelper::map(\agent\models\Country::find()->all(), 'country_name', "country_id");

        $states = \yii\helpers\ArrayHelper::map(\common\models\State::find()->all(), 'name', "state_id");

        $data = [];

        foreach ($json_data as $city) {

            if($city['country'] == "Kuwait")
                continue;

            if(isset($countries[$city['country']])) {
                $country_id = $countries[$city['country']];
            } else {
                $country = new \agent\models\Country();
                $country->country_name = $city['country'];
                if(!$country->save()) {
                    print_r($city);
                    print_r($country->errors); die();
                }

                $country_id = $country->country_id;

                $countries[$city['country']] = $country_id;
            }

            if(isset($states[$city['subcountry']])) {
                $state_id = $states[$city['subcountry']];
            } else if($city['subcountry']) {
                $state = new \common\models\State();
                $state->country_id  = $country_id;
                $state->name = $city['subcountry'];
                if(!$state->save()) {
                    print_r($city);
                    print_r($state->errors); die();
                }

                $state_id = $state->state_id;

                $states[$city['subcountry']] = $state_id;
            }

            $data[] = [
                'country_id' => $country_id,
                'state_id' => $state_id,
                'city_name' => $city['name'],//htmlentities($city['name'], ENT_COMPAT, "UTF-8"),
                'city_name_ar' => $city['name']//htmlentities($city['name'], ENT_COMPAT, "UTF-8")
            ];
        }

        Yii::$app->db->createCommand()->batchInsert('city', ['country_id', 'state_id', 'city_name', 'city_name_ar'], $data)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230618_115655_cities cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230618_115655_cities cannot be reverted.\n";

        return false;
    }
    */
}
