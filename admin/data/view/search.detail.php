<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" action="?m=data&p=baiduSearch&act=&navTabId=mobile_2" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>开始时间：</td>			
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d",strtotime("-14 days")) ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d") ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>				
			</tr>
		</table>
	</div>
	</form>
</div>
<div class="pageContent" style="width:100%;overflow-x:hidden;" layoutH="150">
    <table class="table" width="100%">
        <thead>
            <tr>
                <th style="font-weight:bold;text-align:center" width="150">时间</th>
                <th style="font-weight:bold;text-align:center">总点击量</th>
                <th style="font-weight:bold;text-align:center">Android点击量</th>
                <th style="font-weight:bold;text-align:center">iOS点击量</th>
            </tr>
        </thead>
            
        <tbody>
            <?php foreach ($record as $row=>$rows):?>
                <tr>
                    <td style="text-align:center" width="150"><?php echo $row?></td>
                    <td style="text-align:center"><?php echo $rows[$item][0][0]?>/<?php echo $rows[$item][0][1]?></td>
                    <td style="text-align:center"><?php echo $rows[$item][1][0]?>/<?php echo $rows[$item][1][1]?></td>
                    <td style="text-align:center"><?php echo $rows[$item][2][0]?>/<?php echo $rows[$item][2][1]?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>