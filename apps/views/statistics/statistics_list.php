<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->
<script type="text/javascript" src="/app/views/js/fusioncharts/fusioncharts.js"></script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_4_1"><img src="/app/views/images/common/img_subtitle4_1.gif" alt="통계" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">통계</a> <span>&gt;</span> <strong>전체통계</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            
            <p class="bbstitle3"><!-- <strong>홍길동</strong>님의 -->Project 전체 통계</p>
            
            <div class="btn_LCASTATIS"><button  class="st1" id="btn_LCASTATIS">LCA보고서</button></div>

            <dl class="tablebox1">
            	<dd class="bbslisttotal"><!-- Total : <strong>7</strong>/2100 --></dd>
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
                	
                </dd>
                <dd class="bbslistbox1">
                    <table class="tablelisttype3" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>No.
                            <th>Project명</th>
                            <th>지구온난화<br />Kgco2</th>
                            <th>자원소모<br />Kgsb</th>
                            <th>오존층영향<br />Kgcfc11</th>
                            <th>산성화<br />KgSO2</th>
                            <th>부영양화<br />KgPO4 3-</th>
                            <th>광화학적산화물<br />KgC2H4</th>
                            <th>물발자국<br />KgH2O</th>
                        </tr>
<?php
	$line_num = count($stat);
	foreach($stat as $key => $val) {
		$sum_use = (float)$stat[$key]['use_energy_schedule'] + (float)$stat[$key]['use_preuse'] + (float)$stat[$key]['use_occurrence'] + (float)$stat[$key]['use_info'] + (float)$stat[$key]['use_maintenance'];
		$sum_line = $sum_use + (float)$stat[$key]['pre_use'] + (float)$stat[$key]['post_use'];
		$class_name = "trst1";
		if (($line_num % 2) == 0)
			$class_name = "";
?>
                        <tr class="<?php echo $class_name; ?>">
                            <td><?php echo $line_num;?></td>
                            <td><a href="/statistics/statistics_zone/project/<?php echo $stat[$key]['pj_seq'];?>"><?php echo $stat[$key]['pj_name'];?></a></td>
                            <td><?php echo $sum_line;?></td>
                            <td><?php echo $sum_use;?></td>
                            <td><?php echo $stat[$key]['post_use'];?></td>
                            <td><?php echo $sum_line;?></td>
                            <td><?php echo $sum_use;?></td>
                            <td><?php echo $stat[$key]['post_use'];?></td>
                            <td><?php echo $sum_line;?></td>
                        </tr>
<?php
		$line_num--;
	}
?>
                    </table>
                    <br />
                                        <div id="chart-container">FusionCharts will load here...</div>

				</dd>        
                <dd class="bbsbtnbox1">
                    <div class="btntype1"></div>
                    <div class="btntype2"></div>
                    <div class="pageing"><!-- <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page1.gif" alt="처음" /></a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page2.gif" alt="이전" /></a> <a href="#;">5</a> <a href="#;">6</a> <a href="#;">7</a> <a href="#;">8</a> <a href="#;" class="strong">9</a> <a href="#;">10</a> <a href="#;">11</a> <a href="#;">12</a> <a href="#;">13</a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page3.gif" alt="다음" /></a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page4.gif" alt="마지막" /></a> --></div>
                </dd>
            </dl>

        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup_LCASTATIS">
<style>
.dox_V_kist_LCA{ line-height:180%; font-family:'Nanum Gothic', gulim, dotum, Arial, Helvetica, sans-serif;}
.dox_V_kist_LCA h1{ position:relative; padding:10px !important; text-align:center; font-size:2em;}
.dox_V_kist_LCA h2{ position:relative; padding:3px !important; text-align:center; font-size:1.5em;}

.dox_V_kist_LCA .img100{ width:100%; max-width:900px;}
.dox_V_kist_LCA .imgtitle{ display:block; padding:20px 0 40px 0; text-align:center}

.dox_V_kist_LCA table{ width:100%; border:solid 1px #999; border-collapse:collapse;}
.dox_V_kist_LCA table tr th,
.dox_V_kist_LCA table tr td{ padding:5px 10px; border-right:solid 1px #999; border-bottom:solid 1px #999;}
.dox_V_kist_LCA table tr th{ vertical-align:text-bottom; background:#f2f2f2;}
.dox_V_kist_LCA table tr:last-child th,
.dox_V_kist_LCA table tr:last-child td{ border-bottom:0;}
.dox_V_kist_LCA table th{ border-right:solid 1px #ddd; word-break:keep-all;}

.dox_V_kist_LCA table table{ border:solid 1px #ddd;}
.dox_V_kist_LCA table table tr th,
.dox_V_kist_LCA table table tr td{ border-right:solid 1px #ddd; border-bottom:solid 1px #ddd !important;}
.dox_V_kist_LCA table table tr th{ vertical-align:middle; background:#f7f7f7;}

.dox_V_kist_LCA .theader th{ border:0;  border-bottom:solid 1px #999; background:#aaa; color:#fff;}
.dox_V_kist_LCA .trst1 td{ background:#FFC;}
.dox_V_kist_LCA .tdst1{ background:#E9F8FE;}
.dox_V_kist_LCA .tdst2{ color:#F00}
</style>
<div class="dox_V_kist_LCA">
    <h1>건축물 전과정평가(LCA)  수행 보고서</h1>
  <h2>건물명 : LCA 테스트</h2>
    
    <table>
        <tr class="theader">
            <th colspan="2">Ⅰ.전과정평가 개요</th>
        </tr>
        <tr>
            <th>1.평가 대상 개요</th>
            <td>
            
                <table>
                    <tr>
                        <th>소재지</th>
                    <td>테스트 단지</td>
                    </tr>
                    <tr>
                        <th>평면</th>
                        <td><img src="/app/views/images/v-kist/vkist_img_1.jpg" class="img100" /><span class="imgtitle">그림 1 배치도 샘플</span><img src="/app/views/images/v-kist/vkist_img_2.jpg" class="img100" /><span class="imgtitle">그림 2  테스트 1층</span></td>
                    </tr>
                    <tr>
                      <th>대지면적</th>
                      <td>5,302.17 ㎡</td>
                    </tr>
                    <tr>
                      <th>건축면적</th>
                      <td>2.122.00 ㎡</td>
                    </tr>
                    <tr>
                      <th>연면적</th>
                      <td>6,310.92 ㎡</td>
                    </tr>
                    <tr>
                      <th>건폐율</th>
                      <td>40 %</td>
                    </tr>
                    <tr>
                      <th>용적율</th>
                      <td>119 %</td>
                    </tr>
                    <tr>
                      <th>층수</th>
                      <td>지상 4층</td>
                    </tr>
                    <tr>
                      <th>건축용도</th>
                      <td>교육연구시설</td>
                    </tr>
                    <tr>
                      <th>주요시설</th>
                      <td>연구동 3개동, 본관동 1개동 중 본관동   대상</td>
                    </tr>
                    <tr>
                      <th>난방방식</th>
                      <td>난방설비 : EHP, AHU</td>
                    </tr>
                    <tr>
                      <th>발주처</th>
                      <td>샘플</td>
                    </tr>
                    <tr>
                      <th>CM</th>
                      <td>샘플</td>
                    </tr>
                    <tr>
                      <th>설계사</th>
                      <td>샘플</td>
                    </tr>
                </table>
                <span class="imgtitle">그림 1 테스트배치도</span>
          </td>
        </tr>
        <tr>
            <th>2. 평가기준</th>
            <td>기능 및 기능단위
              <table>
                <tr>
                  <th>기능</th>
                  <td>교육연구 생활을 위한  기능</td>
                </tr>
                <tr>
                  <th>기능단위</th>
                  <td>50년간 사용할 교육연구시설 건축물</td>
                </tr>
                <tr>
                  <th>기준흐름</th>
                  <td>50년간 사용할 교육연구시설 건축물에 투입되는 물질과   에너지</td>
                </tr>
            </table>
            <span class="imgtitle">표 1 테스트 건축물의 개요</span>
              ○ 기능단위 및 기준흐름은 교육연구시설 건축물과 그에 투입되는 물질과 에너지로 설정함<br />
            ○ 녹색건축인증(G-SEED) 및 건축물 전과정 평가지침(안) 내 건축물 사용기간 50년을 적용하였음.</td>
        </tr>
        <tr>
          <th>3. 시스템경계</th>
          <td>○ 본 테스트 건축물에 대한 전과정평가의 &quot;Cradle to Gate&quot; 시스템경계는 투입되는  자재를 생산하는 단계에서부터 건축물로서 수명이 다하여 해체되고 건설폐기물이 재활용 또는 매립되는 단계까지임.
            <img src="/app/views/images/v-kist/vkist_img_3.jpg" class="img100" /><span class="imgtitle">표 1 테스트 건축물의 개요</span>■ 전과정단계<br />
            (1) 자재생산 단계<br />
            자재생산의 시스템 경계는 건축재료의 생산을 위한 원료의 채취와 가공, 제품의 제조 등 생산에 필요한 자원과 에너지를 소비하여 건축물에 투입될 건축자재를 완제품 또는 반제 품의 상태로 생산하는 모든 공정을   포함함.<br />
            (2) 시공 단계<br />
            시공의 시스템경계는 건축물을 구성할 자재를 생산지에서 건설현장까지 운송해오는 과정에서부터 시공 공종별로 건설자재, 에너지 및 각종 기계장비들을 투입하여 건축물을 완성하는 단계를 포함함.<br />
            (3) 사용 및 유지보수  단계<br />
            사용 및 유지보수의 시스템경계는 완성된 건축물을 해체하기 전까지 이용자가 건물에 상주하면서 다양한 설비기계들을 이용하고 추가적인 수선 및 보수를 통하여 점차 노후화되는 건축물의 상태를 유지하는 과정을   포함함.<br />
            (4) 폐기 및 재활용  단계<br />
            건축물의  용도가 다하면  장비를  이용하여 건축물을  해체하고 발생된  건축폐자재를 처리장까지 수송하여 일부 폐기하거나 다른 제품의 원료로서    재활용하는 과정을 포함함.<br />
            ○ 테스트 건축물 LCA 시스템경계 단위공정 설명<br />
            <br />
            <table>
              <tr>
                <th>구분</th>
                <th>일련번호</th>
                <th>단위공정명</th>
                <th>설명</th>
              </tr>
              <tr>
                <td>자재생산 단계</td>
                <td>①</td>
                <td>건축자재생산</td>
                <td><p align="left">원료의 채취와 가공,  제품의 제조 등 생산에 필요한 자원과 에너지를 소비하여 건축물에 투입될 건축자재를 생산하는  과정</p></td>
              </tr>
              <tr>
                <td rowspan="2">시공 단계</td>
                <td>②</td>
                <td>건축자재운반</td>
                <td>건축물에 투입될 자재를 구입처 및 저장소 에서 시공현장까지 운반하는  과정</td>
              </tr>
              <tr>
                <td>③</td>
                <td>건축물시공</td>
                <td>현장에 운반된 자재를 각종 건설 장비를 활용하여 건축물을  시공하는  과정</td>
              </tr>
              <tr>
                <td rowspan="2">사용 단계</td>
                <td>④</td>
                <td>건물사용</td>
                <td>거주자가 건물을 점유하는 동안  다양한 설비기계들을  이용하여  실내  환경을 쾌적하게 유지하면서 생활하는  과정</td>
              </tr>
              <tr>
                <td>⑤</td>
                <td>건물유지보수</td>
                <td>시간이  지남에  따라  노후화되는  건물을 수선 및 보수를 통해 건물의 상태를 건설초기 상황과 유사하게 유지하는  과정</td>
              </tr>
              <tr>
                <td rowspan="4">폐기 및 재활용 단계</td>
                <td>⑥</td>
                <td>건축물해체</td>
                <td>건축물을 건설장비를 사용하여 해체하는  과정</td>
              </tr>
              <tr>
                <td>⑦</td>
                <td>폐자재운송</td>
                <td>해체 후 발생된 폐자재를 처리방법에 따라 해당 처리장으로  수송하는  과정</td>
              </tr>
              <tr>
                <td>⑧</td>
                <td>재활용처리</td>
                <td>재활용 가능한 폐자재를 파쇄 및 선별 작업  을 통하여 다른 제품의 원료로 사용하거나  새로운 제품으로 제조하는  과정</td>
              </tr>
              <tr>
                <td>⑨</td>
                <td>폐자재매립/소각</td>
                <td>재활용이 불가능한 폐자재를 매립하거나 소각하는 과정</td>
              </tr>
            </table>
            <span class="imgtitle">표 3 테스트 건축물의 시스템경계 설명</span>
            </td>
        </tr>
        <tr>
          <th>4. 데이터수집</th>
          <td>■ 데이터 범주<br />
            ○ 투입물 및 산출물에 대한 데이터 범주에 대해 정리하면 다음과 같음.<br />
            ○ 원료물질, 보조물질 : 원료물질 및 보조물질에는 건축물을 구성하는데 투입된  건축자재들인 레미콘, 콘크리트벽돌, 철근, 모래, 시멘트, 잡석이    포함됨.<br />
            - 간이 견적을 통해 도출된 약 20개 건설자재 중 질량기준 Cut-Off 99%에 해당되는 건설자재 7종을 평가대상으로  하였음.<br />
            ○ 에너지 : 에너지 범주에는 원료물질 채취 및 제품제조 공정에서 사용되는 설비가동소모 전력, 운송장비 및 건설장비의 활용 시 투입된 경유, 사용기간 동안 투입된 전력 에너지가 포함됨.<br />
            ○ 제품 및 부산물 : 제품에는 최종제품인 철근콘크리트 건축물(테스트)임.<br />
            ○ 대기배출물 : 대기배출물은 이산화탄소(CO2), 메탄(CH4), 아산화질소(N2O), 수소불화탄소(HFC), 과불화탄소(PFC), 육불화황(SF6) 등 6대   온실가스임.<br />
          ○ 폐기물 : 폐기물은 건축물의 해체 후 발생된 폐콘크리트(레미콘),   폐토석(잡석/모래), 폐벽돌(콘크리트벽돌), 폐금속(철근). 혼합건설폐기물(시멘트)임.<br />
            <table>
              <tr>
                <th>데이터범주</th>
                <th colspan="2">세부항목</th>
                <th>설명</th>
              </tr>
              <tr>
                <td rowspan="2">투입물</td>
                <td>원료 및 보조물질</td>
                <td>레미콘, 콘크리트벽돌, 철근, 모래, 시멘트, 잡석 등</td>
                <td><p align="left">RC건축물 주요 투입 자재</p></td>
              </tr>
              <tr>
                <td>에너지</td>
                <td>경유, 전기</td>
                <td>-</td>
              </tr>
              <tr>
                <td rowspan="3">산출물</td>
                <td>제품, 부산물</td>
                <td>철근콘크리트 건축물</td>
                <td>-</td>
              </tr>
              <tr>
                <td>대기배출물</td>
                <td>이산화탄소(CO2),  메탄(CH4), 아산화질소<br />
                (N2O), 수소불화탄소(HFC), 과불화탄소 (PFC),  육불화황(SF6) 등</td>
                <td>6대 온실가스</td>
              </tr>
              <tr>
                <td>고형폐기물</td>
                <td>폐콘크리트, 폐토석, 폐벽돌,	폐금속, 혼합건설폐기물</td>
                <td>-</td>
              </tr>
            </table>
            <span class="imgtitle">표 4 데이터 범주별  세부항목</span>
■ 데이터 품질요건<br />

○ 데이터의 필수적인 품질 요건인 시간적 범위, 지역적 범위, 기술적 범위를 전과정 단 계별로 설정함.
            <table>
              <tr>
                <th>구분</th>
                <th>자재생산단계</th>
                <th>시공단계</th>
                <th>사용단계</th>
                <th>폐기단계</th>
              </tr>
              <tr>
                <td>시간적 범위</td>
                <td>일반데이터<br />
                (2002-2006)</td>
                <td>일반데이터<br />
                (2005-2012)</td>
                <td><p align="left">일반데이터<br />
                (2009-2016)</p></td>
                <td>일반데이터<br />
                (2009)</td>
              </tr>
              <tr>
                <td>공간적 범위</td>
                <td>대한민국</td>
                <td>대한민국</td>
                <td>대한민국</td>
                <td>대한민국</td>
              </tr>
              <tr>
                <td>기술적 범위</td>
                <td colspan="4">해당산업 및 해동공종의  대표기술</td>
              </tr>
            </table>
            <span class="imgtitle">표 5 데이터의 시간적, 공간적, 기술적 범위</span>
            <table>
              <tr>
                <th>단계</th>
                <th colspan="2">물질명</th>
                <th>시간적 범위</th>
                <th>공간적 범위</th>
                <th>데이터 출처</th>
              </tr>
              <tr>
                <td rowspan="6">자재생산 단계</td>
                <td colspan="2">레미콘</td>
                <td>2003</td>
                <td rowspan="6">대한민국</td>
                <td>산업통상자원부</td>
              </tr>
              <tr>
                <td colspan="2">콘크리트벽돌</td>
                <td>2005</td>
                <td>국토교통부</td>
              </tr>
              <tr>
                <td colspan="2">철근</td>
                <td>2006</td>
                <td>국토교통부</td>
              </tr>
              <tr>
                <td colspan="2">모래</td>
                <td>2005</td>
                <td>국토교통부</td>
              </tr>
              <tr>
                <td colspan="2">시멘트</td>
                <td>2002</td>
                <td>환경부</td>
              </tr>
              <tr>
                <td colspan="2">잡석</td>
                <td>2006</td>
                <td>국토교통부</td>
              </tr>
              <tr>
                <td rowspan="4">시공 단계</td>
                <td colspan="2">트럭</td>
                <td>2012</td>
                <td rowspan="4">대한민국</td>
                <td>환경부</td>
              </tr>
              <tr>
                <td colspan="2">레미콘 수송</td>
                <td>2005</td>
                <td>국토교통부</td>
              </tr>
              <tr>
                <td colspan="2">경유</td>
                <td>2006</td>
                <td>탄소성적표지</td>
              </tr>
              <tr>
                <td colspan="2">전력</td>
                <td>2009</td>
                <td>탄소성적표지</td>
              </tr>
              <tr>
                <td rowspan="2">사용 및 유지보수 단계</td>
                <td colspan="2">전력</td>
                <td>2009</td>
                <td rowspan="2">대한민국</td>
                <td>탄소성적표지</td>
              </tr>
              <tr>
                <td colspan="2">수선주기 및 수선율</td>
                <td>2012</td>
                <td>주택법 시행규칙</td>
              </tr>
              <tr>
                <td rowspan="10">폐기 및 재활용 단계</td>
                <td rowspan="5">재활용</td>
                <td>폐콘크리트</td>
                <td>2009</td>
                <td rowspan="10">대한민국</td>
                <td rowspan="10">탄소성적표지</td>
              </tr>
              <tr>
                <td>폐토석</td>
                <td>2009</td>
              </tr>
              <tr>
                <td>폐벽돌</td>
                <td>2009</td>
              </tr>
              <tr>
                <td>폐금속</td>
                <td>2009</td>
              </tr>
              <tr>
                <td>혼합건설폐기물</td>
                <td>2009</td>
              </tr>
              <tr>
                <td rowspan="4">매립</td>
                <td>폐토석</td>
                <td>2009</td>
              </tr>
              <tr>
                <td>폐벽돌</td>
                <td>2009</td>
              </tr>
              <tr>
                <td>폐금속</td>
                <td>2009</td>
              </tr>
              <tr>
                <td>혼합건설폐기물</td>
                <td>2009</td>
              </tr>
              <tr>
                <td>소각</td>
                <td>혼합건설폐기물</td>
                <td>2009</td>
              </tr>
            </table>
            <span class="imgtitle">표 6 일반데이터의  품질요건</span>
          </td>
        </tr>
        <tr>
          <th>5. 가정 및 제한 사항</th>
          <td>○ 수집된 모든 현장 데이터와 이들을 계산한 모든 데이터는 소수점 둘째자리까지  정리하는 것을 원칙으로  함.<br />
            ○ 자재생산단계에서 물량 산출 시 재사용되는 가설자재는 데이터 수집 대상에서 제외하였고 따라서 가설공종에 해당하는 기타 자재도 분석범위에서    제외함.<br />
            ○ 시공단계에서 에너지원에 대한 데이터 수집 시, 현장에 투입된 에너지사용에 관한 정확한  데이터의 입수가  불가능하였으므로  데이터 수집  범위는 조사대상의  공사비 내역서를 근거로 기계장비의 사용과 관련된 에너지사용량으로 한정짓되, 현장에 실제적으로 투입된 전력, 수도 등의 기타 에너지원에 대한 고려는 배제함.<br />
            - 국내 수송 평균거리는 30km이지만, 본 전과정 평가 시 수송 평균거리는 베트남 현지내 건축설계 전문가에 자문의견을 참고하여 60km로  가정하였음.<br />
            ○ 사용 및 유지보수단계에서 투입되는 물질과 에너지는 건물 전체의 규모나사용자의 행동 패턴에 따라 크게 달라질 수 있기 때문에 건물의 사용기간 중에 투입된 물질과 에<br />
            너지에 대한 정확한 데이터의 입수가 불가능함. 따라서 시나리오 기준을 설정하여 주요물질과 에너지의 투입량을  추산함.<br />
            ○ 건축물 사용기간 동안 투입된 주요 에너지원은 전력으로 설정하고, 전력사용량은  시뮬레이션  툴을 이용하여 도출된 값을  적용함.<br />
            - 연간 전력사용량은 국외 시뮬레이션 프로그램인 energy plus 및 equest, design builder등을 이용하여 도출하였음.<br />
            ○ 수선 시 투입된 자재의 물량은 주택법 시행규칙 별표 5의 장기수선계획의 수립기준을활용하여 수선주기(년) 및 수선율(%)에 따라 건축자재의 투입물량을   추산함.<br />
            ○ 건축물의 사용기간은 50년으로 가정하였음. 이는 건축물 전과정 평가지침 내 '건축물운용단계' 50년을 적용한 것임.<br />
            ○ 폐기 및 재활용단계에서 RC건축물의 철거는 인력으로 이루어지고 폐기물 적재  시에만  백호우가 투입된  것으로  조사되었기 때문에  해체과정에 투입된  에너지는 건설장비의활용 시 투입된 경유의 소모량으로   한정함.<br />
            ○ 건축물의 해체 시 발생한 폐자재의 총량은 건축물 시공시 투입된 건축자재의  총량과 사용 및 유지보수단계에서 수선 시 투입된 건축자재의    총량의 합으로 계산함.<br />
          ○ 건축물의 해체과정에서 발생한 폐자재 종류별 실측치를 구하는 것이 어려우므로 건설 폐기물 재활용  통계조사보고서의 2011년도 통계치인 재활용율, 매립율,  소각율을 폐기물 의 종류에 따라 적용하여 건설폐기물의 종류별 배출량을   추산함.</td>
        </tr>
        <tr>
          <th>6. 할당</th>
          <td>○ 본 전과정평가에서는 할당은 이루어지지 않았음</td>
        </tr>
        <tr class="theader">
          <th colspan="2">Ⅱ. 데이터 수집 및  계산</th>
        </tr>
        <tr>
          <td colspan="2">
<h3>1. 제외기준 선정</h3>
○ 본 전과정평가에서는 건축물에 투입되는 건축자재의 질량을 각 자재별 운송대수에 근거하여 산출하고 그에 따른 누적질량기여도를 검토한 뒤, 기타자재를 제외한 누적질량 기여도 99%에 해당하는 자재만을 대상으로 하여 전과정평가를   실시하였음.          
            <table>
              <tr>
                <th>자재 구분</th>
                <th>단위</th>
                <th>투입량</th>
                <th>비율</th>
                <th>누적질량 기여도(%)</th>
              </tr>
              <tr class="trst1">
                <td>레미콘</td>
                <td>kg</td>
                <td>10,644,400</td>
                <td>70.74%</td>
                <td>70.74%</td>
              </tr>
              <tr class="trst1">
                <td>콘크리트벽돌</td>
                <td>kg</td>
                <td>2,938,400</td>
                <td>19.53%</td>
                <td>90.27%</td>
              </tr>
              <tr class="trst1">
                <td>철근</td>
                <td>kg</td>
                <td>572,000</td>
                <td>3.80%</td>
                <td>94.07%</td>
              </tr>
              <tr class="trst1">
                <td>모래</td>
                <td>kg</td>
                <td>521,400</td>
                <td>3.47%</td>
                <td>97.54%</td>
              </tr>
              <tr class="trst1">
                <td>시멘트</td>
                <td>kg</td>
                <td>155,591</td>
                <td>1.03%</td>
                <td>98.57%</td>
              </tr>
              <tr class="trst1">
                <td>잡석</td>
                <td>kg</td>
                <td>152,857</td>
                <td>1.02%</td>
                <td>99.59%</td>
              </tr>
              <tr>
                <td>자기질타일</td>
                <td>kg</td>
                <td>16,860</td>
                <td>0.11%</td>
                <td>99.70%</td>
              </tr>
              <tr>
                <td>석고보드</td>
                <td>kg</td>
                <td>16,728</td>
                <td>0.11%</td>
                <td>99.81%</td>
              </tr>
              <tr>
                <td>경질우레탄폼단열재</td>
                <td>kg</td>
                <td>11,473</td>
                <td>0.08%</td>
                <td>99.89%</td>
              </tr>
              <tr>
                <td>싱글로이유리</td>
                <td>kg</td>
                <td>3,758</td>
                <td>0.02%</td>
                <td>99.91%</td>
              </tr>
              <tr>
                <td>도기질타일</td>
                <td>kg</td>
                <td>3,675</td>
                <td>0.02%</td>
                <td>99.93%</td>
              </tr>
              <tr>
                <td>특수(백색)시멘트</td>
                <td>kg</td>
                <td>3,307</td>
                <td>0.02%</td>
                <td>99.95%</td>
              </tr>
              <tr>
                <td>석기질타일</td>
                <td>kg</td>
                <td>2,700</td>
                <td>0.02%</td>
                <td>99.97%</td>
              </tr>
              <tr>
                <td>불연천장재</td>
                <td>kg</td>
                <td>1,102</td>
                <td>0.01%</td>
                <td>99.98%</td>
              </tr>
              <tr>
                <td>자연석판석</td>
                <td>kg</td>
                <td>1,504</td>
                <td>0.01%</td>
                <td>99.99%</td>
              </tr>
              <tr>
                <td>글라스울</td>
                <td>kg</td>
                <td>417</td>
                <td>0.01%</td>
                <td>99.99%</td>
              </tr>
              <tr>
                <td>단열모르타르</td>
                <td>kg</td>
                <td>175</td>
                <td>0.00%</td>
                <td>99.99%</td>
              </tr>
              <tr>
                <td>강화유리</td>
                <td>kg</td>
                <td>87</td>
                <td>0.00%</td>
                <td>99.99%</td>
              </tr>
              <tr>
                <td>알루미늄천장재</td>
                <td>kg</td>
                <td>64</td>
                <td>0.00%</td>
                <td>100.00%</td>
              </tr>
              <tr>
                <th>소계</th>
                <th>&nbsp;</th>
                <th>15,046,498</th>
                <th>100%</th>
                <th>100.00%</th>
              </tr>
            </table>
            <span class="imgtitle">표 7 가회동 한옥 주요 투입자재별   누적질량기여도</span>

            <h3>2. 자재생산 단계</h3>
            <h4>2.1. 재료별 투입물량(질량, ㎏)에 대한  환경영향평가</h4>
            
            <table>
              <tr>
                <th width="36%" rowspan="2">구분</th>
                <th width="16%" rowspan="2">번호</th>
                <th width="16%" rowspan="2">투입물 명칭</th>
                <th width="16%" rowspan="2">단위</th>
                <th width="16%" rowspan="2">양</th>
                <th width="16%" rowspan="2">투입비율</th>
                <th width="16%" rowspan="2">데이터 품질</th>
                <th width="16%" rowspan="2">근거 자료</th>
                <th colspan="3">LCI DB 사용(㎏CO₂/kg, ㎥)</th>
                <th width="16%" rowspan="2">계산 근거</th>
                <th width="16%" rowspan="2">CO₂발생량(㎏CO₂)</th>
                <th width="16%" rowspan="2">배출기여도(%)</th>
              </tr>
              <tr>
                <th width="16%">직접/간접</th>
                <th width="16%">DB명</th>
                <th width="16%">배출계수</th>
              </tr>
              <tr>
                <td rowspan="6">주요 투입자재</td>
                <td>1</td>
                <td>레미콘</td>
                <td>kg<br />
                (㎥)</td>
                <td><p>10,644,400<br />
                (4,628)</p></td>
                <td>70.74%</td>
                <td>계산치</td>
                <td>2016</td>
                <td>직접</td>
                <td>레미콘</td>
                <td>3.46E+02</td>
                <td>&nbsp;</td>
                <td>1,601,288.1</td>
                <td>68.6%</td>
              </tr>
              <tr>
                <td>2</td>
                <td>콘크리트벽돌</td>
                <td>kg</td>
                <td>2,938,400</td>
                <td>19.53%</td>
                <td>계산치</td>
                <td>2016</td>
                <td>직접</td>
                <td>콘크리트벽돌</td>
                <td>1.23E-01</td>
                <td>&nbsp;</td>
                <td>361,423.2</td>
                <td>15.5%</td>
              </tr>
              <tr>
                <td>3</td>
                <td>철근</td>
                <td>kg</td>
                <td>572,000</td>
                <td>3.80%</td>
                <td>계산치</td>
                <td>2016</td>
                <td>직접</td>
                <td>일반이형철근</td>
                <td>3.85E-01</td>
                <td>&nbsp;</td>
                <td>220,220.1</td>
                <td>9.4%</td>
              </tr>
              <tr>
                <td>4</td>
                <td>모래</td>
                <td>kg<br />
                (㎥)</td>
                <td><p>521,400<br />
                (330)</p></td>
                <td>3.47%</td>
                <td>계산치</td>
                <td>2016</td>
                <td>직접</td>
                <td>모래</td>
                <td>3.86E+00</td>
                <td>&nbsp;</td>
                <td>1,273.8</td>
                <td>0.1%</td>
              </tr>
              <tr>
                <td>5</td>
                <td>시멘트</td>
                <td>kg</td>
                <td>155,591</td>
                <td>1.03%</td>
                <td>계산치</td>
                <td>2016</td>
                <td>직접</td>
                <td>시멘트</td>
                <td>9.44E-01</td>
                <td>&nbsp;</td>
                <td>146877.9</td>
                <td>6.3%</td>
              </tr>
              <tr>
                <td>6</td>
                <td>잡석</td>
                <td>kg<br />
                (㎥)</td>
                <td><p>152,857<br />
                (428)</p></td>
                <td>1.02%</td>
                <td>계산치</td>
                <td>2016</td>
                <td>직접</td>
                <td>자갈</td>
                <td>1.13E+01</td>
                <td>&nbsp;</td>
                <td>4836.4</td>
                <td>0.2%</td>
              </tr>
            </table>
            <span class="imgtitle">표 8 자재생산단계의 자재별  CO₂배출량</span>[양식해설]<br />
            ○ 양: 평가대상의 기능단위를 기준으로 건설자재의 투입량을   기입한다.<br />
            ○  데이터품질:  수집된  현장데이터를&quot;측정치&quot;,  &quot;계산치&quot;,  &quot;추정치&quot;로  구분한다.<br />
            ○ 근거자료: 데이터의 계산근거 자료로 기업이 관리하는 문서의 명칭과 연도 기입한다.<br />
            ○ LCI DB 사용 : 직접배출/간접배출/사용안함을 선택하여  표기한다.<br />
            ○ 계산근거: 수집된 현장데이터를 무게단위로 환산한 계산과정을 기록하며, 산출근거는 별도로 첨부한다.


            <h3>3. 시공 단계</h3>
            <h4>3.1. 자재 운반</h4>
            
            <table>
              <tr>
                <th rowspan="2">품명</th>
                <th rowspan="2">투입물량① (ton)</th>
                <th rowspan="2">수송거리② (㎞)</th>
                <th rowspan="2">수송수단</th>
                <th rowspan="2">연료</th>
                <th colspan="2">LCI DB 사용 ③<br />
                (단위: ㎏-CO₂eq./ton․㎞)</th>
                <th rowspan="2">CO₂배출량 <br />
                (단위: ㎏-CO₂eq.)</th>
                <th rowspan="2">배출기여도</th>
              </tr>
              <tr>
                <th>DB명</th>
                <th>배출계수</th>
              </tr>
              <tr>
                <td>레미콘 운반</td>
                <td>10,644.4</td>
                <td>60</td>
                <td>레미콘 트럭(6㎥)</td>
                <td>경유</td>
                <td>트럭</td>
                <td>2.49E-01</td>
                <td>11523.72</td>
                <td>61%</td>
              </tr>
              <tr>
                <td>콘크리트벽돌 운반</td>
                <td>2,938.4</td>
                <td>60</td>
                <td>카고 8톤</td>
                <td>경유</td>
                <td>트럭</td>
                <td>2.49E-01</td>
                <td>5487.46</td>
                <td>29%</td>
              </tr>
              <tr>
                <td>철근 운반</td>
                <td>572.0</td>
                <td>60</td>
                <td>카고 8톤</td>
                <td>경유</td>
                <td>트럭</td>
                <td>2.49E-01</td>
                <td>1068.21</td>
                <td>6%</td>
              </tr>
              <tr>
                <td>모래 운반</td>
                <td>521.4</td>
                <td>60</td>
                <td>덤프 15톤</td>
                <td>경유</td>
                <td>트럭</td>
                <td>2.49E-01</td>
                <td>519.31</td>
                <td>3%</td>
              </tr>
              <tr>
                <td>시멘트 운반</td>
                <td>155.5</td>
                <td>60</td>
                <td>벌크 트럭(15ton)</td>
                <td>경유</td>
                <td>트럭</td>
                <td>2.49E-01</td>
                <td>154.87</td>
                <td>1%</td>
              </tr>
              <tr>
                <td>잡석 운반</td>
                <td>152.8</td>
                <td>60</td>
                <td>덤프 15톤</td>
                <td>경유</td>
                <td>트럭</td>
                <td>2.49E-01</td>
                <td>152.18</td>
                <td>1%</td>
              </tr>
              <tr>
                <td colspan="7">총 배출량</td>
                <td>18905.77</td>
                <td>100%</td>
              </tr>
            </table>
            [양식해설]<br />
            ○ 자재 운송장비별 CO₂배출량은 ①*②*③의 곱으로   계산한다.

            <h4>3.2. 건축물 시공 활동(행위)</h4>            
            <table>
          <tr>
                <th rowspan="2">용도</th>
                <th colspan="2" rowspan="2">품명</th>
                <th rowspan="2">규격</th>
                <th colspan="3">에너지원</th>
                <th colspan="3">이산화탄소배출원단위</th>
                <th rowspan="2">CO₂배출량<br />
                (단위: ㎏-CO₂eq.)</th>
          </tr>
              <tr>
                <th>연료</th>
                <th>소비량</th>
                <th>단위</th>
                <th>값</th>
                <th>단위</th>
                <th>자료출처</th>
              </tr>
              <tr>
                <td>철근콘크리트 타설</td>
                <td colspan="2">콘크리트 펌프차</td>
                <td>80 </td>
                <td>경유</td>
                <td>16.5</td>
                <td>L/hr</td>
                <td>6.82E-02</td>
                <td>㎏-CO₂eq./kg</td>
                <td>탄소성적표지</td>
                <td>55.33</td>
              </tr>
              <tr>
                <td>무근콘크리트 타설</td>
                <td colspan="2">콘크리트 펌프차</td>
                <td>80 </td>
                <td>경유</td>
                <td>16.5</td>
                <td>L/hr</td>
                <td>6.82E-02</td>
                <td>㎏-CO₂eq./kg</td>
                <td>탄소성적표지</td>
                <td>55.33</td>
              </tr>
              <tr>
                <td>다짐</td>
                <td colspan="2">CON'T 진동기</td>
                <td>0.93  ㎥/hr급</td>
                <td>전력</td>
                <td>0.75</td>
                <td>kW/hr</td>
                <td>4.95E-01</td>
                <td>㎏ CO₂eq./kwh</td>
                <td>탄소성적표지</td>
                <td>97.36</td>
              </tr>
              <tr>
                <td colspan="10">총 배출량</td>
                <td>208.03</td>
              </tr>
            </table>
            <p>[양식해설]<br />
              ○ 기계화 시공장비의 사용에 따른 이산화탄소배출량은 경유와 전력의 소비에 따른 이산화탄소배출량에 한정하여 추산할 수 있음.<br />
            ○ 에너지원별 연소에 따른 이산화탄소 배출 산정공식은 IPCC에서 규정한 식에 따름 </p>

            <h3>4. 사용 및 유지보수 단계</h3>
            <h4>4.1. 사용단계의 전력에너지 소비</h4>           
            <table>
              <tr>
                <th>구분</th>
                <th>연면적 (㎡)</th>
                <th>연간 전력사용량 </th>
                <th>CO₂배출계수 (㎏-CO₂eq./kwh)</th>
                <th>연간발생량 (㎏-CO₂eq.)</th>
                <th>건물 사용연수</th>
                <th>50년 전력사용 (㎏-CO₂eq.)</th>
              </tr>
              <tr>
                <td>교육시설</td>
                <td>6,310.92</td>
                <td>852275.87</td>
                <td>4.95E-01</td>
                <td>421,876.56</td>
                <td>50</td>
                <td>21,093,827.78</td>
              </tr>
              <tr>
                <td colspan="6">총 배출량</td>
                <td>21,093,827.78</td>
              </tr>
            </table>
            <h4>4.2. 사용단계의 난방에너지 소비</h4>           
            <table>
              <tr>
                <th>구분</th>
                <th>난방면적</th>
                <th>연간 단위면적당 LNG소모</th>
                <th>CO₂배출계수 (㎏-CO₂eq.)</th>
                <th>연간발생량 (㎏-CO₂eq.)</th>
                <th>건물 사용연수</th>
                <th>산출값 (㎏-CO₂eq.)</th>
              </tr>
              <tr>
                <td>교육시설</td>
                <td>사용안함</td>
                <td>사용안함</td>
                <td>-</td>
                <td>-</td>
                <td class="tdst2">50</td>
                <td>-</td>
              </tr>
              <tr>
                <td colspan="6">총 배출량</td>
                <td>-</td>
              </tr>
            </table>
            <h4>4.3. 건축물 수선(유지보수)에 따른 부위별 CO₂배출량 산정</h4>           
            <table>
              <tr>
                <th rowspan="2">구분</th>
                <th rowspan="2">부위</th>
                <th rowspan="2">공사종별</th>
                <th rowspan="2">자재명</th>
                <th rowspan="2">단위</th>
                <th rowspan="2">양</th>
                <th rowspan="2"><p>데이터품질</p></th>
                <th rowspan="2">근거자료</th>
                <th colspan="2">LCI DB 사용(㎏-CO₂eq./kg)</th>
                <th rowspan="2">계산근거</th>
                <th rowspan="2">CO₂발생량<br />
                (㎏-CO₂eq.)</th>
              </tr>
              <tr>
                <th>DB명</th>
                <th>배출계수</th>
              </tr>
              <tr>
                <td>건물외부</td>
                <td>외벽</td>
                <td>모르타르마감</td>
                <td>시멘트</td>
                <td>kg</td>
                <td>70,015</td>
                <td>계산치</td>
                <td>2014</td>
                <td>시멘트</td>
                <td>9.44E-01</td>
                <td>&nbsp;</td>
                <td>66094.16</td>
              </tr>
              <tr>
                <td colspan="11">총 배출량</td>
                <td>66094.16</td>
              </tr>
            </table>
            <span class="imgtitle">표 13 건축물 유지보수에 따른 부위별 CO₂배출량 산정</span>
            [양식해설]<br />
            ○ 주택법 시행규칙(별표5)의 표준수선주기(년) 및 수선율(%)  따름
            
            <h4>4.4. 수선 시 투입자재별 운반에 따른 CO₂배출량 산정</h4>           
            <table>
              <tr>
                <th>자재명</th>
                <th>투입물량① (ton)</th>
                <th>수송거리② (㎞)</th>
                <th>수송수단</th>
                <th>연료</th>
                <th><p>운송장비별 이산화탄소배출원단위③<br />
                (단위: ㎏ CO₂/ton․㎞)</p></th>
                <th>CO₂배출량<br />(단위: ㎏ CO₂eq.)
</th>
              </tr>
              <tr>
                <td>시멘트</td>
                <td>70</td>
                <td>60</td>
                <td>벌크 트럭(15ton)</td>
                <td>경유</td>
                <td>2.49E-01</td>
                <td>69.72</td>
              </tr>
              <tr>
                <td colspan="6">총 배출량</td>
                <td>69.72</td>
              </tr>
            </table>
            [양식해설]<br />
            ○ 자재별 운반에 따른 CO₂배출량은 ①*②*③의 곱으로   계산한다.

            <h3>5. 폐기 및 재활용 단계</h3>
            
            <h4>5.1. 건축물 해체 부문 CO₂배출량 산정</h4>           
            <table>
              <tr>
                <th rowspan="2">적용</th>
                <th colspan="2" rowspan="2">해체장비 종류</th>
                <th rowspan="2">규격</th>
                <th rowspan="2">작업량</th>
                <th colspan="3">에너지지원</th>
                <th colspan="2">이산화탄소 배출원 단위</th>
                <th rowspan="2"><p align="left">CO₂배출량 </p>
                (단위: ㎏-CO₂eq.)</th>
              </tr>
              <tr>
                <th>연료</th>
                <th>소비량</th>
                <th>단위</th>
                <th>값</th>
                <th>단위</th>
              </tr>
              <tr>
                <td>건설폐기물</td>
                <td>철거</td>
                <td>굴삭기+대형브레이커+압쇄기</td>
                <td>1.0 ㎥</td>
                <td>0.28 hr/㎥</td>
                <td>경유</td>
                <td>442</td>
                <td>kg</td>
                <td>6.82E-02</td>
                <td>㎏-CO₂eq./kg</td>
                <td>30.1</td>
              </tr>
              <tr>
                <td colspan="10">총 배출량</td>
                <td>30.1</td>
              </tr>
            </table>
            <span class="imgtitle">표 15 평가대상 건축물 철거에 투입된 장비의 종류에 따른 CO₂배출원단위</span>
            [양식해설]<br />
            ○ 건축물에 해체공법에  따름
            
            <h4>5.2. 건축폐자재 수송 부문 CO₂배출량 산정</h4>           
            <table>
              <tr>
                <th rowspan="2">수송물</th>
                <th rowspan="2">운송수단</th>
                <th rowspan="2">수송지</th>
                <th rowspan="2">투입물량 (ton)</th>
                <th rowspan="2">수송거리 (㎞)</th>
                <th colspan="3">이산화탄소배출원단위 (환경부 LCI  DB)</th>
                <th rowspan="2">CO₂배출량<br />(단위: ㎏-CO₂eq.)</th>
              </tr>
              <tr>
                <th>값</th>
                <th>단위</th>
                <th>DB</th>
              </tr>
              <tr>
                <td>재활용재</td>
                <td>15톤급 덤프트럭</td>
                <td>재활용 처리장</td>
                <td>14737.16</td>
                <td>30</td>
                <td>2.49E-01</td>
                <td>㎏-CO₂eq./ton・㎞</td>
                <td>트럭</td>
                <td>7339.1</td>
              </tr>
              <tr>
                <td>매립 및 소각재</td>
                <td>15톤급 덤프트럭</td>
                <td>매립 및 소각장</td>
                <td>511.18</td>
                <td>30</td>
                <td>2.49E-01</td>
                <td>㎏-CO₂eq./ton・㎞</td>
                <td>트럭</td>
                <td>254.6</td>
              </tr>
              <tr>
                <td colspan="8">총 배출량</td>
                <td>7,593.7</td>
              </tr>
            </table>
            [양식해설]<br />
            ○ 수송물에 따른 수송부문 CO₂배출량은 투입물량*수송거리*배출원단위 곱으로   계산한다.

            <h4>5.3. 폐기물량 산출 부문 CO₂배출량 산정</h4>           
            <table>
              <tr>
                <th rowspan="2">종류</th>
                <th rowspan="2">분류</th>
                <th colspan="2">처리방법</th>
                <th rowspan="2">폐기물 발생량 (단위: ton)</th>
                <th rowspan="2">발생기여도</th>
                <th colspan="2" rowspan="2">이산화탄소배출원단위 (단위: ㎏-CO₂/㎏)</th>
                <th colspan="2" rowspan="2">CO₂배출량 (단위: ㎏-CO₂eq.)</th>
              </tr>
              <tr>
                <th>구분</th>
                <th>비율(%)</th>
              </tr>
              <tr>
                <td rowspan="3">폐콘크리트</td>
                <td rowspan="3">불연성</td>
                <td>재활용 과정</td>
                <td>100</td>
                <td>10644.4</td>
                <td rowspan="3">69.81%</td>
                <td>재활용 과정</td>
                <td>1.38E-02</td>
                <td colspan="2">146,892.7</td>
              </tr>
              <tr>
                <td>매립 과정</td>
                <td>0</td>
                <td>0</td>
                <td>매립 과정</td>
                <td>-</td>
                <td colspan="2">0</td>
              </tr>
              <tr>
                <td>소각 과정</td>
                <td>0</td>
                <td>0</td>
                <td>소각 과정</td>
                <td>-</td>
                <td colspan="2">0</td>
              </tr>
              <tr>
                <td rowspan="3">폐토석 (잡석,  모래 등)</td>
                <td rowspan="3">불연성</td>
                <td>재활용 과정</td>
                <td>92</td>
                <td>620.26</td>
                <td rowspan="3">4.42%</td>
                <td>재활용 과정</td>
                <td>1.36E-02</td>
                <td colspan="2">8,435.5</td>
              </tr>
              <tr>
                <td>매립 과정</td>
                <td>7.9</td>
                <td>53.26</td>
                <td>매립 과정</td>
                <td>6.07E-02</td>
                <td colspan="2">3,232.9</td>
              </tr>
              <tr>
                <td>소각 과정</td>
                <td>0</td>
                <td>0</td>
                <td>소각 과정</td>
                <td>-</td>
                <td colspan="2">0</td>
              </tr>
              <tr>
                <td rowspan="3">폐벽돌 (콘크리트벽돌)</td>
                <td rowspan="3">불연성</td>
                <td>재활용 과정</td>
                <td>99.9</td>
                <td>2909.01</td>
                <td rowspan="3">21%</td>
                <td>재활용 과정</td>
                <td>1.38E-02</td>
                <td colspan="2">40,144.4</td>
              </tr>
              <tr>
                <td>매립 과정</td>
                <td>0.1</td>
                <td>293.84</td>
                <td>매립 과정</td>
                <td>7.03E-03</td>
                <td colspan="2">2,065.7</td>
              </tr>
              <tr>
                <td>소각 과정</td>
                <td>0</td>
                <td>0</td>
                <td>소각 과정</td>
                <td>-</td>
                <td colspan="2">0</td>
              </tr>
              <tr>
                <td rowspan="3">폐금속 (철근)</td>
                <td rowspan="3">불연성</td>
                <td>재활용 과정</td>
                <td>98.5</td>
                <td>563.47</td>
                <td rowspan="3">3.75%</td>
                <td>재활용 과정</td>
                <td class="tdst2">3.80E-03</td>
                <td colspan="2" class="tdst2">2,1411.1</td>
              </tr>
              <tr>
                <td>매립 과정</td>
                <td>1.5</td>
                <td>8.58</td>
                <td>매립 과정</td>
                <td class="tdst2">7.03E-03</td>
                <td colspan="2" class="tdst2">60.3</td>
              </tr>
              <tr>
                <td>소각 과정</td>
                <td>0</td>
                <td>0</td>
                <td>소각 과정</td>
                <td>-</td>
                <td colspan="2">0</td>
              </tr>
              <tr>
                <td rowspan="3">혼합 건설폐기물 (시멘트)</td>
                <td rowspan="3">기타</td>
                <td>재활용 과정</td>
                <td>0</td>
                <td>0</td>
                <td rowspan="3">1.02%</td>
                <td>재활용 과정</td>
                <td>-</td>
                <td colspan="2">0</td>
              </tr>
              <tr>
                <td>매립 과정</td>
                <td>100</td>
                <td>155.5</td>
                <td>매립 과정</td>
                <td class="tdst2">7.98E-02</td>
                <td colspan="2" class="tdst2">12408.91</td>
              </tr>
              <tr>
                <td>소각 과정</td>
                <td>0</td>
                <td>0</td>
                <td>소각 과정</td>
                <td>-</td>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="8" rowspan="3">총 배출량</td>
                <td>재활용</td>
                <td>197,608</td>
              </tr>
              <tr>
                <td>매립</td>
                <td>19,152</td>
              </tr>
              <tr>
                <td>소각</td>
                <td>0</td>
              </tr>
            </table>
            [양식해설]<br />
            ○ 2005년도 건설폐기물 재활용 통계조사보고서(환경부)에 따름
          </td>
        </tr>
        
        <tr>        
          <th class="theader" colspan="2">Ⅲ. 건물의 전생애주기 환경부하량 평가 결과</th>
        </tr>
        <tr>        
          <td colspan="2">
            <h3>1. 전과정 온실가스 배출량 및 기여도 산정 (평가 기간 : 30년)</h3>
            <table>
              <tr>
                <th>구분</th>
                <th>발생량</th>
                <th>%</th>
                <th>세분</th>
                <th>사례 평균치</th>
                <th>단위</th>
                <th>발생기여도</th>
              </tr>
              <tr>
                <td rowspan="2">자재생산 단계</td>
                <td rowspan="2">2,335,919.50</td>
                <td rowspan="2">9.29</td>
                <td>자재 생산</td>
                <td>2,335,919.50</td>
                <td>㎏-CO₂eq.</td>
                <td>9.84%</td>
              </tr>
              <tr>
                <td class="tdst1">단위면적당</td>
                <td class="tdst1">370.13</td>
                <td class="tdst1">㎏-CO₂eq./㎡</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td rowspan="3">시공 단계</td>
                <td rowspan="3">19,113.80</td>
                <td rowspan="3">0.89</td>
                <td>자재 운송</td>
                <td>18905.77</td>
                <td>㎏-CO₂eq.</td>
                <td>0.08%</td>
              </tr>
              <tr>
                <td>건축물 시공</td>
                <td>208.03</td>
                <td>㎏-CO₂eq.</td>
                <td>0.01%</td>
              </tr>
              <tr>
                <td class="tdst1">단위면적당</td>
                <td class="tdst1">3.02</td>
                <td class="tdst1">㎏-CO₂eq./㎡</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td rowspan="4">사용 및 유지보수  단계</td>
                <td rowspan="4">21,159,991.66</td>
                <td rowspan="4">88.94</td>
                <td>전력사용</td>
                <td>21,093,827.78</td>
                <td>㎏-CO₂eq.</td>
                <td>88.86%</td>
              </tr>
              <tr>
                <td>난방에너지 사용</td>
                <td>0</td>
                <td>㎏-CO₂eq.</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>유지보수</td>
                <td>66163.88</td>
                <td>㎏-CO₂eq.</td>
                <td>0.28%</td>
              </tr>
              <tr>
                <td class="tdst1">단위면적당</td>
                <td class="tdst1">3352.91</td>
                <td class="tdst1">㎏-CO₂eq./㎡</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td rowspan="6">폐기 및 재활용  단계</td>
                <td rowspan="6">224,383.80</td>
                <td rowspan="6">0.88</td>
                <td>건축물 해체</td>
                <td>30.1</td>
                <td>㎏-CO₂eq.</td>
                <td>0.01%</td>
              </tr>
              <tr>
                <td>폐기물 수송</td>
                <td>7,593.7</td>
                <td>㎏-CO₂eq.</td>
                <td>0.03%</td>
              </tr>
              <tr>
                <td>폐기물 재활용</td>
                <td>197,608</td>
                <td>㎏-CO₂eq.</td>
                <td>0.83%</td>
              </tr>
              <tr>
                <td>폐기물 매립</td>
                <td>19,152</td>
                <td>㎏-CO₂eq.</td>
                <td>0.08%</td>
              </tr>
              <tr>
                <td>폐기물 소각</td>
                <td><p>0</p></td>
                <td>㎏-CO₂eq.</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="tdst1">단위면적당</td>
                <td class="tdst1">35.55</td>
                <td class="tdst1">㎏-CO₂eq./㎡</td>
                <td>100</td>
              </tr>
              <tr>
                <td class="tdst1">전생애주기 CO₂발생량</td>
                <td class="tdst1">23,739,408.76</td>
                <td rowspan="2" class="tdst1">100</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td class="tdst1">단위면적당</td>
                <td class="tdst1">3761.64</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
            </table>

            <h3>2. 건물 온실가스 배출량  결과</h3>
            <table>
              <tr>
                <th>건물 전과정 온실가스  배출량</th>
                <th>단위</th>
                <th>자재생산 단계</th>
                <th>시공 단계</th>
                <th>사용 및 유지보수  단계</th>
                <th>폐기 및 재활용  단계</th>
              </tr>
              <tr>
                <td class="tdst1">23,739,408.76</td>
                <td>㎏-CO₂eq</td>
                <td>2,335,919.50</td>
                <td>19,113.80</td>
                <td>21,159,991.66</td>
                <td>224,383.80</td>
              </tr>
            </table>
          </td>
        </tr>
        
        <tr>        
          <th class="theader" colspan="2">Ⅳ. 첨부자료</th>
        </tr>
        <tr>        
          <td colspan="2">
            <h3>1. LCI 데이터베이스  목록</h3>
            <table>
              <tr>
                <th rowspan="2">전과정단계</th>
                <th rowspan="2">공정명</th>
                <th rowspan="2">물질명</th>
                <th colspan="3">LCI 데이터베이스</th>
              </tr>
              <tr>
                <th>명칭</th>
                <th>출처</th>
                <th>배출계수값</th>
              </tr>
              <tr>
                <td rowspan="6">자재생산 단계</td>
                <td rowspan="6">건축자재 생산</td>
                <td>레미콘</td>
                <td>레미콘</td>
                <td>탄소성적표지</td>
                <td>3.46E+02 ㎏-CO₂eq./㎥</td>
              </tr>
              <tr>
                <td>콘크리트벽돌</td>
                <td>콘크리트 벽돌</td>
                <td>국토교통부</td>
                <td>1.23E-01 ㎏-CO₂eq./kg</td>
              </tr>
              <tr>
                <td>철근</td>
                <td>일반이형철근</td>
                <td>국토교통부</td>
                <td>3.85E-01 ㎏-CO₂eq./kg</td>
              </tr>
              <tr>
                <td>모래</td>
                <td>모래</td>
                <td>국토교통부</td>
                <td>3.87E+00  ㎏-CO₂eq./㎥</td>
              </tr>
              <tr>
                <td>시멘트</td>
                <td>포틀랜드시멘트</td>
                <td>탄소성적표지</td>
                <td>9.44E-01 ㎏-CO₂eq./kg</td>
              </tr>
              <tr>
                <td>잡석</td>
                <td>자갈</td>
                <td>국토교통부</td>
                <td>1.13E+01 ㎏-CO₂eq./㎥</td>
              </tr>
              <tr>
                <td rowspan="5">시공 단계</td>
                <td rowspan="3">건축자재 운반</td>
                <td>레미콘 트럭(6㎥)</td>
                <td>트럭</td>
                <td>탄소성적표지</td>
                <td>2.49E-01  ㎏-CO₂eq./ton・km</td>
              </tr>
              <tr>
                <td>카고 8톤</td>
                <td>트럭</td>
                <td>탄소성적표지</td>
                <td>2.49E-01  ㎏-CO₂eq./ton・km</td>
              </tr>
              <tr>
                <td>벌크 트럭(15톤)</td>
                <td>트럭</td>
                <td>탄소성적표지</td>
                <td>2.49E-01  ㎏-CO₂eq./ton・km</td>
              </tr>
              <tr>
                <td rowspan="2">건축물 시공</td>
                <td>콘크리트 펌프차</td>
                <td>경유</td>
                <td>탄소성적표지</td>
                <td>6.82E-02 ㎏-CO₂eq./kg</td>
              </tr>
              <tr>
                <td>CON'T 진동기/ 래머</td>
                <td>전기</td>
                <td>탄소성적표지</td>
                <td>4.95E-01 ㎏-CO₂eq./kwh</td>
              </tr>
              <tr>
                <td>사용 및 유지보수  단계</td>
                <td>건물 사용(에너지)</td>
                <td>전력</td>
                <td>전기</td>
                <td>탄소성적표지</td>
                <td>4.95E-01 ㎏-CO₂eq./kwh</td>
              </tr>
              <tr>
                <td rowspan="2">폐기 및 재활용  단계</td>
                <td>폐자재 운송</td>
                <td>15톤급 덤프트럭</td>
                <td>트럭</td>
                <td>탄소성적표지</td>
                <td>2.49E-01  ㎏-CO₂eq./ton・km</td>
              </tr>
              <tr>
                <td>폐기물 처리</td>
                <td>매립/소각/재활용</td>
                <td>폐기물 처리 방법</td>
                <td>탄소성적표지</td>
                <td>http://www.epd.or.kr/lci/co204.asp</td>
              </tr>
            </table>

            <h3>2. 데이터 수집 근거자료</h3>
            ○ 현재 통상적으로 사용되는 비중값인 콘크리트 비중 2.3, 모래 및 잡석 비중 2.6으로 설정함. 이를 무게단위 환산 시 사용함. (예) 1㎥ = 2300kg, 1㎥ = 2600kg
          </td>
        </tr>
    </table>
</div>
</div>

<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>