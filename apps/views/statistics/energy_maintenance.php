<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->
<script type="text/javascript" src="/app/views/js/fusioncharts/fusioncharts.js"></script>
<script type="text/javascript">
<!--
function change_check() {
	var change_url = '/statistics/energy_maintenance/project/'+$('#select_project').val()+'/zone/'+$('#select_zone').val();
	if ($('#select_build').val() != '0')
		change_url += '/build/'+$('#select_build').val();
	
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
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_4_1"><img src="/app/views/images/common/img_subtitle4_2.gif" alt="통계" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">통계</a> <span>&gt;</span> <strong>생애주기</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            
            <p class="bbstitle3"><strong id="title_project_area"></strong> / <strong id="title_zone_area"></strong> / <strong id="title_build_area"></strong>의 유지보수 생애주기 통계</p>
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
	$select_build = '건물 전체';
	echo '<option value="0">:건물선택:</option>'."\n";
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
            <dl id="statslisttapmenu" class="statslisttapmenu2">
            	<dd class="menu1"><a href="#;">Energy</a></dd>
                <dd class="menu2"><a href="#;" class="strong">유지보수</a></dd>
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
        dataSource: <?php echo json_encode($chart); ?>
    });

    // Render the chart.
    myChart1.render();
});
</script>
                <dd class="bbschart1"><div id="chart-container1">FusionCharts will load here...</div></dd>
                <dd class="bbslistbox2">
                    <table class="tablelisttype3" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>년도</th>
<?php
	$sum_line = array();
	$sum_total = 0.0;
	foreach($stat['year'] as $skey => $sval) {
		echo '							<th>'.$sval.'</th>'."\n";
		$sum_line[$skey] = 0.0;
	}
?>
                        </tr>
<?php
	foreach($stat['build_name'] as $skey => $sval) {
?>
                        <tr class="trst1">
                            <td class="l"><a href="#;"><?php echo $stat['build_name'][$skey]?></a></td>
<?php
		foreach($stat['data'][$skey] as $dkey => $dval) {
			echo '							<td>'.$dval.'</td>'."\n";
			$sum_line[$dkey] += (float)$dval;
		}
?>
                        </tr>
<?php
	}
?>
                        <tr class="trst1">
                            <td class="l"><strong>Total</strong></td>
<?php
	foreach($sum_line as $skey => $sval) {
		echo '							<td>'.$sval.'</td>'."\n";
	}
?>
                        </tr>
                    </table>
                </dd>        
            </dl>
<script type="text/javascript">
<!--
	$('#title_project_area').html('<?=$select_project?>');
	$('#title_zone_area').html('<?=$select_zone?>');
	$('#title_build_area').html('<?=$select_build?>');
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