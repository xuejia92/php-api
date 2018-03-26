<form id="pagerForm" action=""></form>
<div class="pageHeader">
	<form rel="pagerForm" method="post" action="" onsubmit="return dwzSearch(this, 'dialog');">
	<div class="searchBar">
		<div class="subBar">
			<ul>
				<li><div class="button"><div class="buttonContent"><button type="button" multLookup="shop" warn="请选择渠道商">选择</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">

	<table class="table" layoutH="118" targetType="dialog" width="100%">
		<thead>
			<tr>
				<th width="30"><input id='s_all' type="checkbox" class="checkboxCtrl" group="shop" /></th>
				<th orderfield="pmode">渠道ID</th>
				<th orderfield="payname">渠道名</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($allChannel as $pmode=>$payname):?>
			<tr>
				<td><input onclick="checkBox()" <?php if($aChannel && in_array($pmode, $aChannel)):?>checked<?php endif;?> type="checkbox" name="shop" value="{pmode<?php echo $_REQUEST['mtype']?>:'<?php echo $pmode?>', payname<?php echo $_REQUEST['mtype']?>:'<?php echo $payname?>'}"/></td>
				<td><?php echo $pmode?></td>
				<td><?php echo $payname?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

<script type="text/javascript">

function checkBox(){
	if($("[name=shop]").length == $("[name=shop]:checked").length){
		$("#s_all").attr("checked",true);
	}else{
		$("#s_all").attr("checked",false);
	}
}

checkBox();

</script>