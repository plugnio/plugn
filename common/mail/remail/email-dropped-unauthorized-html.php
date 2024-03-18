<?php
/* @var $this yii\web\View */
/* @var $emailFrom string */
/* @var $emailText string */
/* @var $ticket_uuid string */
?>

<p>Your message wasn't delivered because you do not have access to this conversation from the email address you used.</p>

<p>Please confirm that you're responding to emails using the same email address you signed up with on Plugn.</p>

<b>Email address you used:</b>
<p><?= $emailFrom ?></p>

<b>Ticket ID:</b>
<p><?= $ticket_uuid ?></p>

<b>Your message:</b><br/>

<?= str_replace(array("\r\n", "\r", "\n"), "<br />", $emailText); ?>
