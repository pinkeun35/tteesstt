<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->
<script type="text/javascript" src="/app/views/js/fusioncharts/fusioncharts.js"></script>
<script type="text/javascript">
<!--
function change_check() {
	var change_url = '/statistics/lifecycle_build/project/'+$('#select_project').val()+'/zone/'+$('#select_zone').val();
	if ($('#select_build').val() != 0) {
		change_url += '/build/'+$('#select_build').val();
	}

	document.location = change_url;
}

function load_listzone(vVal, vStep2, vStep3) {
	$.post( '/usedata/load_listzone', { project: vVal })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$("select[name='" + vStep2 + "'] option").remove();
				$("select[name='" + vStep3 + "'] option").remove();
				$("<option></option>").attr("selected", true).text(":건물선택:").attr("value", "0").appendTo("select[name='" + vStep3 + "']");
	
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
					$("<option></option>").attr("selected", true).text(":건물선택:").attr("value", "0").appendTo("select[name='" + vStep3 + "']");
		
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
            
            <p class="bbstitle3"><strong id="title_project_area"></strong> / <strong id="title_zone_area"></strong>의 건물별 에너지 생애주기 통계</p>
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
	echo '<option value="0">:건물선택:</option>'."\n";
	foreach($build as $bkey => $bval) {
		$build_seq = $build[$bkey]->build_seq;
		$build_name = $build[$bkey]->build_name;
		if ((int)$param['build'] == (int)$build[$bkey]->build_seq) {
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
                <dd class="bbschart1">
                	<div>
<script type="text/javascript">
FusionCharts.ready(function () {
    var myChart_pie = new FusionCharts({
        type: 'pie2D',
        width: '100%',
        height: '400',
        renderAt: 'chart-pie',

        dataFormat: 'json',
        dataSource: <?php echo json_encode($pie); ?>
    });

    // Render the chart.
    myChart_pie.render();
});
</script>
                		<span width="50%" id="chart-pie">FusionCharts will load here...</span>
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
                		<span width="50%" id="chart-container1">FusionCharts will load here...</span>
                	</div>
                </dd>
<script type="text/javascript">
FusionCharts.ready(function () {
    var myChart2 = new FusionCharts({
        type: 'mscombidy2d',
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
                <dd class="bbslistbox2">
<?php
	if (count($stat) > 0) {
?>
                    <table class="tablelisttype3" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>&nbsp;</th>
<?php
		$stat_val = $stat;
		foreach($stat_val[0]['year'] as $skey => $sval) {
			echo '							<th>'.$sval.'</th>'."\n";
		}
?>
                        </tr>
<?php
		foreach($stat_val as $key => $val) {
?>
                        <tr class="trst1">
                            <td class="l"><?php echo $stat_val[$key]['build_name']?></td>
<?php
			foreach($stat_val[$key]['data'] as $skey => $sval) {
				if ((float)$sval == 0.0) {
					echo '							<td>'.$sval.'</td>'."\n";
				} else {
					echo '							<td><a href="/statistics/lifecycle_buildyear/project/'.$param['project'].'/zone/'.$param['zone'].'/build/'.$stat_val[$key]['build_seq'].'/year/'.$stat_val[$key]['year'][$skey].'">'.$sval.'</a></td>'."\n";
				}
			}
?>
                        </tr>
<?php
		}
?>
                    </table>
<?php
	}
?>
                </dd>        
            </dl>

        </div>
        <!--//본문 -->            
<script type="text/javascript">
<!--
	$('#title_project_area').html('<?=$select_project?>');
	$('#title_zone_area').html('<?=$select_zone?>');
-->
</script>

    </div>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>