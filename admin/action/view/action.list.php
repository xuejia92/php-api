
<div class="pageContent">

	<div class="tabs" currentIndex="0" eventType="click">
		<div  class="tabsContent" >			
			<div>
			    <?php {$_REQUEST['navTabId'] = $_REQUEST['navTabId']."-1";}?>
			    <?php
			        $action = Action_Config::$action[$_REQUEST['actionid']]['name'];
			        include "action.$action.php";
			    ?>
			</div>
		</div>
		<div class="tabsFooter">
			<div class="tabsFooterContent"></div>
		</div>
	</div>
</div>