<?php

use yii\behaviors\SluggableBehavior;
use yii\db\Migration;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use common\models\Item;
use common\models\Restaurant;


/**
 * Class m220318_075125_item_slug
 */
class m220318_075125_item_slug extends Migration
{
    public $owner;

    public $slugAttribute = 'slug';

    public $attribute = 'item_name';

    public $uniqueValidator = ['targetAttribute' => ['restaurant_uuid', 'slug']];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $stores = Restaurant::find()
            ->all();

        $total = Item::find()->count();

        Console::startProgress(0, $total);

        $n = 0;

        foreach ($stores as $store)
        {
            $batchQuery = $store->getItems()
                ->andWhere(['like', 'slug', ""]);

            foreach ($batchQuery->batch(100) as $items)
            {
                foreach ($items as $item)
                {
                    $this->owner = $item;

                    Yii::$app->db->createCommand('UPDATE item SET slug="'.$this->getSlugValue().'" 
                        WHERE item_uuid="'.$item->item_uuid.'"')->execute();

                    $n++;
                }

                Console::updateProgress($n, $total);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220318_075125_item_slug cannot be reverted.\n";

        return false;
    }
    */

    /**
     * {@inheritdoc}
     */
    protected function getSlugValue()
    {
            $slugParts = [];

            foreach ((array) $this->attribute as $attribute) {
                $part = ArrayHelper::getValue($this->owner, $attribute);

                $slugParts[] = $part;
            }
            $slug = $this->generateSlug($slugParts);

        return $this->makeUnique($slug);
    }

    /**
     * This method is called by [[getValue]] to generate the slug.
     * You may override it to customize slug generation.
     * The default implementation calls [[\yii\helpers\Inflector::slug()]] on the input strings
     * concatenated by dashes (`-`).
     * @param array $slugParts an array of strings that should be concatenated and converted to generate the slug value.
     * @return string the conversion result.
     */
    protected function generateSlug($slugParts)
    {
        return \yii\helpers\Inflector::slug(implode('-', $slugParts));
    }

    /**
     * This method is called by [[getValue]] when [[ensureUnique]] is true to generate the unique slug.
     * Calls [[generateUniqueSlug]] until generated slug is unique and returns it.
     * @param string $slug basic slug value
     * @return string unique slug
     * @see getValue
     * @see generateUniqueSlug
     * @since 2.0.7
     */
    protected function makeUnique($slug)
    {
        $uniqueSlug = $slug;
        $iteration = 0;
        while (!$this->validateSlug($uniqueSlug)) {
            $iteration++;
            $uniqueSlug = $this->generateUniqueSlug($slug, $iteration);
        }

        return $uniqueSlug;
    }

    /**
     * Checks if given slug value is unique.
     * @param string $slug slug value
     * @return bool whether slug is unique.
     */
    protected function validateSlug($slug)
    {
        /* @var $validator UniqueValidator */
        /* @var $model BaseActiveRecord */
        $validator = Yii::createObject(array_merge(
            [
                'class' => \yii\validators\UniqueValidator::className(),
            ],
            $this->uniqueValidator
        ));

        $model = clone $this->owner;
        $model->clearErrors();
        $model->{$this->slugAttribute} = $slug;

        $validator->validateAttribute($model, $this->slugAttribute);
        return !$model->hasErrors();
    }

    /**
     * Generates slug using configured callback or increment of iteration.
     * @param string $baseSlug base slug value
     * @param int $iteration iteration number
     * @return string new slug value
     * @throws \yii\base\InvalidConfigException
     */
    protected function generateUniqueSlug($baseSlug, $iteration)
    {
        return $baseSlug . '-' . ($iteration + 1);
    }
}
