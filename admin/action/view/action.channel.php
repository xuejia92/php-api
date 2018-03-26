<form id="pagerForm" action=""></form>
<div class="pageHeader">
	<form rel="pagerForm" method="post" action="" onsubmit="return dwzSearch(this, 'dialog');">
	<div class="searchBar">
		<div class="subBar">
			<ul>
				<li><div class="button"><div class="buttonContent"><button type="button" multLookup="cid" warn="请选择渠道商">选择</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">

	<table class="table" layoutH="118" targetType="dialog" width="100%">
		<thead>
			<tr>
				<th width="30"><input id='s_all' type="checkbox" class="checkboxCtrl" group="cid" /></th>
				<th orderfield="cid">编号</th>
				<th orderfield="cname">渠道名</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($aCid as $k=>$v):?>
			<tr>
				<td><input onclick="checkBox()" <?php foreach ($aChannel as $channelid){if ($v['cid']==$channelid){echo checked;}} ?> type="checkbox" name="cid" value="{cid:'<?php echo $v['cid']?>', cname:'<?php echo $v['cname']?>'}"/></td>
				<td><?php echo $v['cid']?></td>
				<td><?php echo $v['cname']?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

<script type="text/javascript">

function checkBox(){
	if($("[name=cid]").length == $("[name=cid]:checked").length){
		$("#s_all").attr("checked",true);
	}else{
		$("#s_all").attr("checked",false);
	}
}

checkBox();

</script>