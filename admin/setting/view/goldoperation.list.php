<div class="pageHeader" >
	<form onsubmit="return navTabSearch(this);" action="?m=setting&p=goldoperation&act=getone&navTabId=goldoperation" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>用户名：</td>	
				<td>					
					<input id="username" style="float:left" type="text" name=username />
				</td>
				<td>开始时间：</td>			
				<td>					
					<input id="stime" style="float:left" type="text" value="<?php echo $_REQUEST['stime'] ? $_REQUEST['stime'] : date("Y-m-d",strtotime("-5 days")) ?>" name=stime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>
				<td>结束时间：</td>	
				<td>					
					<input id="etime" style="float:left" type="text" value="<?php echo $_REQUEST['etime'] ? $_REQUEST['etime'] : date("Y-m-d") ?>" name=etime class="date" dateFmt="yyyy-MM-dd" readonly="true"/>
					<a style="float:left" class="inputDateButton" href="javascript:;">选择</a>
				</td>				
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="return autoclick()" >检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
<table class="table" width="100%" layoutH="138">
    <thead>
        <tr>
            <th style="font-weight:bold;" width="80">用户名</th>
            <th style="font-weight:bold;" width="80">金币数</th>
            <th style="font-weight:bold;" width="80">对方mid</th>
            <th style="font-weight:bold;" width="80">时间</th>
            <th style="font-weight:bold;" width="80">IP地址</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach(  $items as $item ):?>
		<tr target="id" rel="<?php echo $item['id']?>">
			<td><?php echo $item['username'] ?></td>
			<td><?php echo $item['money'] ?></td>
			<td><?php echo $item['mid'] ?></td>
			<td><?php echo date("Y-m-d H:i:s",$item['time']) ?></td>
			<td><?php echo $item['ip'] ?></td>
		</tr>
   <?php endforeach;?>
    </tbody>
</table>
</div>