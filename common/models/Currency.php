<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "currency".
 *
 * @property int $currency_id
 * @property string $title
 * @property string $code
 *
 * @property Restaurant[] $restaurants
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'code'], 'required'],
            [['title', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'currency_id' => 'Currency ID',
            'title' => 'Title',
            'code' => 'Code',
        ];
    }

    public static function getDataFromApi($useTransaction = true) {

        if($useTransaction)
            $transaction = Yii::$app->db->beginTransaction();

        $api_key = Yii::$app->params['currencylayer_api_key'];
        $response = file_get_contents('http://apilayer.net/api/live?access_key=' . $api_key . '&source=USD');

        $data = json_decode($response);

        $insertData = [];
        $currentData = include(Yii::getAlias('@common/fixtures/data/currency.php'));

        $arrSortOrder = \yii\helpers\ArrayHelper::map($currentData, 'code', 'sort_order');
        $arrCurrencyName = \yii\helpers\ArrayHelper::map($currentData, 'code', 'title');
        $arrCurrencySymbol = \yii\helpers\ArrayHelper::map($currentData, 'code', 'currency_symbol');


        if (isset($data->quotes)) {

            foreach ($data->quotes as $label => $rate) {

                $currency = substr($label, 3);

                if (strlen($currency) > 3) {
                    continue;
                }


                $insertData[] = [
                    $currency,
                    isset($arrCurrencyName[$currency]) ? $arrCurrencyName[$currency] : null,
                    isset($arrCurrencySymbol[$currency]) ? $arrCurrencySymbol[$currency] : null,
                    $rate,
                    isset($arrSortOrder[$currency]) ? $arrSortOrder[$currency] : null,
                    new Expression('NOW()')
                ];
            }

            Currency::deleteAll();

            Yii::$app->db->createCommand()->batchInsert('currency', [
                'code',
                'title',
                'currency_symbol',
                'rate',
                'sort_order',
                'datetime'
            ], $insertData
            )->execute();

            if ($useTransaction)
                $transaction->commit();

            return "Currency record updated!";
        } else {
            return "no record found";
        }
    }

    public function getSign()
    {
        $arr = [
            'AUD' => '$',
            'BGN' => 'лв',
            'BRL' => 'R$',
            'CAD' => '$',
            'CHF' => "CHF",
            'CNY' => '¥',
            'CZK' => 'Kč',
            'DKK' => 'kr',
            'EUR' => '€',
            'GBP' => '£',
            'HKD' => '$',
            'HRK' => 'kn',
            'HUF' => 'Ft',
            "IDR" => 'Rp',
            'ILS' => '₪',
            'INR' => '₹',
            'JPY' => '¥',
            'KRW' => '₩',
            'MXN' => '$',
            'MYR' => 'RM',
            'NOK' => 'kr',
            'NZD' => '$',
            'PHP' => '₱',
            'PLN' => 'zł',
            'RON' => 'lei',
            'RUB' => '₽',
            'SEK' => 'kr',
            'SGD' => '$',
            'THB' => '฿',
            'TRY' => '₺',
            'USD' => '$',
            'ZAR' => 'R'
        ];

        return isset($arr[$this->currency])? $arr[$this->currency]: $this->currency;
    }

    /**
     * Return currency symbol by currency code
     * @param string $currency_code
     * @return httpcode
     */
    public function currencySymbol($currency_code) {

        switch ($currency_code) {

            case( 'AUD' ):
                $cur = "&#36;";
                break;

            case( 'BGN' ):
                $cur = "&#1083;&#1074;";
                break;

            case( 'BRL' ):
                $cur = "R&#36;";
                break;

            case( 'CAD' ):
                $cur = "C&#36;";
                break;

            case( 'CHF' ):
                $cur = "&#165;";
                break;

            case( 'CZK' ):
                $cur = "K&#269;";
                break;

            case( 'DKK' ):
                $cur = "kr";
                break;

            case( 'EUR' ):
                $cur = "&#8364;";
                break;

            case( 'GBP' ):
                $cur = "&#163;";
                break;

            case( 'HKD' ):
                $cur = "&#36;";
                break;

            case( 'HRK' ):
                $cur = "kn";
                break;

            case( 'HUF' ):
                $cur = "Ft";
                break;

            case( 'IDR' ):
                $cur = "Rp";
                break;

            case( 'ILS' ):
                $cur = "&#8362;";
                break;

            case( 'INR' ):
                $cur = "&#8377;";
                break;

            case( 'JPY' ):
                $cur = "&#165;";
                break;

            case( 'KRW' ):
                $cur = "&#8361;";
                break;

            case( 'LTL' ):
                $cur = "Lt";
                break;

            case( 'LVL' ):
                $cur = "Ls";
                break;

            case( 'MXN' ):
                $cur = "&#36;";
                break;

            case( 'MYR' ):
                $cur = "RM";
                break;

            case( 'NOK' ):
                $cur = "kr";
                break;

            case( 'NZD' ):
                $cur = "&#36;";
                break;

            case( 'PHP' ):
                $cur = "&#8369;";
                break;

            case( 'PLN' ):
                $cur = "&#122;&#322;";
                break;

            case( 'RON' ):
                $cur = "&#108;&#101;&#105;";
                break;

            case( 'RUB' ):
                $cur = "₽";
                break;

            case( 'SEK' ):
                $cur = "kr";
                break;

            case( 'SGD' ):
                $cur = "&#36;";
                break;

            case( 'THB' ):
                $cur = "&#3647;";
                break;

            case( 'TRY' ):
                $cur = "&#8356;";
                break;

            case( 'USD' ):
                $cur = "&#36;";
                break;

            case( 'ZAR' ):
                $cur = "R";
                break;

            case( 'CNY' ):
                $cur = "元";
                break;
            default:
                $cur = $currency_code;
                break;
        }
        return $cur;
    }
    /**
     * Gets query for [[Restaurants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants($modelClass = "\common\models\Restaurant")
    {
        return $this->hasMany($modelClass::className(), ['currency_id' => 'currency_id']);
    }

}
