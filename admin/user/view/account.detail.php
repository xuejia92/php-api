
<div class="pageContent">

	<div class="tabs" currentIndex="0" eventType="click">
		<div class="tabsHeader">
			<div class="tabsHeaderContent">
				<ul>
					<li><a href="javascript:;"><span>用户信息</span></a></li>
					<li ><a id='a_moneylog'  href="?m=user&p=account&act=moneylog&mid=<?php echo $_REQUEST['mid']?>&gameid=<?php echo $_REQUEST['gameid']?>"  class="j-ajax"><span>金币明细</span></a></li>
					<li ><a id='a_rolllog'  href="?m=user&p=account&act=rolllog&mid=<?php echo $_REQUEST['mid']?>&gameid=<?php echo $_REQUEST['gameid']?>"  class="j-ajax"><span>乐券明细</span></a></li>
					<?php if ($_REQUEST['gameid']!=5):?>
					<li><a id='a_playlog' href="?m=user&p=account&act=playlog&mid=<?php echo $_REQUEST['mid']?>&gameid=<?php echo $_REQUEST['gameid']?>"  class="j-ajax"><span>牌局记录</span></a></li>
					<?php endif;?>
					<li><a id='a_fishlog' href="?m=user&p=account&act=fishlog&mid=<?php echo $_REQUEST['mid']?>&gid=<?php echo $_REQUEST['gameid']?>"  class="j-ajax"><span>捕鱼记录</span></a></li>
					<li><a id='a_yulelog' href="?m=user&p=account&act=yulelog&mid=<?php echo $_REQUEST['mid']?>&gameid=5&gid=<?php echo $_REQUEST['gameid']?>"  class="j-ajax"><span>娱乐场</span></a></li>
					<li><a id='a_payment' href="?m=user&p=account&act=payment&mid=<?php echo $_REQUEST['mid']?>&gameid=<?php echo $_REQUEST['gameid']?>"  class="j-ajax"><span>充值记录</span></a></li>
					<li><a id='a_exchangelog' href="?m=user&p=account&act=exchangelog&mid=<?php echo $_REQUEST['mid']?>&gameid=<?php echo $_REQUEST['gameid']?>"  class="j-ajax"><span>兑换记录</span></a></li>
					<li><a id='a_banklog' href="?m=user&p=account&act=banklog&mid=<?php echo $_REQUEST['mid']?>&gameid=<?php echo $_REQUEST['gameid']?>"  class="j-ajax"><span>保险箱操作</span></a></li>
					<li><a id='a_bataccountlog' href="?m=user&p=account&act=bataccountlog&mid=<?php echo $_REQUEST['mid']?>&gameid=<?php echo $_REQUEST['gameid']?>"  class="j-ajax"><span>封号记录</span></a></li>
				</ul>
			</div>
		</div>
		<div  class="tabsContent" >
			<div><?php include 'account.userinfo.php';?></div>
			<div id="moneylog"></div>
			<div id="rolllog"></div>
			<?php if ($_REQUEST['gameid']!=5):?>
			<div id="playlog"> </div>
			<?php endif;?>
			<div id="fishlog"> </div>
			<div id="yulelog"> </div>
			<div id="payment"> </div>
			<div id="exchangelog"> </div>
			<div id="banklog"> </div>
			<div id="bataccountlog"> </div>
		</div>
		<div class="tabsFooter">
			<div class="tabsFooterContent"></div>
		</div>
	</div>



</div>
