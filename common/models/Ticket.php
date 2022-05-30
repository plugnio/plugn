<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "ticket".
 *
 * @property string $ticket_uuid
 * @property string|null $restaurant_uuid
 * @property int|null $agent_id
 * @property int|null $staff_id
 * @property string|null $ticket_detail
 * @property int|null $ticket_status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Agent $agent
 * @property Restaurant $restaurantUu
 * @property Staff $staff
 * @property TicketAttachment[] $ticketAttachments
 * @property TicketComment[] $ticketComments
 */
class Ticket extends \yii\db\ActiveRecord
{
    public $attachments = [];

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 10;
    const STATUS_IN_PROGRESS = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_uuid'], 'required'],
            [['agent_id', 'staff_id', 'ticket_status'], 'integer'],
            [['ticket_detail'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['ticket_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['ticket_uuid'], 'unique'],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['agent_id' => 'agent_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::className(), 'targetAttribute' => ['staff_id' => 'staff_id']],
        ];
    }

    /**
     *
     * @return type
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'ticket_uuid',
                ],
                'value' => function () {
                    if (!$this->ticket_uuid)
                        $this->ticket_uuid = 'ticket_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();

                    return $this->ticket_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ticket_uuid' => Yii::t('app', 'Ticket Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'agent_id' => Yii::t('app', 'Agent ID'),
            'staff_id' => Yii::t('app', 'Staff ID'),
            'ticket_detail' => Yii::t('app', 'Ticket Detail'),
            'ticket_status' => Yii::t('app', 'Ticket Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->_moveAttachments();
    }

    /**
     * move attachments from temp s3 bucket
     * @return type
     */
    public function _moveAttachments() {

        $sourceBucket = Yii::$app->temporaryBucketResourceManager->bucket;

        foreach ($this->attachments as $value) {

            $output = Yii::$app->security->generateRandomString();

            //$source = Yii::$app->temporaryBucketResourceManager->bucket . '/' . $value;

            try {

                $extension = pathinfo($this->candidate_video, PATHINFO_EXTENSION);

                $file_s3_path = 'attachments/' . $output . '.' . $extension;

                Yii::$app->resourceManager->copy($value, $file_s3_path, $sourceBucket);

                $attachment = new Attachment();
                $attachment->file_path = $file_s3_path;
                $attachment->save();

                $ta = new TicketAttachment();
                $ta->ticket_uuid = $this->ticket_uuid;
                $ta->attachment_uuid = $attachment->attachment_uuid;
                $ta->save();

            } catch (\Aws\S3\Exception\S3Exception $e) {

                Yii::error($e->getMessage(), 'agent');

                //$this->addError('attachments', Yii::t('app', 'Please try again.'));

                return false;

            } catch (\Exception $e) {

                Yii::error($e->getMessage(), 'agent');

                //$this->addError('candidate_video', Yii::t('app', 'Video not available to save.'));

                return false;
            }
        }
    }

    /**
     * Gets query for [[Agent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(Staff::className(), ['staff_id' => 'staff_id']);
    }

    /**
     * Gets query for [[TicketAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicketAttachments()
    {
        return $this->hasMany(TicketAttachment::className(), ['ticket_uuid' => 'ticket_uuid']);
    }

    /**
     * Gets query for [[TicketComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicketComments()
    {
        return $this->hasMany(TicketComment::className(), ['ticket_uuid' => 'ticket_uuid']);
    }
}
