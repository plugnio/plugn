<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ticket".
 *
 * @property string $ticket_uuid
 * @property string|null $restaurant_uuid
 * @property int|null $agent_id
 * @property int|null $staff_id
 * @property string|null $ticket_detail
 * @property int|null $ticket_status
 * @property string|null $ticket_started_at
 * @property string|null $ticket_completed_at
 * @property int|null $response_time
 * @property int|null $resolution_time
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
            [['agent_id', 'staff_id', 'ticket_status', 'response_time', 'resolution_time'], 'integer'],
            [['ticket_detail'], 'string'],
            [['created_at', 'updated_at', 'ticket_started_at', 'ticket_completed_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
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
     * @return string[]
     */
    public function extraFields()
    {
        return [
            'attachments',
            'agent',
            'staff',
            'restaurant',
            'ticketComments',
            'ticketAttachments',
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
            'ticket_started_at' => Yii::t('app', 'Started At'),
            'ticket_completed_at' => Yii::t('app', 'Completed At'),
            'response_time' => Yii::t('app', 'Response time'),
            'resolution_time' => Yii::t('app', 'Resolution time'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        //notify staff

        if($insert) {
            $this->sendTicketGeneratedMail();

            if(YII_ENV == 'prod') {

                Yii::$app->eventManager->track('Ticket Added', [
                    'ticket_id' => $this->ticket_uuid,
                    'store_id' => $this->restaurant_uuid,
                    'ticket_description' => $this->ticket_detail,
                    'number_of_attachments' => sizeof($this->attachments)
                ],
                    null,
                    Yii::$app->user->getId()
                );
            }
        }

        if(isset($changedAttributes['ticket_status'])) {

            if($this->ticket_status == self::STATUS_COMPLETED)
            {
                $this->ticket_completed_at = new Expression("NOW()");
                $this->resolution_time = time() - strtotime($this->ticket_started_at);

                if(YII_ENV == 'prod') {

                    Yii::$app->eventManager->track('Ticket Resolved', [
                        'ticket_id' => $this->ticket_uuid,
                        'store_id' => $this->restaurant_uuid,
                        'ticket_description' => $this->ticket_detail,
                        'number_of_attachments' => $this->getAttachments()->count()
                    ],
                        null,
                        Yii::$app->user->getId()
                    );
                }
            }
            else if($this->ticket_status == self::STATUS_IN_PROGRESS)
            {
                $this->ticket_started_at = new Expression("NOW()");
                $this->response_time = time() - strtotime($this->created_at);

                if(YII_ENV == 'prod') {

                    Yii::$app->eventManager->track('Ticket Started', [
                        'ticket_id' => $this->ticket_uuid,
                        'store_id' => $this->restaurant_uuid,
                        'ticket_description' => $this->ticket_detail,
                        'number_of_attachments' => $this->getAttachments()->count()
                    ],
                        null,
                        Yii::$app->user->getId()
                    );
                }
            }

            self::updateAll([
                'ticket_completed_at' => $this->ticket_completed_at,
                'ticket_started_at' => $this->ticket_started_at,
                'resolution_time' => $this->resolution_time,
                'response_time'=> $this->response_time
            ], [
                'ticket_uuid' => $this->ticket_uuid
            ]);
        }

        $this->_moveAttachments();
    }

    /**
     * notify staff for new ticket
     */
    public function sendTicketAssignedMail() {

        \Yii::$app->mailer->htmlLayout = "layouts/text";

        \Yii::$app->mailer->compose ([
            'html' => 'agent/ticket-assigned-html',
            'text' => 'agent/ticket-assigned-text',
        ], [
            'model' => $this
        ])
            ->setFrom ([Yii::$app->params['supportEmail']])
            ->setTo ($this->staff->staff_email)
            ->setSubject ('Ticket assigned for ' . $this->restaurant->name)
            ->send ();
    }

    /**
     * notify staff for new ticket
     */
    public function sendTicketGeneratedMail() {

        $staffs = Staff::find()->all();

        $staffEmails = ArrayHelper::getColumn ($staffs, 'staff_email');

        \Yii::$app->mailer->htmlLayout = "layouts/text";

        \Yii::$app->mailer->compose ([
                'html' => 'agent/ticket-generated-html',
                'text' => 'agent/ticket-generated-text',
            ], [
                'model' => $this
            ])
            ->setFrom ([Yii::$app->params['supportEmail']])
            ->setTo (Yii::$app->params['supportEmail'])
            ->setCc ($staffEmails)
            ->setSubject ('New ticket generated for ' . $this->restaurant->name)
            ->send ();
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
    
                $extension = pathinfo($value, PATHINFO_EXTENSION);

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
    public function getAgent($modelClass = "\common\models\Agent")
    {
        return $this->hasOne($modelClass::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaff($modelClass = "\common\models\Staff")
    {
        return $this->hasOne($modelClass::className(), ['staff_id' => 'staff_id']);
    }

    /**
     * Gets query for [[TicketAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicketAttachments($modelClass = "\common\models\TicketAttachment")
    {
        return $this->hasMany($modelClass::className(), ['ticket_uuid' => 'ticket_uuid']);
    }

    /**
     * Gets query for [[TicketCommentAttachment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments($modelClass = "\common\models\Attachment")
    {
        return $this->hasMany($modelClass::className(), ['attachment_uuid' => 'attachment_uuid'])
            ->via('ticketAttachments');
    }

    /**
     * Gets query for [[TicketComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicketComments($modelClass = "\common\models\TicketComment")
    {
        return $this->hasMany($modelClass::className(), ['ticket_uuid' => 'ticket_uuid']);
    }

    /**
     * @return query\TicketQuery
     */
    public static function find()
    {
        return new query\TicketQuery(get_called_class());
    }
}
