
<div class="pageContent">

	<div class="tabs" currentIndex="0" eventType="click">
		<div class="tabsHeader">
			<div class="tabsHeaderContent">
				<ul>
				<?php foreach($cats as $catid=>$catname):?>
					<li><a id="<?php echo $_REQUEST['navTabId']."-".$catid?>"  href="?m=data&p=show&act=detail&catid=<?php echo $catid?>&cid=<?php echo $_REQUEST['cid'];?>&ctype=<?php echo $_REQUEST['ctype'];?>&gameid=<?php echo $_REQUEST['gameid'];?>&navTabId=<?php echo $_REQUEST['navTabId']."-".$catid ?>"  class="j-ajax"><span><?php echo $catname?></span></a></li>

				<?php endforeach;?>
				</ul>
			</div>
		</div>
		<div  class="tabsContent" >
			<?php foreach($cats as $catid=>$catname):?>				
				<div id="data_<?php echo $catid?>">
				<?php if($catid == 1){
					$_REQUEST['navTabId'] = $_REQUEST['navTabId']."-1";
					include 'data.detail.php';
				} ?>
				</div>
			<?php endforeach;?>
		</div>
		<div class="tabsFooter">
			<div class="tabsFooterContent"></div>
		</div>
	</div>
</div>
