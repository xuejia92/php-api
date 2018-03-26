<style type="text/css">
	ul.rightTools {float:right; display:block;}
	ul.rightTools li{float:left; display:block; margin-left:5px}
</style>

<script type="text/javascript">

function reloadFeedback(){
	$.ajax({url: '?m=feedback&p=reply&act=reloadFeedback',
		type: 'POST',
		data:{navTabId:"f_list"},
		dataType: 'json',
		success: function(result){navTabAjaxDone(result)}
		}); 
}

reloadFeedback();

$(".tabsPageContent").css("overflow","scroll");

</script>

<div class="pageContent" style="float: left; margin:5px;width: 650px; ">
<form  method="post" action="?m=feedback&p=reply&act=reply&navTabId=reply_<?php echo $userInfo['mid']?>" class="pageForm required-validate"  onsubmit="return iframeCallback(this, navTabAjaxDone)">
<div class="pageFormContent" >
	<input type="hidden" name='id' value="<?php echo $_GET['id']?>">
	<fieldset>
		<legend>玩家反馈</legend>
		<dl  class="nowrap">
			<dt style="height: 70px; line-height:70px;width:60px">反馈：</dt>
			<dd><textarea readonly="true" cols="70" rows="4">(<?php echo date("Y-m-d H:i:s",$item['feedback']['ctime'])?>):<?php echo $item['feedback']['content'] ? $item['feedback']['content'] : "图片反馈"?></textarea></dd>
		</dl>
		<dl  class="nowrap">
			<dt style="height: 50px; line-height:50px;width:60px" >图片：</dt>
			<dd><?php if($item['feedback']['pic']):?><img src="<?php echo $item['feedback']['pic']?>"/><?php else:?><span style="height:50px;line-height:50px;">无</span><?php endif;?></dd>
		</dl>
		<dl class="nowrap">
			<dt style="height: 60px; line-height:60px ;color:red;width:60px">回复：</dt>
			<dd><?php if($item['feedback']['status'] == 1){ echo "回复时间：". date("Y-m-d H:i:s",$item['reply']['rtime']);}?><textarea name="content" class="required" cols="70" rows="5"><?php if(isset($item['reply']['content'])){echo $item['reply']['content'];} ?></textarea></dd>
		</dl>
		 <dl  class="nowrap">
			<div style="margin-left:280px" class="buttonActive"><div class="buttonContent"><button  type="submit"><?php if($item['feedback']['status'] == 1):?>修改<?php else:?>回复<?php endif;?></button></div></div>
			<a style="margin-left:10px;" class="button" href="?m=feedback&p=list&act=ignore&id=<?php echo $_GET['id']?>&navTabId=reply_<?php echo $userInfo['mid']?>&close=1" target="ajaxTodo" title="确定要对玩家这条反馈不予回复吗?"><span>忽略</span></a>
		</dl>
	</fieldset>	
</div>
</form>
</div>


<div style="float: left;width: 650px;height:2000px;" class="pageFormContent" layoutH="60">
	<fieldset>
		<legend>玩家基本信息</legend>
		<dl>
			<dt>mid：</dt>
			<dd><?php echo  $userInfo['mid'] ?></dd>
		</dl>
		<dl>
			<dt>昵称：</dt>
			<dd><?php echo $userInfo['mnick'] ?></dd>
		</dl>
		<dl>
			<dt>账号类型：</dt>
			<dd><?php echo $aSid[$userInfo['sid']]?></dd>
		</dl>
		<dl>
			<dt>客户端：</dt>
			<dd><?php echo $aCtype[$userInfo['ctype']]?></dd>
		</dl>
		<dl>
			<dt>是否会员：</dt>
			<dd><?php echo $isvip ? '是' : '否'?></dd>
		</dl>
		<dl>
			<dt>金币数：</dt>
			<dd><?php echo $userInfo['money']?></dd>
		</dl>
		<dl>
		<dt>乐券数：</dt>
			<dd><?php echo $userInfo['roll'] + $userInfo['roll1']?></dd>
		</dl>
	</fieldset>
	
	<fieldset>
		<legend>历史反馈</legend>
		<?php foreach($historys as $row):?>
		<dl class="nowrap">
			<dt>反馈内容：</dt>
			<dd>(<?php echo date("Y-m-d H:i:s",$row['feedback']['ctime'])?>):<?php echo $row['feedback']['content']?></dd>
		</dl>
		<dl class="nowrap">
			<dt>图：</dt>
			<dd> <?php if($row['feedback']['pic']):?> <img src="<?php echo $row['feedback']['pic']?>"/><?php else:?> 无图<?php endif;?></dd>
		</dl>
		<dl class="nowrap">	
			<dt>回复：</dt>
			<dd> 
				<?php if($row['feedback']['status'] == 1):?>
				<textarea style="width:300px;"  readonly="true" cols="50" rows="4">(<?php echo date("Y-m-d H:i:s",$row['reply']['rtime'])?>):<?php echo $row['reply']['content']?></textarea>
				<?php elseif($row['feedback']['status']==0):?>
				尚未回复
				<?php else :?>
				已忽略	
				<?php endif;?>
			</dd>
		</dl>
		<dl class="nowrap">
			<dt></dt>
			<dd> <hr></dd>
		</dl>
		<?php endforeach;?>
	</fieldset>
</div>
