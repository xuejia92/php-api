<div class="pageContent">
<div class="pageHeader">
<table class="table" width="100%" layoutH="138">
	<thead>
		<tr>
		    <th style="font-weight:bold; text-align:center;">场次</th>
			<th style="font-weight:bold; text-align:center;">系统输赢</th>
			<th style="font-weight:bold; text-align:center;">赢取上限</th>
			<th style="font-weight:bold; text-align:center;">用户当庄赢取</th>
		</tr>
	</thead>
	<tbody>
		<tr style="text-align:center;">
		    <td>龙虎斗</td>
	        <td><?php echo $dragon['bankerwin']?></td>
	        <td><?php echo $dragon['upperlimit']?></td>
	        <td><?php echo $dragon['userbanker']?></td>
		</tr>
		<tr style="text-align:center;">
		    <td>斗牛百人场</td>
		    <td><?php echo $many['bankerwin']?></td>
	        <td><?php echo $many['upperlimit']?></td>
	        <td><?php echo $many['userbanker']?></td>
		</tr>
		<tr style="text-align:center;">
		    <td>捕鱼</td>
	        <td><?php echo $fishinfo?></td>
	        <td>-</td>
	        <td>-</td>
		</tr>
		<tr style="text-align:center;">
		    <td>德州百人场</td>
		    <td><?php echo $texas['bankerwin']?></td>
	        <td><?php echo $texas['upperlimit']?></td>
	        <td><?php echo $texas['userbanker']?></td>
		</tr>
		<tr style="text-align:center;">
		    <td>炸金花百人场</td>
		    <td><?php echo $glod['bankerwin']?></td>
	        <td><?php echo $glod['upperlimit']?></td>
	        <td><?php echo $glod['userbanker']?></td>
		</tr>
	</tbody>
</table>
</div>
</div>