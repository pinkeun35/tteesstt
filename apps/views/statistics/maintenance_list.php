<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_4_3"><img src="/app/views/images/common/img_subtitle4_3.gif" alt="통계" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">통계</a> <span>&gt;</span> <strong>유지보수</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            
            <p class="bbstitle3"><strong>Project</strong> 별 유지보수 주기</p>

            <dl class="tablebox1">
                <dd class="bbslistbox3">
                    <table class="tablelisttype3" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>년도</th>
<?php
	$sum_occurrence = array();
	$sum_maintenance = array();
	foreach($stat['year'] as $skey => $sval) {
		echo '							<th>'.$sval.'</th>'."\n";
		$sum_occurrence[$skey] = 0.0;
		$sum_maintenance[$skey] = 0.0;
	}
?>
                        </tr>
<?php
	foreach($stat['project'] as $skey => $sval) {
?>
                        <tr class="trst1">
                            <td rowspan="2"><?php echo $stat['project_name'][$skey]?></td>
<?php
		foreach($stat['data1'][$skey] as $dkey => $dval) {
			echo '							<td>'.$dval.'</td>'."\n";
			$sum_occurrence[$dkey] += (float)$dval;
		}
?>
                        </tr>
                        <tr>
<?php
		foreach($stat['data2'][$skey] as $dkey => $dval) {
			echo '							<td>'.$dval.'</td>'."\n";
			$sum_maintenance[$dkey] += (float)$dval;
		}
?>
                        </tr>
<?php
	}
?>
                        <tr class="trst1">
                            <td rowspan="2">Total</td>
<?php
		foreach($sum_occurrence as $dkey => $dval) {
			echo '							<td>'.$dval.'</td>'."\n";
		}
?>
                        </tr>
                        <tr>
<?php
		foreach($sum_maintenance as $dkey => $dval) {
			echo '							<td>'.$dval.'</td>'."\n";
		}
?>
                        </tr>
                    </table>
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