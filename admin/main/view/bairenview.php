<style type="text/css">
        table { border-color:Black; border-style:dotted; border-width:0px; border-right-width:1px; border-bottom-width:1px; margin:0; padding:0; border-spacing:0; }
        td { border-color:Black; border-style:dotted; border-width:0px; border-top-width:1px; border-left-width:1px; padding:0; }
    </style>

<div class="pageContent">
	<div style="text-align:center;margin-bottom:3px;line-height:20px">
		<p style="text-align:center;margin:5px;font-size:25px">庄家信息</p>
		<p style="margin:10px;">
			用户ID：
			<span id="mid<?php echo $_GET['type'] ?>"><?php echo $data['mid']?></span>
		</p>
		<p style="margin:10px;">
			昵称：
			<span id="mnick<?php echo $_GET['type'] ?>"><?php echo $data['mnick']?></span>
		</p>
		<p style="margin:10px;">
			身上金币：
			<span id="money<?php echo $_GET['type'] ?>"><?php echo $data['money']?></span>
		</p>
		<p style="margin:10px;">
			保险箱金币：
			<span id="freezemoney<?php echo $_GET['type'] ?>"><?php echo $data['freezemoney']?></span>
		</p>
		<p style="margin:30px; ">
			状态：
			<span style="font-size:30px;color:red" id="status<?php echo $_GET['type'] ?>"><?php echo $data['status'] == 1 ? '空闲' : ($data['status'] == 2 ? '下注' : '结算')?></span>
		</p>
		<?php if($_REQUEST['type'] == 2):?>
			<p style="margin:30px;">	
				庄家控制：
				&nbsp;&nbsp;&nbsp;&nbsp;
				<span style="font-size:15px;">
				<a class="delete" href="?m=main&p=sh&act=update&type=2&num=5&navTabId=<?php echo $_GET['navTabId'] ?>" target="ajaxTodo" title="你确定要开庄全赢吗？" warn="请选择其中一个">
				开庄家赢
				</a>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="delete" href="?m=main&p=sh&act=update&type=2&num=6&navTabId=<?php echo $_GET['navTabId'] ?>" target="ajaxTodo" title="你确定要开庄全输吗？" warn="请选择其中一个">
				开庄家输
				</a>
				</span>
			</p>
			<p style="margin:30px;" >
				
			</p>						
		<?php endif;?>
	</div>


	<table style="height:100px;" width="100%" style="height:300px" >
		<thead>
			<tr>
				<th style="font-weight:bold;" width="80">下注点</th>
				<th style="font-weight:bold;" width="100">下注概括</th>
				<th style="font-weight:bold;" width="80">操作</th>
			</tr>
		</thead>
		<tbody>
		
		<?php for ($i=1;$i<=$data['count'];$i++):?>
			<tr >				
				<td id="bet<?php echo $i?><?php echo $_GET['type']?>"><?php echo $data['b'.$i] ?></td>
				<td id="gk<?php echo $i?><?php echo $_GET['type']?>"><?php echo $data['bet'.$i] ?></td>
				<td>
				<a id="opt" class="delete" href="?m=main&p=sh&act=update&type=<?php echo $_GET['type']?>&num=<?php echo $i?>&navTabId=<?php echo $_GET['navTabId'] ?>" target="ajaxTodo" title="你确定要开<?php echo $data['b'.$i] ?>吗？" warn="请选择其中一个">
				开此
				</a>
				</td>						
			</tr>
		<?php endfor;?>
		</tbody>
	</table>	
	
	<table style="margin-top:10px;" width="100%" layoutH="138">
		<thead>
			<tr>
				<th style="font-weight:bold;" width="80"><?php echo $_GET['type'] == 1 ? "龙" : "天" ?> </th>
				<th style="font-weight:bold;" width="80"><?php echo $_GET['type'] == 1 ? "和" : "地" ?></th>
				<th style="font-weight:bold;" width="80"><?php echo $_GET['type'] == 1 ? "虎" : "玄" ?></th>
				<?php if($_GET['type'] == 2):?><th style="font-weight:bold;" width="80">黄</th><?php endif;?>
			</tr>
		</thead>
		<tbody>

		<tr style="font-size:20px;" >				
			<td id="man1<?php echo $_GET['type']?>">
			</td>
			<td id="man2<?php echo $_GET['type']?>">
			</td>
			<td id="man3<?php echo $_GET['type']?>">
			</td>
			<?php if($_GET['type'] == 2):?>
			<td id="man4<?php echo $_GET['type']?>">
			</td>
			<?php endif;?>			
		</tr>
		</tbody>
	</table>	
</div>

<script>


function get_data() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "?m=main&p=sh&act=bairenajax&type=<?php echo $type?>",
        success: function(data) {
			var b1 = data.b1;
			var b2 = data.b2;
			var b3 = data.b3;
			var b4 = data.b4;

			var bet1 = data.bet1;
			var bet2 = data.bet2;
			var bet3 = data.bet3;
			var bet4 = data.bet4;

			var banker      = data.banker;
			var mnick       = data.mnick;
			var mid         = data.mid;
			var money       = data.money;
			var freezemoney = data.freezemoney;
			var status = data.status == 1 ? '空闲' : (data.status == 2 ? '下注' : '结算');

			$("#bet1<?php echo $_GET['type'] ?>").text(b1);
			$("#bet2<?php echo $_GET['type'] ?>").text(b2);
			$("#bet3<?php echo $_GET['type'] ?>").text(b3);
			$("#bet4<?php echo $_GET['type'] ?>").text(b4);

			$("#gk1<?php echo $_GET['type'] ?>").text(bet1);
			$("#gk2<?php echo $_GET['type'] ?>").text(bet2);
			$("#gk3<?php echo $_GET['type'] ?>").text(bet3);
			$("#gk4<?php echo $_GET['type'] ?>").text(bet4);

			$("#mid<?php echo $_GET['type'] ?>").text(mid);
			$("#mnick<?php echo $_GET['type'] ?>").text(mnick);
			$("#money<?php echo $_GET['type'] ?>").text(money);
			$("#freezemoney<?php echo $_GET['type'] ?>").text(freezemoney);
			$("#status<?php echo $_GET['type'] ?>").text(status);

			man1 = data.m_bet1;
			man2 = data.m_bet2;
			man3 = data.m_bet3;
			man4 = data.m_bet4;
			
			$("#man1<?php echo $_GET['type'] ?>").html(man1);
			$("#man2<?php echo $_GET['type'] ?>").html(man2);
			$("#man3<?php echo $_GET['type'] ?>").html(man3);
			$("#man4<?php echo $_GET['type'] ?>").html(man4);
        }
    });
}    

setInterval("get_data()",1000);//1秒一次执行

</script>


