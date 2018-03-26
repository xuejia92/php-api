<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="admin.php?m=user&p=account&navTabId=u-alist" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					站点ID(sitemid)：<input type="text" name="sitemid" value="<?php echo isset($_REQUEST['sitemid']) ? $_REQUEST['sitemid'] : ''?>" />
				</td>
				<td>
					<select name="sid" >
							<option value=0>全部</option>
						<?php foreach ($aSid as $sid=>$accountType):?>
							<option <?php if($_REQUEST['sid'] == $sid):?>selected="selected"<?php endif;?>  
								value="<?php echo $sid?>"><?php echo $accountType ?>
							</option>	
						<?php endforeach;?>
					</select>
				</td>
				<td>
					以下条件中选择一个进行查询
				</td>
				<td>
					游戏ID(mid)：<input type="text" name="mid" value="<?php echo isset($_REQUEST['mid']) ? $_REQUEST['mid'] : ''?>"/>
				</td>
				<td>
					用户账号：<input type="text" name="username" value="<?php echo isset($_REQUEST['username']) ? $_REQUEST['username'] : ''?>" />
				</td>				
				<td>
					昵称(mnick)：<input type="text" name="mnick" value="<?php echo isset($_REQUEST['mnick']) ? $_REQUEST['mnick'] : ''?>" />
				</td>
				
				<td>
					手机号码：<input type="text" name="phone" value="<?php echo isset($_REQUEST['phone']) ? $_REQUEST['phone'] : ''?>" />
				</td>
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">查找</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
<?php if($items):?>	
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				<th width="30">mid</th>
				<th width="50">账号</th>
				<th width="80">昵称</th>
				<th width="100">机器码</th>
				<th width="50">IP</th>
				<th width="50">账号类型</th>
				<th width="200">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($items as $item):?>
			<tr target="id" rel="<?php echo $item['mid']?>">
				<td> <?php echo $item['mid']?></td>
				<td><?php echo $item['registername']?></td>
				<td><?php echo $item['mnick']?></td>
				<td><a class="button"  height="800" width="800" href="?m=user&p=account&act=getdevices&deviceno=<?php echo $item['deviceno'] ?>" target="dialog" rel="sdf" title="查看与此机器码相同的用户"><span><?php  echo  $item['deviceno']?></span> </a>
					<?php if($item['dflag'] !== false):?>
					&nbsp;&nbsp;&nbsp;已被封&nbsp;&nbsp;&nbsp;
					<a style='color:red' class="delete" href="?m=user&p=account&act=resetDeviceNo&deviceno=<?php echo $item['deviceno']?>" target="ajaxTodo" title="你确定要解封？" >
					解封&nbsp;&nbsp;&nbsp;
					</a>
					<a style='color:red' class="delete" href="?m=user&p=account&act=banDeviceNo&deviceno=<?php echo $item['deviceno']?>" target="ajaxTodo" title="你确定要封这个机器码？" >
					再次封机器码
					</a>
					<?php else:?>
					<a style='color:red' class="delete" href="?m=user&p=account&act=banDeviceNo&deviceno=<?php echo $item['deviceno']?>" target="ajaxTodo" title="你确定要封这个机器码？" >
					封机器码
					</a>
					<?php endif;?>  
				</td>
				<td><a class="button"  height="800" width="800" href="?m=user&p=account&act=getips&ip=<?php echo $item['ip'] ?>" target="dialog" rel="sdf" title="查看与此IP相同的用户"><span><?php  echo  $item['ip']?></span></a>
					 <?php if($item['ipflag'] !== false):?>
					 	&nbsp;&nbsp;&nbsp;已被封&nbsp;&nbsp;&nbsp;
					 	<a style='color:red' class="delete" href="?m=user&p=account&act=resetbanIp&ip=<?php echo $item['ip']?>" target="ajaxTodo" title="你确定解封这个IP码？" >
						解封IP&nbsp;&nbsp;&nbsp; 
					 	</a>
					 	<a style='color:red' class="delete" href="?m=user&p=account&act=banIP&ip=<?php echo $item['ip']?>" target="ajaxTodo" title="你确定要封这个IP码？" >
						再次封IP 
					 </a>
					 <?php else:?>
					 <a style='color:red' class="delete" href="?m=user&p=account&act=banIP&ip=<?php echo $item['ip']?>" target="ajaxTodo" title="你确定要封这个IP码？" >
						封IP 
					 </a>
					 <?php endif;?>  
					 
				</td>
				<td><?php foreach ($aSid as $sid=>$accountType):?>
					<?php if($sid == $item['sid']):?><?php echo $accountType ?><?php endif;?>  
					<?php endforeach;?></td>
				<td> 
					<?php $aGame = array_keys($item['mtime'],true)?>
					<?php foreach ($aGame as $gameid):?>
						<a style="color:red" class="edit" href="?m=user&p=account&act=detail&mid=<?php echo $item['mid']?>&navTabId=u-detail&gameid=<?php echo $gameid?>" rel="u-detail" target="navTab" title="明细-<?php echo Config_Game::$game[$gameid]?>"><?php echo Config_Game::$game[$gameid]?></a>
					<?php endforeach;?>
					
				</td>
			</tr>
		<?php endforeach;?>
		
		</tbody>
	</table>
<?php endif;?>	
</div>
