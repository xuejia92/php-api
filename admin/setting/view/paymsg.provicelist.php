<form id="pagerForm" method="post" action="?m=setting&p=msgpay&act=getProvice&navTabId=provincelist">
	<input type="hidden" name="gameid" value="<?php echo $_REQUEST['gameid']?>" />
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="?m=setting&p=msgpay&act=getProvice&navTabId=provincelist" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td style="padding-right: 3px;">游戏：</td>
				<td>	
					<select name="gameid">
						<?php foreach (Config_Game::$game as $gameId=>$gameName):?>
							<option <?php if($gameId == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo $gameId?>"><?php echo $gameName;?></option>
						<?php endforeach;?>
					</select>				
				</td>
				<td><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></td>
			</tr>
		</table>
	</div>
	</form>
</div>
<div class="pageContent">	
	<div class="pageContent">
		<div class="panelBar">
			<ul class="toolBar">
				<li><a class="add" href="?m=setting&p=msgpay&act=setProviceView&navTabId=provincelist" target="navTab" ><span>新增</span></a></li>	
			</ul>
		</div>
				
		<table class="table" width="100%" layoutH="138">
		<thead>
		<tr>
			<th style="font-weight:bold;" width="80" >支付方式</th>
			<th style="font-weight:bold;" width="200" >移动</th>
			<th style="font-weight:bold;" width="200" >联通</th>
			<th style="font-weight:bold;" width="200" >电信</th>
			<th style="font-weight:bold;" width="50" >操作</th>
		</tr>
		</thead>
		<tbody id="tb">
			<?php if($items):?>
				<?php foreach ($items as $gameid=>$item):?>
    				<?php foreach ($item as $pmode=>$info ):?>
    				<tr>												
    				<td><?php echo $aPmode[$pmode] ?></td>
    				<td>
    				<?php echo  $info[1]?>
    				</td>
    				<td>
    				<?php echo  $info[2]?>
    				</td>
    				<td>
    				<?php echo  $info[3]?>
    				</td>
    				<td>
    				<a title='修改省份' class="edit" href="?m=setting&p=msgpay&navTabId=provincelist&act=setProviceView&pmode=<?php echo $pmode?>&gameid=<?php echo $gameid?>&mtype=3" target="dialog">修改</a>
    				|
    				<a class="delete" href="?m=setting&p=msgpay&act=delProvice&pmode=<?php echo $pmode?>&gameid=<?php echo $gameid?>&navTabId=provincelist" target="ajaxTodo" title="你确定要删除吗？" warn="请选择">
    				删除 
    				</a>
    				</td>
    				</tr>
    				<?php endforeach;?>
				<?php endforeach;?>
		    <?php endif;?>	
		</tbody>
	 </table>
   </div>
</div>



	

