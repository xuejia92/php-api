
<div class="pageContent">
	<form method="post" action="?m=user&p=account&act=resetVIP&navTabId=u-detail" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid']?>"/>
		<input type="hidden" name="gameid" value="<?php echo $_REQUEST['gameid']?>"/>
		<div class="pageFormContent" layoutH="56">
		<table class="searchContent">		
			<tr>
				<td style="padding-right: 3px;">会员类型</td>
				<td>
					<select id="<?php echo $_REQUEST['navTabId'] ?>" class="combox" name="vip">
						<option value="30">月会员 </option>
						<option value="7">周会员</option>
						<option value="90">季会员</option>
						<option value="365">年会员</option>
						<option value="0">取消会员</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="padding-right: 3px;">会员天数</td>
				<td>
					<input name='exptime' type='text' value="<?php echo $_REQUEST['exptime']?>" />
				</td>
			</tr>
		</table>		
		</div>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">修改</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>
