<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->
<script type="text/javascript" src="/app/views/js/fusioncharts/fusioncharts.js"></script>
<script type="text/javascript">
<!--
function load_listzone(vObj) {
	var index = $("#select_project option").index($("#select_project option:selected"));
	var temp = vObj[vObj.selectedIndex].value.replace('/statistics/statistics_zone/project/','');
	$.post( '/usedata/load_listzone', { project: temp })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$("select[name='select_zone'] option").remove();
	
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						$("<option></option>").attr("selected", false).text(jdata.item[key].zone_name).attr("value", jdata.item[key].seq).appendTo("select[name='select_zone']");
					});
				}
			}
			else if (data.status == "fail") {
				alert('자료 초기화에 싫패하였습니다.');
			}
  		}, "json");
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_4_1"><img src="/app/views/images/common/img_subtitle4_1.gif" alt="통계" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">통계</a> <span>&gt;</span> <strong>전체통계</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">

            <p class="bbstitle3"><strong id="title_project_area"></strong>의 <strong id="title_zone_area"></strong>공구 건물별 통계</p>

            <dl class="tablebox1">
<?php
	$loop_cnt = 0;
?>
                <dd class="bbssearchbox1">
                    <fieldset>
                    <legend>검색</legend>
                    <select id="select_project" name="select_project" onchange="load_listzone(this);">
<?php
	$select_project = 0;
	foreach($project as $pkey => $pval) {
		if ((int)$param['project'] == (int)$project[$pkey]->pj_seq) {
			$project_name = $project[$pkey]->pj_name;
			$select_project = (int)$project[$pkey]->pj_seq;
			echo '<option value="/statistics/statistics_zone/project/'.$project[$pkey]->pj_seq.'" selected="selected">'.$project[$pkey]->pj_name.'</option>'."\n";
		} else {
			echo '<option value="/statistics/statistics_zone/project/'.$project[$pkey]->pj_seq.'">'.$project[$pkey]->pj_name.'</option>'."\n";
		}
	}
?>
                    </select>
                    <input type="image" src="/app/views/images/common/icon_zoom1.gif" class="sbtn" title="검색" onclick="document.location=$('#select_project').val();" />
                    &nbsp;
                    <select id="select_zone" name="select_zone">
<?php
	foreach($zone as $zkey => $zval) {
		if ((int)$param['zone'] == (int)$zone[$zkey]->zone_seq) {
			$zone_name = $zone[$zkey]->zone_name;
			echo '<option value="/statistics/statistics_build/project/'.$select_project.'/zone/'.$zone[$zkey]->zone_seq.'" selected="selected">'.$zone[$zkey]->zone_name.'</option>'."\n";
		} else {
			echo '<option value="/statistics/statistics_build/project/'.$select_project.'/zone/'.$zone[$zkey]->zone_seq.'">'.$zone[$zkey]->zone_name.'</option>'."\n";
		}
	}
?>
                    </select>
                    <input type="image" src="/app/views/images/common/icon_zoom1.gif" class="sbtn" title="검색" onclick="document.location=$('#select_zone').val();" />
                    </fieldset>
                </dd>
            	<!-- <dd class="bbssubtitle1">&#8226; 2공구</dd> -->
<?php
	$stat_val = $stat[0];
	foreach($stat_val as $key => $val) {
		if ($loop_cnt == 0) {
?>
                <dd class="bbschart1">
<script type="text/javascript">
FusionCharts.ready(function () {
    var myChart<?php echo $stat_val[$key]['zone_seq'];?> = new FusionCharts({
        type: 'stackedcolumn2d',
        width: '100%',
        height: '400',
        renderAt: 'chart-container<?php echo $stat_val[$key]['zone_seq'];?>',

        dataFormat: 'json',
        dataSource: <?php echo json_encode($chart[$key]); ?>
    });

    // Render the chart.
    myChart<?php echo $stat_val[$key]['zone_seq'];?>.render();
});
</script>
                	<div id="chart-container<?php echo $stat_val[$key]['zone_seq'];?>">FusionCharts will load here...</div>
                </dd>
                <dd class="bbslistbox1">
                    <table class="tablelisttype3" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>No.
                            <th>건물 명</th>
                            <th>Pre-Use</th>
                            <th>Use</th>
                            <th>Post-Use</th>
                            <th>Total</th>
                        </tr>
<?php
			$line_num = count($stat_val);
			foreach($stat_val as $key2 => $val2) {
				$sum_use = (float)$stat_val[$key2]['use_energy_schedule'] + (float)$stat_val[$key2]['use_preuse'] + (float)$stat_val[$key2]['use_occurrence'] + (float)$stat_val[$key2]['use_info'] + (float)$stat_val[$key2]['use_maintenance'];
				$sum_line = $sum_use + (float)$stat_val[$key2]['pre_use'] + (float)$stat_val[$key2]['post_use'];
				$class_name = "trst1";
				if (($line_num % 2) == 0)
					$class_name = "";
?>
                        <tr class="<?php echo $class_name; ?>">
                            <td><?php echo $line_num;?></td>
                            <td><?php echo $stat_val[$key2]['build_name'];?></td>
                            <td><?php echo $stat_val[$key2]['pre_use'];?></td>
                            <td><?php echo $sum_use;?></td>
                            <td><?php echo $stat_val[$key2]['post_use'];?></td>
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
                    <div class="pageing"><!-- <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page1.gif" alt="처음" /></a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page2.gif" alt="이전" /></a> <a href="#;">5</a> <a href="#;">6</a> <a href="#;">7</a> <a href="#;">8</a> <a href="#;" class="strong">9</a> <a href="#;">10</a> <a href="#;">11</a> <a href="#;">12</a> <a href="#;">13</a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page3.gif" alt="다음" /></a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page4.gif" alt="마지막" /></a> --></div>
                </dd>
            </dl>
<?php
		}
		$loop_cnt++;
	}
?>
<script type="text/javascript">
<!--
	$('#title_project_area').html('<?=$project_name?>');
	$('#title_zone_area').html('<?=$zone_name?>');
-->
</script>

        </div>
        <!--//본문 -->            

    </div>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>