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
            
            <p class="bbstitle3"><strong id="title_project_area"></strong>의 에너지 생애주기 통계</p>

<?php
	$loop_cnt = 0;
?>
            <dl class="tablebox1">
                <dd class="bbssearchbox1">
                    <fieldset>
                    <legend>검색</legend>
                    <select id="select_project">
<?php
	foreach($project as $pkey => $pval) {
		if ((int)$param['project'] == (int)$project[$pkey]->pj_seq) {
			$project_name = $project[$pkey]->pj_name;
			echo '<option value="/statistics/lifecycle_zone/project/'.$project[$pkey]->pj_seq.'" selected="selected">'.$project[$pkey]->pj_name.'</option>'."\n";
		} else {
			echo '<option value="/statistics/lifecycle_zone/project/'.$project[$pkey]->pj_seq.'">'.$project[$pkey]->pj_name.'</option>'."\n";
		}
	}
?>
                    </select>
                    <input type="image" src="/app/views/images/common/icon_zoom1.gif" class="sbtn" title="검색" onclick="document.location=$('#select_project').val();" />
                    </fieldset>
                </dd>
                <dd class="bbssubtitle1">&#8226; Energy Co2 배출 예정 통계</dd>
<?php
	$stat_val = $stat;
	foreach($stat as $key => $val) {
		if ($loop_cnt == 0) {
?>
                <dd class="bbschart1">
<script type="text/javascript">
FusionCharts.ready(function () {
    var myChart<?php echo $stat_val[$key]['zone_seq'];?> = new FusionCharts({
        type: 'msline',
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
                <dd class="bbslistbox2">
                    <table class="tablelisttype3" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>&nbsp;</th>
<?php
			foreach($stat_val[$key]['year'] as $ykey => $yval) {
				echo '                            <th>'.$stat_val[$key]['year'][$ykey].'</th>'."\n";
			}
?>
                        </tr>
<?php
		}
		
?>
                        <tr class="trst1">
<?php
		echo '                            <td class="l"><a href="/statistics/lifecycle_build/project/'.$stat_val[$key]['pj_seq'].'/zone/'.$stat_val[$key]['zone_seq'].'">'.$stat_val[$key]['zone_name'].'</a></td>'."\n";
		foreach($stat_val[$key]['year'] as $ykey => $yval) {
			echo '                            <td>'.$stat_val[$key]['year'][$ykey].'</td>'."\n";
		}
?>
                        </tr>
<?php
		$loop_cnt++;
	}

	if (count($stat) > 0) {
?>
                    </table>
<?php
	}
?>
                </dd>        
            </dl>
<script type="text/javascript">
<!--
	$('#title_project_area').html('<?=$project_name?>');
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