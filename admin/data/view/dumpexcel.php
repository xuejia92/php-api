<div class="pageHeader">
	<form  action="?m=data&p=baiduSearch&act=dumpexcel&navTabId=datastat" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
			<td style="padding-right: 3px;">游戏：</td>
				<td>					
					<select class="combox" name="gameid">
					<option value="">所有</option>
						<?php foreach (Config_Game::$game as $gameid=>$gamename):?>
							<option <?php if($gameid == $_REQUEST['gameid']):?> selected="selected" <?php endif;?> value="<?php echo $gameid?>"><?php echo $gamename;?></option>
						<?php endforeach;?>
					</select>
				</td>
				<td>开始时间：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d",strtotime("-3 days")) ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d") ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>				
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">导出excel</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>