<?php

use yii\db\Migration;
use common\models\Ticket;

/**
 * Class m231231_093639_crm_stats
 */
class m231231_093639_crm_stats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ticket', 'ticket_started_at',
            $this->dateTime()->after('ticket_status')->null());

        $this->addColumn('ticket', 'ticket_completed_at',
            $this->dateTime()->after('ticket_started_at')->null());

        $this->addColumn('ticket', 'response_time',
            $this->integer()->after('ticket_status')->null());

        $this->addColumn('ticket', 'resolution_time',
            $this->integer()->after('ticket_status')->null());

        $query = \common\models\Ticket::find()
                ->andWhere(['!=', 'ticket_status', \common\models\Ticket::STATUS_PENDING]);

        foreach ($query->batch(100) as $tickets)
        {
            foreach ($tickets as $ticket) {

                $oldestCommentByStaff = $ticket->getTicketComments()
                    ->andWhere(new \yii\db\Expression("staff_id IS NOT NULL"))
                    ->orderBy('created_at')
                    ->one();

                if($oldestCommentByStaff) {
                    $ticket->ticket_started_at = $oldestCommentByStaff->created_at;

                    $ticket->response_time = strtotime($oldestCommentByStaff->created_at) - strtotime($ticket->created_at);
                }

                if($ticket->ticket_status == Ticket::STATUS_COMPLETED) {

                    $latestCommentByStaff = $ticket->getTicketComments()
                        ->andWhere(new \yii\db\Expression("staff_id IS NOT NULL"))
                        ->orderBy('created_at DESC')
                        ->one();

                    $ticket->ticket_completed_at = $latestCommentByStaff->created_at;

                    if($oldestCommentByStaff)
                        $ticket->resolution_time = strtotime($latestCommentByStaff->ticket_completed_at) - strtotime($oldestCommentByStaff->created_at);
                }

                $ticket->save();
            }
        }
/*
         - first comment date by staff
 - last comment date by staff
 (in minutes)
 (in minutes)
*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ticket', 'ticket_started_at');
        $this->dropColumn('ticket', 'ticket_completed_at');
        $this->dropColumn('ticket', 'response_time');
        $this->dropColumn('ticket', 'resolution_time');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231231_093639_crm_stats cannot be reverted.\n";

        return false;
    }
    */
}
