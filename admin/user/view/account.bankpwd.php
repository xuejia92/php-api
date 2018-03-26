
<div class="pageContent">
	<form method="post" action="?m=user&p=account&act=resetBankPassword&navTabId=u-detail" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid']?>"/>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>用名ID：</label>
				<?php echo $_REQUEST['mid']?>
			</p>
			<p>
				<label>保险箱金币数：</label>
				<?php echo $_REQUEST['freezemoney']?>
			</p>
			<p>
				<label>保险箱密码：</label>
				<input class="required" name="password"  type="text" size="20" value="" />
			</p>
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
