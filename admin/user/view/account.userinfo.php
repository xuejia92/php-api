
<form method="post" action="?m=user&p=account&act=set&navTabId=<?php echo isset($_GET['navTabId']) ? $_GET['navTabId'] : "u-detail"?>" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
					<div class="pageFormContent" layoutH="138">
						<div style="text-align:center;margin-bottom:3px"><img width=150 height=150 src="<?php echo $item['iconurl']?>"></div>
						<input type='hidden' name='mid' value="<?php echo $item['mid']; ?>"/>
						<input type='hidden' name='gameid' value="<?php echo $_REQUEST['gameid']; ?>"/>
						<p>
							<label><strong>用户ID：</strong></label>
							<?php echo  $item['mid'] ?>
						</p>
						<p>
							<label><strong>玩家昵称：</strong></label>
							<?php echo $item['mnick'] ?>
						</p>
						
						<p>
							<label><strong>性别：</strong></label>
							<?php echo  isset($item['sex']) ? $item['sex']==1 ? '男' : ($item['sex'] == 2 ? '女' : '未知') : ""?>
						</p>
						<p>
							<label><strong>账号类型：</strong></label>
							<?php foreach ($aSid as $sid=>$accountType){ if($sid == $item['sid']){echo $accountType; break;} }?>
						</p>
						<p>
							<label><strong>渠道：</strong></label>
							<?php foreach ($aCid as $cid=>$cname){ if($cid == $item['cid']){echo $cname; break;} }?>
						</p>
						<p>
							<label><strong>包名称：</strong></label>
							<?php foreach ($aPid as $pid=>$pname){ if($pid == $item['pid']){echo $pname; break;} }?>
						</p>
						<p>
							<label><strong>累计在玩时长：</strong></label>
							<?php echo sprintf("%.2f", round($item['ot:'.$_REQUEST['gameid']]/3600,2))?>h
						</p>
						<p>
							<label><strong>sitemid：</strong></label>
							<?php echo  $item['sitemid']?>
						</p>
						<p>
							<label><strong>注册名：</strong></label>
							<?php echo  $item['registername']?>
							<a class="button" href="?m=user&p=account&act=setAccountpwdView&sitemid=<?php echo $item['sitemid'] ?>&sid=<?php echo $item['sid']?>" target="dialog" rel="accountpwd"><span>修改账号密码</span></a>
						</p>
						<p>
							<label><strong>密钥：</strong></label>
							<?php echo  $item['secretkey']?>
						</p>
						<p>
							<label><strong>注册时间：</strong></label>
							
							<?php echo  date("Y-m-d H:i:s", $item['mtime'][$_REQUEST['gameid']]) ?>
						</p>
						<p>
							<label><strong>最后登陆时间：</strong></label>
							<?php echo  date("Y-m-d H:i:s",$item['mactivetime'][$_REQUEST['gameid']])?>
						</p>
						<p>
							<label><strong>最后登陆IP：</strong></label>
							<?php $ip_arr = Lib_Ip::find($item['ip'])  ?>
							<?php echo ($ip_arr[1] == '中国' || $ip_arr[1] == '局域网') ? $item['ip'] : $ip_arr[0].' '.$ip_arr[1].' '.$ip_arr[2].' '.$ip_arr[3].' '.$ip_arr[4];?>
						</p>
						<p>
							<label><strong>登陆次数：</strong></label>
							<?php echo $item['mentercount'][$_REQUEST['gameid']]?>
						</p>
						<p>
							<label><strong>设备名称：</strong></label>
							<?php echo $item['devicename']?>
						</p>
						<p>
							<label><strong>系统版本号：</strong></label>
							<?php echo $item['osversion']?>
						</p>
						<p>
							<label><strong>网络类型：</strong></label>
							<?php echo $item['nettype'] == 2 ? 'wifi' :($item['nettype'] == 1 ? "3G" : ($item['nettype'] == 3 ? "2G":"无") )?>
						</p>
						<p>
							<label><strong>版本号：</strong></label>
							<?php echo $item['versions']?>
						</p>
						<p>
							<label><strong>战绩：</strong></label>
							胜：&nbsp;<?php echo (int)$item['wi:'.$_REQUEST['gameid']]?>&nbsp;|&nbsp;负：&nbsp;<?php echo (int)$item['ls:'.$_REQUEST['gameid']]?>
						</p>
						<p>
							<label><strong>逃跑次数：</strong></label>
							<?php echo (int)$item['ra:'.$_REQUEST['gameid']]?>
						</p>
						<p>
							<label><strong>是否会员：</strong></label>
							<?php echo $isvip ? $vipday.'天': '否'?>
							<a class="button" href="?m=user&p=account&act=setVIPView&gameid=<?php echo $_REQUEST['gameid']?>&mid=<?php echo $item['mid'] ?>&freezemoney=<?php echo $item['freezemoney']?>" target="dialog" rel="bankpwd"><span>修改会员天数</span></a>
						</p>
						<p>
							<label><strong>是否开启保险箱：</strong></label>
							<?php echo $item['bankPWD'] ? '是' : '否'?>
							<?php if($item['bankPWD']):?>
							<a class="button" href="?m=user&p=account&act=setBankpwdView&mid=<?php echo $item['mid'] ?>&freezemoney=<?php echo $item['freezemoney']?>" target="dialog" rel="bankpwd"><span>修改保险箱密码</span></a>
							<?php endif;?>
						</p>
						<p>
							<label><strong>是否禁言：</strong></label>
							<?php echo $gag ? '禁止发言' : '允许发言'?>
							<a class="button" href="?m=user&p=account&act=setGagView&mid=<?php echo $item['mid'] ?>&gag=<?php echo (int)$gag?>" target="dialog" rel="gag"><span>修改状态</span></a>
						</p>
						<p>
							<label><strong>保险箱金币数：</strong></label>
							
							<input style="color:blue" class="textInput disabled" disabled="true"  value="<?php echo $item['freezemoney']?>" type="text" size="10" />
							<select name="mcon" >
								<option value="2" selected>扣减</option>
							</select>
							<input name="freezemoney" type="text" size="10" />
						</p>
						
						<p>
							<label><strong>账号状态：</strong></label>
							<?php 
							if($item['mstatus'] == 7){
								echo '封号7天';
							}elseif($item['mstatus'] == 15){
								echo '封号15天';
							}elseif($item['mstatus'] == 180){
								echo '封号半年';
							}elseif($item['mstatus'] === 'forever'){
								echo '永久封号';
							}else{
								echo '正常';
							}
							?>
						</p>
						<p>
							<label><strong style="color:red">金币：</strong></label>
							<input style="color:blue" class="textInput disabled" disabled="true"  value="<?php echo $item['money']?>" type="text" size="10" />
							<select name="mcon" >
								<option value="1">增加</option>
								<option value="2" selected>扣减</option>
							</select>
							<input name="money" type="text" size="10" />
						</p>
						<p>
							<label><strong style="color:red">乐卷：</strong></label>
							<input style="color:blue" class="textInput disabled" disabled="true"  value="<?php echo $item['roll'] + $item['roll1']?>" type="text" size="10" />
							<select name="rcon" class="required combox">
								<option value="1">增加</option>
								<option value="2" selected>扣减</option>
							</select>
							<input name="roll" type="text" size="10" />
						</p>
						
						<p>
							<label>小喇叭：</label>
							<input style="color:blue" class="textInput disabled" disabled="true"  value="<?php echo (int)$item['horn']?>" type="text" size="10" />
							<select name="hcon" class="required combox">
								<option value="1">增加</option>
								<option value="2" selected>扣减</option>
							</select>
							<input name="horn" type="text" size="10" />
						</p>
						
						<p>
							<label><strong>绑定微信：</strong></label>
							<?php echo $item['weixinbinding'] ? '已绑定' : '未绑定'?>
							<a class="button" href="?m=user&p=account&act=cleanWeixinBinding&mid=<?php echo $item['mid'] ?>&openid=<?php echo $item['weixinbinding']?>&navTabId=u-detail" target="ajaxTodo" title="你确定要删除吗？" warn="请选择其中一个"><span>清除绑定</span></a>
						</p>
						
						<p>
							<label><strong>头像管理：</strong></label>
							<?php echo $item['iconblist'] ==1 ? '解除黑名单' : '删除头像'?>
							<a class="button" href="?m=user&p=account&act=setOptIconView&opt=<?php echo (int)$item['iconblist']?>&mid=<?php echo $item['mid'] ?>&gag=<?php echo (int)$gag?>" target="dialog" rel="gag"><span>修改状态</span></a>
						</p>
					</div>
					<div class="formBar">
						<ul>
							<li><div class="buttonActive"><div class="buttonContent"><button type="submit">修改</button></div></div></li>
							<li>
								<div class="button"><div class="buttonContent"><button type="button" class="close">返回</button></div></div>
							</li>
						</ul>
					</div>
</form>