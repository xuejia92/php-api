
<div class="pageContent">
	<form method="post" action="?m=user&p=account&act=setGag&navTabId=u-detail" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<input type="hidden" name="mid" value="<?php echo $_REQUEST['mid']?>"/>
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>
					<input type="radio" name="gag" value=1 <?php if($_REQUEST['gag'] == 1):?> checked="checked" <?php endif;?> >
					禁言发言
				</label>
				<label>
					<input type="radio" name="gag" value=0 <?php if($_REQUEST['gag'] == 0):?> checked="checked" <?php endif;?> >
					允许发言
				</label>
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
