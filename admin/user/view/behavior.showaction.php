
<textarea style="width:100%;height:370px;font-size:13px">
<?php if($_GET['result']):?>
result:
<?php echo $_GET['result']?>
<?php else:?>

reuqest：
<?php echo base64_decode($_GET['request']);?>

<?php echo "\n"?>

response:
<?php echo base64_decode($_GET['response'])?>

<?php endif;?>


</textarea>
