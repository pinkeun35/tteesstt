<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->
<script type="text/javascript" src="/app/views/js/fusioncharts/fusioncharts.js"></script>
<script type="text/javascript">
<!--
function change_check() {
	var change_url = '/statistics/lifecycle_buildyear/project/'+$('#select_project').val()+'/zone/'+$('#select_zone').val()+'/build/'+$('#select_build').val()+'/year/';

	$.post( '/statistics/get_build_1st_year', {
			project: $('#select_project').val(),
			zone: $('#select_zone').val(),
			build: $('#select_build').val()
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				change_url += jdata.min;

				document.location = change_url;
			}
			else if (data.status == "fail") {
				alert('자료 초기화에 싫패하였습니다.');
			}
  		}, "json");
}

function change_check2(vYear) {
	var change_url = '/statistics/lifecycle_buildyear/project/<?php echo $param['project'];?>/zone/<?php echo $param['zone'];?>/build/<?php echo $param['build'];?>/year/'+vYear;

	document.location = change_url;
}

function load_listzone(vVal, vStep2, vStep3) {
	$.post( '/usedata/load_listzone', { project: vVal })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$("select[name='" + vStep2 + "'] option").remove();
				$("select[name='" + vStep3 + "'] option").remove();
	
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						$("<option></option>").attr("selected", false).text(jdata.item[key].zone_name).attr("value", jdata.item[key].seq).appendTo("select[name='" + vStep2 + "']");
					});
				}
			}
			else if (data.status == "fail") {
				alert('자료 초기화에 싫패하였습니다.');
			}
  		}, "json");
}

function load_listbuild(vVal, vStep1, vStep3) {
	if (vVal != '') {
		$.post( '/usedata/load_listbuilding', { project: vStep1, zone: vVal })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					$("select[name='" + vStep3 + "'] option").remove();
		
					if ( jdata.item.length > 0 ) {
						$.each(jdata.item, function(key,state){
							$("<option></option>").attr("selected", false).text(jdata.item[key].bname).attr("value", jdata.item[key].seq).appendTo("select[name='" + vStep3 + "']");
						});
					}
				}
				else if (data.status == "fail") {
					alert('자료 초기화에 싫패하였습니다.');
				}
	  		}, "json");
	  }
}

function go_energy_maintenance() {
<?php
	if (isset($param['build'])) {
		$go_url = '/statistics/energy_maintenance/project/'.$param['project'].'/zone/'.$param['zone'].'/build/'.$param['build'];
	} else {
		$go_url = '/statistics/energy_maintenance/project/'.$param['project'].'/zone/'.$param['zone'];
	}
	echo '	document.location = "'.$go_url.'";'."\n";
?>
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_4_1"><img src="/app/views/images/common/img_subtitle4_2.gif" alt="통계" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">통계</a> <span>&gt;</span> <strong>생애주기</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            
            <p class="bbstitle3"><strong id="title_project_area"></strong> / <strong id="title_zone_area"></strong> / <strong id="title_build_area"></strong>의 <?php echo $param['year']; ?> 년도 에너지 생애주기 통계</p>
            <dl class="tablebox1">
                <dd class="bbssearchbox1">
                    <fieldset>
                    <legend>검색</legend>
                    <select id="select_project" name="select_project" onchange="load_listzone(this.value, 'select_zone', 'select_build');">
<?php
	$select_project = 0;
	foreach($project as $pkey => $pval) {
		$project_seq = (int)$project[$pkey]->pj_seq;
		$project_name = $project[$pkey]->pj_name;
		if ((int)$param['project'] == $project_seq) {
			$select_project = $project_name;
			echo '<option value="'.$project_seq.'" selected="selected">'.$project_name.'</option>'."\n";
		} else {
			echo '<option value="'.$project_seq.'">'.$project_name.'</option>'."\n";
		}
	}
?>
                    </select>
                    <select id="select_zone" name="select_zone" onchange="load_listbuild(this.value, $('#select_project').val(), 'select_build');">
<?php
	foreach($zone as $zkey => $zval) {
		$zone_seq = $zone[$zkey]->zone_seq;
		$zone_name = $zone[$zkey]->zone_name;
		if ((int)$param['zone'] == $zone_seq) {
			$select_zone = $zone_name;
			echo '<option value="'.$zone_seq.'" selected="selected">'.$zone_name.'</option>'."\n";
		} else {
			echo '<option value="'.$zone_seq.'">'.$zone_name.'</option>'."\n";
		}
	}
?>
                    </select>
                    <select id="select_build" name="select_build">
<?php
	foreach($build as $bkey => $bval) {
		$build_seq = $build[$bkey]->build_seq;
		$build_name = $build[$bkey]->build_name;
		if ((int)$param['build'] == (int)$build[$bkey]->build_seq) {
			$select_build = $build_name;
			echo '<option value="'.$build_seq.'" selected="selected">'.$build_name.'</option>'."\n";
		} else {
			echo '<option value="'.$build_seq.'">'.$build_name.'</option>'."\n";
		}
	}
?>
                    </select>
                    <input type="image" src="/app/views/images/common/icon_zoom1.gif" class="sbtn" title="검색" onclick="change_check();" />
                    </fieldset>
                </dd>
            </dl>
            
            <dl id="statslisttapmenu" class="statslisttapmenu1">
            	<dd class="menu1"><a href="/statistics/lifecycle_list" class="strong">Energy</a></dd>
                <dd class="menu2"><a href="javascript:go_energy_maintenance();">유지보수</a></dd>
            </dl>

            <dl class="tablebox1">
<script type="text/javascript">
FusionCharts.ready(function () {
    var myChart1 = new FusionCharts({
        type: 'msline',
        width: '100%',
        height: '400',
        renderAt: 'chart-container1',

        dataFormat: 'json',
        dataSource: <?php echo json_encode($chart1); ?>
    });

    // Render the chart.
    myChart1.render();
});
</script>
                <dd class="bbschart1"><div id="chart-container1">FusionCharts will load here...</div></dd>
  <script type="text/javascript">
FusionCharts.ready(function () {
    var myChart2 = new FusionCharts({
        type: 'msline',
        width: '100%',
        height: '400',
        renderAt: 'chart-container2',

        dataFormat: 'json',
        dataSource: <?php echo json_encode($chart2); ?>
    });

    // Render the chart.
    myChart2.render();
});
</script>
                <dd class="bbschart1"><div id="chart-container2">FusionCharts will load here...</div></dd>
                <dd class="bbssearchbox2">
                    <fieldset>
                    <legend>검색</legend>
                    <select id="select_year" name="select_year">
<?php
	for ($i=(int)$build_year->year_min; $i<=(int)$build_year->year_max; $i++) {
		if ($i == (int)$param['year']) {
			echo '						<option value="'.$i.'" selected="selected">'.$i.'년</option>'."\n";
		} else {
			echo '						<option value="'.$i.'">'.$i.'년</option>'."\n";
		}
	}
?>
                    </select>
                    <input type="image" src="/app/views/images/common/icon_zoom1.gif" class="sbtn" title="검색" onclick="change_check2($('#select_year').val());" />
                    </fieldset>
                </dd>
                <dd class="bbslistbox2">
                <br />
                    <table class="tablelisttype3" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th colspan="2"><?php echo $param['year'];?>년 / 월</th>
                            <th>1월</th>
                            <th>2월</th>
                            <th>3월</th>
                            <th>4월</th>
                            <th>5월</th>
                            <th>6월</th>
                            <th>7월</th>
                            <th>8월</th>
                            <th>9월</th>
                            <th>10월</th>
                            <th>11월</th>
                            <th>12월</th>
                            <th>합계</th>
                        </tr>
                        <tr class="trst1">
                            <td colspan="2">발생예상</td>
<?php
	$sum_step1 = 0.0;
	for ($i=1; $i<=12; $i++) {
		$sum_step1 += (float)$stat['step1'][$i];
		echo '							<td>'.$stat['step1'][$i].'</td>'."\n";
	}
	echo '							<td>'.$sum_step1.'</td>'."\n";
?>
                        </tr>
                        <tr>
                            <td rowspan="7">실발생량</td>
                            <td>Total</td>
<?php
	$sum_total = 0.0;
	$sum_line = array();
	$sum_line[0] = 0.0;
	for ($i=1; $i<=12; $i++) {
		$sum_line[$i] = (float)$stat['data1'][$i] + (float)$stat['data2'][$i] + (float)$stat['data3'][$i] + (float)$stat['data4'][$i] + (float)$stat['data5'][$i] + (float)$stat['data6'][$i];
		$sum_total += $sum_line[$i];
	}
	for ($i=1; $i<=12; $i++) {
		echo '							<td>'.$sum_line[$i].'</td>'."\n";
	}	
	echo '							<td>'.$sum_total.'</td>'."\n";
?>
                        </tr>
                        <tr class="trst1">
                            <td>전기</td>
<?php
	$sum_data1 = 0.0;
	for ($i=1; $i<=12; $i++) {
		$sum_data1 += (float)$stat['data1'][$i];
		echo '							<td>'.$stat['data1'][$i].'</td>'."\n";
	}
	echo '							<td>'.$sum_data1.'</td>'."\n";
?>
                        </tr>
                        <tr>
                            <td>상수도</td>
<?php
	$sum_data2 = 0.0;
	for ($i=1; $i<=12; $i++) {
		$sum_data2 += (float)$stat['data2'][$i];
		echo '							<td>'.$stat['data2'][$i].'</td>'."\n";
	}
	echo '							<td>'.$sum_data2.'</td>'."\n";
?>
                        </tr>
                        <tr class="trst1">
                            <td>경유</td>
<?php
	$sum_data3 = 0.0;
	for ($i=1; $i<=12; $i++) {
		$sum_data3 += (float)$stat['data3'][$i];
		echo '							<td>'.$stat['data3'][$i].'</td>'."\n";
	}
	echo '							<td>'.$sum_data3.'</td>'."\n";
?>
                        </tr>
                        <tr>
                            <td>도시가스</td>
<?php
	$sum_data4 = 0.0;
	for ($i=1; $i<=12; $i++) {
		$sum_data4 += (float)$stat['data4'][$i];
		echo '							<td>'.$stat['data4'][$i].'</td>'."\n";
	}
	echo '							<td>'.$sum_data4.'</td>'."\n";
?>
                        </tr>
                        <tr class="trst1">
                            <td>LPG</td>
<?php
	$sum_data5 = 0.0;
	for ($i=1; $i<=12; $i++) {
		$sum_data5 += (float)$stat['data5'][$i];
		echo '							<td>'.$stat['data5'][$i].'</td>'."\n";
	}
	echo '							<td>'.$sum_data5.'</td>'."\n";
?>
                        </tr>
                        <tr>
                            <td>펠</td>
<?php
	$sum_data6 = 0.0;
	for ($i=1; $i<=12; $i++) {
		$sum_data6 += (float)$stat['data6'][$i];
		echo '							<td>'.$stat['data6'][$i].'</td>'."\n";
	}
	echo '							<td>'.$sum_data6.'</td>'."\n";
?>
                        </tr>
                    </table>
                </dd>        
            </dl>

        </div>
        <!--//본문 -->            
<script type="text/javascript">
<!--
	$('#title_project_area').html('<?=$select_project?>');
	$('#title_zone_area').html('<?=$select_zone?>');
	$('#title_build_area').html('<?=$select_build?>');
-->
</script>

    </div>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>