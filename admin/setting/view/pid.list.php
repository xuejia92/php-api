<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=setting&p=pid&ctype=<?php echo $_GET['ctype']?>&act=setView&navTabId=channel_<?php echo $_REQUEST['gameid']?>_<?php echo $_REQUEST['cid']?>&gameid=<?php echo $_GET['gameid']?>&cid=<?php echo $_GET['cid']?>" target="navTab" mask="true" width="800" height="580"><span>新增客户端包(pid)</span></a></li>
	<li><a class="delete" href="?m=setting&p=pid&act=del&id={id}&navTabId=plist" target="ajaxTodo" title="你确定要删除吗？" warn="请选择关卡"><span>删除</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th width="30" >pid</th>
			<th width="30" >cid</th>
			<th width="40" >客户端包名称</th>
			<th width="40" >移动</th>
			<th width="40" >联通</th>
			<th width="40" >电信</th>
			<th width="40" >网页支付</th>
			<th width="40" >更多游戏</th>
			<th width="40" >实物兑换</th>
			<th width="40" >点卡兑换</th>
			<?php if($_GET['ctype'] == 2):?>
			<th width="40" >账号通用</th>
			<th width="40" >游客注册</th>
			<?php endif;?>
			<th width="40" >娱乐场</th>
			<th width="40" >首充</th>
			<th width="40" >破产充</th>
			<th width="40" >限时充</th>
			<?php if($_GET['ctype'] == 1):?>
			<th width="40" >短代防刷</th>
			<th width="40" >短代省份过滤</th>
			<?php endif;?>
			<th width="40" >活动</th>
			<th width="40" >公告</th>
			<th width="40" >子渠道id</th>
			<th width="50">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<?php $switch = Setting_Pid::factory()->getSwitch($item['id']);?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $item['id'] ?></td>
			<td><?php echo $item['cid'] ?></td>
			<td><?php echo $item['pname'] ?></td>
			<td>
				<a title='排序优先级' class="edit" href="?m=setting&p=msgpay&act=sortPidView&pid=<?php echo $item['id']?>&mtype=1&navTabId=<?php echo $_GET['navTabId'] ?>" target="dialog">
				<?php echo $aPay[$item['id']][1]['name'] ?>
				</a>
			</td>
			<td>
				<a title='排序优先级' class="edit" href="?m=setting&p=msgpay&act=sortPidView&pid=<?php echo $item['id']?>&mtype=2&navTabId=<?php echo $_GET['navTabId'] ?>" target="dialog">
				<?php echo $aPay[$item['id']][2]['name'] ?>
				</a>
			</td>
			<td>
				<a title='排序优先级' class="edit" href="?m=setting&p=msgpay&act=sortPidView&pid=<?php echo $item['id']?>&mtype=3&navTabId=<?php echo $_GET['navTabId'] ?>" target="dialog">
				<?php echo $aPay[$item['id']][3]['name'] ?>
				</a>
			</td>
				<td><?php echo in_array(1,$switch)    ? '╳' : '√' ?></td>
				<td><?php echo in_array(2,$switch)    ? '╳' : '√' ?></td>
				<td><?php echo in_array(6,$switch)    ? '╳' : '√' ?></td>
				<td><?php echo in_array(9,$switch)    ? '╳' : '√' ?></td>
				<?php if($_GET['ctype'] == 2):?>
				<td><?php echo in_array(11,$switch)   ? '╳' : '√' ?></td>
				<td><?php echo in_array(12,$switch)   ? '╳' : '√' ?></td>
				<?php endif;?>
				<td><?php echo in_array(3,$switch)    ? '╳' : '√' ?></td>
				<td><?php echo in_array(4,$switch)    ? '╳' : '√' ?></td>
				<td><?php echo in_array(5,$switch)    ? '╳' : '√' ?></td>
				<td><?php echo in_array(10,$switch)   ? '╳' : '√' ?></td>
				<?php if($_GET['ctype'] == 1):?>
				<td><?php echo in_array(7,$switch)    ? '╳' : '√' ?></td>
				<td><?php echo in_array(13,$switch)   ? '╳' : '√' ?></td>
				<?php endif;?>
				<td><?php echo in_array(8,$switch)    ? '╳' : '√' ?></td>
				<td><?php echo in_array(14,$switch)   ? '╳' : '√' ?></td>
			<td><?php echo $item['childid'] ? $item['childid'] : 0?></td>
			<td>
			<a class="delete" href="?m=setting&p=pid&act=del&id=<?php echo $item['id']?>&navTabId=channel_<?php echo $_REQUEST['gameid']?>_<?php echo $_REQUEST['cid']?>" target="ajaxTodo" title="你确定要删除这个关卡吗？" warn="请选择其中一个活动">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=setting&p=pid&act=setView&ctype=<?php echo $_GET['ctype']?>&id=<?php echo $item['id']?>&navTabId=channel_<?php echo $_REQUEST['gameid']?>_<?php echo $_REQUEST['cid']?>&gameid=<?php echo $_GET['gameid']?>&cid=<?php echo $_GET['cid']?>" target="navTab" width="800" height="580" >
				修改
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


