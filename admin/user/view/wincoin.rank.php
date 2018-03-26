
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=user&p=rank&act=getWincoinRank&navTabId=rankwincoin" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>游戏：</td>
				<td>
					<select class="combox" name="gameid">
						<option <?php if(3 == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="3">斗地主</option>
					</select>
				</td>
			</tr>
		</table>

		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th style="font-weight:bold" width="80">Top</th>
				<th style="font-weight:bold" width="80">mid</th>
				<th style="font-weight:bold" width="80">昵称</th>
				<th style="font-weight:bold" width="80">玩牌累计赢金币数</th>
				<th style="font-weight:bold" width="80">身上金币</th>
				<th style="font-weight:bold" width="80">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php if($items):?>
		<?php foreach($items as $k=>$item):?>
			<tr target="id" rel="<?php echo $item['id']?>">	
				<td> <?php echo $k + 1 ?> </td>
				<td><a title="用户信息" href="?m=user&p=account&navTabId=u-alist&mid=<?php echo $item['mid'] ?>" target="navTab" style="text-decoration: underline;"><?php echo $item['mid'] ?></a></td>			
				<td><?php echo $item['mnick'] ?></td>
				<td><?php echo $item['winmoney'] ?></td>
				<td><?php echo $item['money'] ?></td>
				<td>
				<?php if($item['flag']):?>
				<a  class="delete" href="?m=user&p=rank&act=delWinCoinBlasklist&navTabId=rankwincoin&mid=<?php echo $item['mid']?>" target="ajaxTodo" title="你确定要解除？" >
					解除黑名单
				</a>	
				<?php else:?>
				<a style='color:red' class="delete" href="?m=user&p=rank&act=setWinCoinBlasklist&navTabId=rankwincoin&mid=<?php echo $item['mid']?>" target="ajaxTodo" title="你确定要加入黑名单？" >
					加入黑名单
				</a>	
				<?php endif;?>	
				</td>
			</tr>
		<?php endforeach;?>	
		<?php endif;;?>
		</tbody>
	</table>
</div>
