<div class="pageContent">

	<div class="tabs" currentIndex="0" eventType="click">
		<div class="tabsHeader">
			<div class="tabsHeaderContent">
				<ul>
			         <li><a id="<?php echo $_REQUEST['navTabId']."-1"?>"  href="?m=data&p=channel&act=detail&catid=1&gameid=<?php echo $_REQUEST['gameid'];?>&navTabId=<?php echo $_REQUEST['navTabId']."-".$catid ?>"  class="j-ajax"><span>渠道概况</span></a></li>
				</ul>
			</div>
		</div>
		<div  class="tabsContent" >			
			<div id="data_1">
			<?php include 'channel.detail.php'?>
			</div>
		</div>
		<div class="tabsFooter">
			<div class="tabsFooterContent"></div>
		</div>
	</div>
</div>