<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->
<script type="text/javascript" src="/app/views/js/fusioncharts/fusioncharts.js"></script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_4_1"><img src="/app/views/images/common/img_subtitle4_2.gif" alt="통계" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">통계</a> <span>&gt;</span> <strong>생애주기</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            
            <p class="bbstitle3"><strong>홍길동</strong>님의 Project 생애주기 통계</p>

            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong>7</strong>/2100</dd>
                <dd class="bbschart1">
<script type="text/javascript">
FusionCharts.ready(function () {
    var myChart = new FusionCharts({
        type: 'stackedcolumn2d',
        width: '100%',
        height: '400',
        renderAt: 'chart-container',

        dataFormat: 'json',
        dataSource: <?php echo json_encode($chart); ?>
    });

    // Render the chart.
    myChart.render();
});
</script>
                	<div id="chart-container">FusionCharts will load here...</div>
                </dd>
                <dd class="bbslistbox1">
                    <table class="tablelisttype3" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>No.</th>
                            <th>Project명</th>
                            <th>Energy</th>
                            <th>유지보수</th>
                            <th>Total</th>
                        </tr>
<?php
	$line_num = count($stat);
	foreach($stat as $key => $val) {
		$sum_use1 = (float)$stat[$key]['use_energy_schedule'] + (float)$stat[$key]['use_info'];
		$sum_use2 = (float)$stat[$key]['use_occurrence'] + (float)$stat[$key]['use_maintenance'];
		$sum_line = $sum_use1 + $sum_use2;
		$class_name = "trst1";
		if (($line_num % 2) == 0)
			$class_name = "";
?>
                        <tr class="<?php echo $class_name; ?>">
                            <td><?php echo $line_num;?></td>
                            <td><a href="/statistics/lifecycle_zone/project/<?php echo $stat[$key]['pj_seq'];?>"><?php echo $stat[$key]['pj_name'];?></a></td>
                            <td class="c"><?php echo $sum_use1;?></td>
                            <td class="c"><?php echo $sum_use2;?></td>
                            <td><?php echo $sum_line;?></td>
                        </tr>
<?php
		$line_num--;
	}
?>
                    </table>
                </dd>
        
                <dd class="bbsbtnbox1">
                    <div class="btntype1"></div>
                    <div class="btntype2"></div>
                    <div class="pageing"><a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page1.gif" alt="처음" /></a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page2.gif" alt="이전" /></a> <a href="#;">5</a> <a href="#;">6</a> <a href="#;">7</a> <a href="#;">8</a> <a href="#;" class="strong">9</a> <a href="#;">10</a> <a href="#;">11</a> <a href="#;">12</a> <a href="#;">13</a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page3.gif" alt="다음" /></a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page4.gif" alt="마지막" /></a></div>
                </dd>
            </dl>

        </div>
        <!--//본문 -->            

    </div>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>