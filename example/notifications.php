<?php
use Jgauthi\Component\Bootstrap\Html\Notification;

require_once __DIR__.'/inc/init.inc.php';

init_page();
?>
<h3>Quelques notifications</h3>
<?=Notification::success('Success')?>
<?=Notification::warning('Warning')?>
<?=Notification::error('Error')?>
<?=Notification::info('Info')?>
<?=Notification::message('Message')?>

