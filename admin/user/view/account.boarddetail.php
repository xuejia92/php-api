<div class="pageContent">	
	<div class="pageFormContent" layoutH="58">
			<div style="width:1200px;margin-bottom:20px">
			<p style="text-align:center;width:1200px;">
			-----------------------------------基本信息-----------------------------------
			</p>
			<p style="text-align:center;width:1200px; font-size:14px">
			<?php echo $title?>
			</p>
			</div>
			
			<?php if($publicCard && $_REQUEST['gameid']==6):?>
			<div style="width:1200px;margin-bottom:20px">
			<p style="text-align:center;width:1200px;">
			-----------------------------------公共牌-----------------------------------
			</p>
			<div style='text-align:center'>
				<?php foreach($publicCard as $card):?>
					<img src="statics/poker/<?php echo $card?>.png">
				<?php endforeach;?>
			</div>
			</div>
			<?php endif;?>
			
			
			<div style="width:1200px;margin-bottom:20px">
			<p style="text-align:center;width:1200px;">
			-----------------------------------底牌-----------------------------------
			</p>
			
			<?php foreach($members as $mid=>$info):?>
			<div>
				<img width=60 height=60 src=<?php echo Member::factory()->getIcon(0, $mid,'icon')?>>
				
				<div style="display: inline-block; line-height: 2em;">
				            用户ID：<?php echo $mid;?>&nbsp;（<?php echo $mnicks[$mid]['mnicks']?>）
    				当前金币数：<?php echo $others[$mid]['money']?>&nbsp;
    				座位ID：<?php echo $others[$mid]['seatid']?>&nbsp;
    				<?php if ($mnicks[$mid]['ip']):?>
    				<br/>IP：<?php echo $mnicks[$mid]['ip']?>&nbsp;
    				<?php endif;?>
				</div>
				<?php foreach($info as $k=>$poker):?>
					<?php if($k == 0 && $_GET['gameid'] == 6):?>
					手牌：				
					<?php endif;?>
					<?php if($k == 2 && $_GET['gameid'] == 6):?>
					最佳牌组合：
					<?php endif;?>
					<img src="statics/poker/<?php echo $poker?>.png">
				<?php endforeach;?>
				<br/>
				<br/>
			</div>
			<?php endforeach;?>
			</div>
			
			<div style="width:1200px;margin-bottom:20px">
			<p style="text-align:center;width:1200px;">
			-----------------------------------行牌-----------------------------------
			</p>
			<?php foreach($subPlay as $k=>$info):?>
			<div>
				<?php foreach($info as $mid=>$v):?>
					<img width=60 height=60 src=<?php echo Member::factory()->getIcon(0, $mid,'icon')?>>用户ID：<?php echo $mid;?>&nbsp;（<?php echo $mnicks[$mid]['mnicks']?>）
					<?php echo $v?>
					<br/>
				<?php endforeach;?>
				<br/>
				<br/>
			</div>	
			<?php endforeach;?>
			</div>
			
			<div style="width:1200px;margin-bottom:20px">
			<p style="text-align:center;width:1200px;">
			-----------------------------------结算-----------------------------------
			</p>
			<?php foreach($ending as $mid=>$end):?>
			<div style=" font-size:14px">
				<img width=60 height=60 src=<?php echo Member::factory()->getIcon(0, $mid,'icon')?>>用户ID：<?php echo $mid;?>&nbsp;（<?php echo $mnicks[$mid]['mnicks']?>）
				
				<?php foreach($end as $e):?>
					<?php echo $e?>
					<br/>
				<?php endforeach;?>
				
				<br/>
				<br/>
			</div>	
			<?php endforeach;?>
			</div>
	</div>

</div>

