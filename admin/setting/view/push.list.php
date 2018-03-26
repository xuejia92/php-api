<div class="pageContent">
<div class="panelBar">
<ul class="toolBar">
	<li><a class="add" href="?m=setting&p=push&act=setView&navTabId=push" target="navTab" mask="true"><span>新增推送项</span></a></li>
	<li><a class="delete" href="?m=setting&p=push&act=del&id={id}&navTabId=push" target="ajaxTodo" title="你确定要删除吗？" warn="请选择关卡"><span>删除</span></a></li>
</ul>
</div>

<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
			<th style="font-weight:bold;" width="20" >编号</th>
			<th style="font-weight:bold;" width="20" >所属游戏</th>
			<th style="font-weight:bold;" width="20" >客户端类型</th>
			<th style="font-weight:bold;" width="20" >渠道</th>
			<th style="font-weight:bold;" width="40" >推送类别</th>
			<th style="font-weight:bold;" width="120">内容</th>
			<th style="font-weight:bold;" width="90" >推送条件 </th>
			<th style="font-weight:bold;" width="60" >推送时间 </th>
			<th style="font-weight:bold;" width="60" >创建时间</th>
			<th style="font-weight:bold;" width="50" >状态</th>
			<th style="font-weight:bold;" width="80">操作 </th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $item['id'] ?></td>
			<td><?php echo Config_Game::$game[$item['gameid']] ?></td>
			<td><?php echo $item['ctype'] ?></td>
			<td><?php echo $item['cid'] ?></td>
			<td><?php echo $item['ptype'] == 1 ? 'N天没登陆推送' : ($item['ptype'] == 2 ? '某段时间登陆用户推':'全局推') ?></td>
			<td><?php echo $item['msg'] ?></td>
			<td><?php if($item['ptype'] == 2){$aPcon = explode(",", $item['pcon']);}   echo $item['ptype'] == 1 ? $item['pcon'].'天没登陆':($item['ptype'] == 2 ? "$aPcon[0]&nbsp;&nbsp;-&nbsp;&nbsp;$aPcon[1]登陆过的用户":"-") ?></td>
			<td><?php echo $item['ptype'] == 1 ? '每天'.$item['ptime']: date("Y-m-d H:i",$item['ptime']) ?></td>
			<td><?php echo date("Y-m-d H:i:s",$item['ctime']); ?></td>
			<td><?php echo $item['status'] == 1 ? "<a style='color:red'>生效</a>" : '失效' ?></td>
			<td>
			<a class="delete" href="?m=setting&p=push&act=del&id=<?php echo $item['id']?>&navTabId=push" target="ajaxTodo" title="你确定要删除这个关卡吗？" warn="请选择其中一个活动">
				删除 
			</a>	
			|		
			<a class="delete" href="?m=setting&p=push&act=setView&id=<?php echo $item['id']?>&navTabId=push" target="navTab">
				修改
			</a>
			|
			<a class="delete" href="?m=setting&p=push&act=setStatus&id=<?php echo $item['id']?>&status=<?php echo $item['status'] == 1 ? 0 : 1?>&navTabId=push" target="ajaxTodo" title="你确定要操作？" warn="请选择其中一个活动">
				<?php echo $item['status'] == 0 ? "生效" : '失效' ?>
			</a>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>


