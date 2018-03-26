<div class="pageHeader">
	<div class="panel collapse" minH="25" defH="25">
		<h1>微信激活码库存</h1>
		<div>
			<p><span style="margin:0 15px;float:left;font-weight:bold;height:20px;line-height:20px">数量：</span><span style="height:20px;line-height:20px;float:left;color:red;font-weight:bold;font-size:16px" ><?php echo $result[0] ? $result[0] : 0?></span></p>
		</div>
	</div>
	<form onsubmit="return navTabSearch(this);" action="?m=action&p=list&act=detail&actionid=12&navTabId=action_12" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
    			<td>发放开关：</td>			
				<td>
				    <a class="button" href="?m=action&p=list&act=weixinActiveCodeSwitch&actionid=12&navTabId=action_12" target="ajaxTodo"><span><?php echo $result[2]==1 ? '关闭'  : '开启' ?></span></a>
				</td>
			</tr>
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
				<td><button type="submit">检索</button></td>			
			</tr>
		</table>
	</div>
	</form>
</div>
<div class="pageContent" style="width:100%;overflow-x:hidden;" layoutH="150">
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
			<?php foreach ($item as $ite):?>
			     <th style="font-weight:bold;text-align:center" width="90"><?php echo $ite ?></th>
			<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
		    <?php if ($result[1]):?>
    		    <?php foreach ($result[1] as $key=>$val):?>
            			<tr>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $key?></td>
            			     <td style="font-weight:bold;text-align:center" width="90"><?php echo $val ? $val : 0?></td>
            			</tr>
    			<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</div>
