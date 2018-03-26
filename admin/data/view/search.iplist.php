<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" action="?m=data&p=baiduSearch&act=IP&navTabId=mobile_2" method="post">
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
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
				<li><a height="480" width="1100" class="button" href="?m=data&p=baiduSearch&act=dumpexcelView" target="dialog" rel="dumpexcel"><span>导出excel</span></a></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent" style="width:100%;overflow-x:hidden;" layoutH="150">
    <table class="table" width="100%">
        <thead>
            <tr>
                <th style="font-weight:bold;text-align:center">IP地址</th>
                <th style="font-weight:bold;text-align:center">点击次数</th>
            </tr>
        </thead>
            
        <tbody>
            <?php foreach ($record[$item] as $row=>$rows):?>
                <tr>
                    <td style="text-align:center" width="150"><?php echo $row?></td>
                    <td style="text-align:center" width="150"><?php echo $rows?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>