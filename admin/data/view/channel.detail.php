<?php  error_reporting(0);  ?>
<script>
function autoclick(param,cid,catid,overview){
	var gameid = "<?php echo $_REQUEST['gameid'] ? $_REQUEST['gameid'] : ''?>";
	var ctype = "<?php echo $_REQUEST['ctype'] ? $_REQUEST['ctype'] : ''?>";
	var etime = $("#etime").val();
	$("#"+param).attr("href","?m=data&p=channel&act=detail&etime="+etime+"&catid="+catid+"&navTabId="+param+"&ctype="+ctype+"&gameid="+gameid) ;
	$("#"+param).click(); 
}
</script>

<div class="pageHeader">
	<div class="searchBar">
		<table class="searchContent">		
			<tr>
				<td>日期：</td>	
				<td>					
					<input id="etime" style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ?  $_REQUEST['etime'] : date("Y-m-d",strtotime("-1 days"))  ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>			
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="return autoclick('data_<?php echo $gameid?>_<?php echo $ctype?>_0-1','<?php echo $_REQUEST['cid']?>','<?php echo $_REQUEST['catid']?>','<?php echo $_REQUEST['overview']?>')">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	<!--  </form> -->
</div>
<div class="pageContent">
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
			<th  style="font-weight:bold;text-align:center" width="90">渠道名称</th>
			<th  style="font-weight:bold;text-align:center" width="90">时间</th>
			<?php foreach($aItem as $item):?>
				<th style="font-weight:bold;text-align:center;" width="80"><?php echo $item['itemname']?></th>
			<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($channel as $channelid=>$channelname):?>
		    <tr>
		        <?php $cty = Config_Game::$clientTyle?>
				<td style="text-align:center"><a title="<?php echo $channelname?>-<?php echo $cty[$ctype]?>-<?php echo Config_Game::$game[$gameid]?>" href="admin.php?m=data&p=show&gameid=<?php echo $gameid?>&ctype=<?php echo $ctype?>&cid=<?php echo $channelid?>&catid=1&navTabId=data_<?php echo $gameid?>_<?php echo $ctype?>_<?php echo $channelid?>" rel="data_<?php echo $gameid?>_<?php echo $ctype?>_<?php echo $channelid?>" target="navTab"><?php echo $channelname?></a></td>
				<td style="text-align:center"><?php echo $aDate?></td>
				<?php foreach($aContent[$aDate][$channelid] as $content):?>
					<td style="text-align:center"><?php echo $content ? $content : '0' ?></td>
				<?php endforeach;?>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

