<?php
/* @var $this yii\web\View */
/* @var $emailFrom string */
/* @var $emailText string */
/* @var $ticket_uuid string */
?>

Your message wasn't delivered because you do not have access to this conversation from the email address you used.

Please confirm that you're responding to emails using the same email address you signed up with on Plugn.

Email address you used:
<?= $emailFrom ?>

Ticket ID:
<?= $ticket_uuid ?>

Your message:
<?= $emailText ?>
