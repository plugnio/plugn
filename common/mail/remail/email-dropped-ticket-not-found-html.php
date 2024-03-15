<?php
/* @var $this yii\web\View */
/* @var $emailText string */
/* @var $ticket_uuid string */
?>

<p>Ticket not found</p>

<p>Your message wasn't delivered as we do not have a record of this ticket.</p>

<p>Please respond to this email if you require assistance from Plugn's support team.</p>

<b>Ticket ID:</b>
<p><?= $ticket_uuid ?></p>

<b>Your message:</b><br/>
<?= str_replace(array("\r\n", "\r", "\n"), "<br />", $emailText); ?>
