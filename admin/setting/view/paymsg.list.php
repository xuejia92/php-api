
<div class="pageContent">	
	<div class="pageContent">
		<form method="post"  action="?m=shop&p=currency&act=sort&navTabId=currencylist" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone)">
			<div class="panelBar">
				<ul class="toolBar">
					<li><a class="add" href="?m=setting&p=msgpay&act=setView" target="navTab" ><span>新增</span></a></li>	
				</ul>
			</div>
					
			<table class="table" width="100%" layoutH="138">
			<thead>
			<tr>
				<th style="font-weight:bold;" width="50" >游戏</th>
				<th style="font-weight:bold;" width="200" >移动</th>
				<th style="font-weight:bold;" width="200" >联通</th>
				<th style="font-weight:bold;" width="200" >电信</th>
				<th style="font-weight:bold;" width="100" >操作</th>
			</tr>
			</thead>
			<tbody>
				<?php if($items):?>
				<?php foreach(  $items as $gameid=>$item ):?>
				<tr target="id" rel="<?php echo $gameid?>">												
				<td><?php echo Config_Game::$game[$gameid] ?></td>
				<td>
				<a title='排序优先级' class="edit" href="?m=setting&p=msgpay&act=sortView&gameid=<?php echo $gameid?>&mtype=1" target="dialog">
				<?php echo  $item[1]['name']?>
				</a>
				</td>
				<td>
				<a title='排序优先级' class="edit" href="?m=setting&p=msgpay&act=sortView&gameid=<?php echo $gameid?>&mtype=2" target="dialog">
				<?php echo  $item[2]['name']?>
				</a>
				</td>
				<td>
				<a title='排序优先级' class="edit" href="?m=setting&p=msgpay&act=sortView&gameid=<?php echo $gameid?>&mtype=3" target="dialog">
				<?php echo  $item[3]['name']?>
				</a>
				</td>
				<td>
				<a class="delete" href="?m=setting&p=msgpay&act=del&gameid=<?php echo $gameid?>&navTabId=msgpaylist" target="ajaxTodo" title="你确定要删除吗？" warn="请选择">
				删除 
				</a>	
				|		
				<a class="delete" href="?m=setting&p=msgpay&act=setView&gameid=<?php echo $gameid?>&navTabId=msgpaylist" target="navTab" width="800" height="580" >
				修改
				</a>
				|
				<a class="delete" href="?m=setting&p=msgpay&act=sortallpid&navTabId=paymsglist&gameid=<?php echo $gameid?>" target="ajaxTodo" title="你确定要将排序应用到这个游戏吗？" warn="请选择" >
				更新排序到游戏
				</a>
				</td>
				</tr>
				<?php endforeach;?>
			<?php endif;?>	
			</tbody>
		 </table>
	  </form>	
   </div>
</div>



	

