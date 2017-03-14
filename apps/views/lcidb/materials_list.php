<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>

<script type="text/javascript">
<!--
$(function() {
	list_reload(1);
});

function check_search() {
	$('#skeyword').val($('#stext').val());

	list_reload(1);
}

function hv_item(itemdata) {
	if ($('#items_d'+itemdata).css('display') == 'none') {
		$('#items_d'+itemdata).css("display", "block");
	} else {
		$('#items_d'+itemdata).css("display", "none");
	}
}

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/lcidb/get_materials_list", { page: vPage, skeyword: $('#skeyword').val() })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$('#area_nowpage').text(jdata.now_page);
				$('#area_totalpage').text(jdata.page_total);
				$("#area_item tr:not(:first)").remove();

				var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);

				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						tr_html += '<tr class="mobg" onclick="hv_item(' + jdata.item[key].seq + ');">';
						tr_html += '<td class="c"><input type="checkbox" name="cbxseq" disabled="disabled" /></td>';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td>' + jdata.item[key].jname + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jstandard + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].junit + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jculceo + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jjugi + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jsuseon + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jco2 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jweight + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jrecycle + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].wdate.substring(0, 10) + '</td>';
						tr_html += '</tr>';
						tr_html += '<tr id="items_d' + jdata.item[key].seq + '" class="mobg" style="display:none;">';
						tr_html += '<td class="c" colspan="12">';
						tr_html += '<table width="80" align="center" border="0" cellpadding="0" cellspacing="0">';
						tr_html += '<tr>';
						tr_html += '<td>자원소모<br/>(KgSb)</td>';
						tr_html += '<td>지구온난화<br/>(Kgco2)</td>';
						tr_html += '<td>오존층영향<br/>(KgCFC11)</td>';
						tr_html += '<td>산성화<br/>(KgSO2)</td>';
						tr_html += '<td>부영양화<br/>(KgPO4 3-)</td>';
						tr_html += '<td>광화학적산화물 생성<br/>(KgC2H4)</td>';
						tr_html += '<td>물발자국<br/>(Kg H2O)</td>';
						tr_html += '</tr>';
						tr_html += '<tr>';
						tr_html += '<td>' + jdata.item[key].jsb + '</td>';
						tr_html += '<td>' + jdata.item[key].jco2 + '</td>';
						tr_html += '<td>' + jdata.item[key].jcfc + '</td>';
						tr_html += '<td>' + jdata.item[key].jso + '</td>';
						tr_html += '<td>' + jdata.item[key].jpo + '</td>';
						tr_html += '<td>' + jdata.item[key].jhch + '</td>';
						tr_html += '<td>' + jdata.item[key].jho + '</td>';
						tr_html += '</tr>';
						tr_html += '</table>';
						tr_html += '</td>';
						tr_html += '</tr>';

						$("#area_item").append( tr_html );

						line_num--;
					});

					$('#stext').val($('#skeyword').val());

					page_navigation(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="12">데이터가 없습니다.</td>';
					tr_html += '</tr>';

					$("#area_item").append( tr_html );
				}
			}
			else if (jdata.status == "fail") {
				alert('자료 목록 읽기에 싫패하였습니다.');
			}
  		}, "json");
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_3_1"><img src="/app/views/images/common/img_subtitle3_1.gif" alt="LCI DB" /></span></h2>

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">LCI DB</a> <span>&gt;</span> <strong>건설자재</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">

            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbssearchbox1"><input type="hidden" id="now_page" value="1" /><input type="hidden" id="skeyword" value="" />
                    <fieldset>
                    <legend>검색</legend>
                    <input name="stext" id="stext" class="bbsinput1" placeholder="검색어를 입력하세요" />
                    <input type="image" src="/app/views/images/common/icon_zoom1.gif" class="sbtn" title="검색" onclick="check_search();" />
                    </fieldset>
                </dd>
                <dd class="bbslistbox1">
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th class="tl"><input type="checkbox" id="all_record_select" disabled="disabled" /></th>
                            <th>No.
                            <th>자재명</th>
                            <th>자재규격</th>
                            <th>단위</th>
                            <th>출처</th>
                            <th>수선주기</th>
                            <th>수선율(%)</th>
                            <th>Co2배출량(KgCO2)</th>
                            <th>무게기준(Kg)</th>
                            <th><a href="#;">폐기▽</a> | <a href="#;">재활용▽</a></th>
                            <th class="tr">등록일(수정일)</th>
                        </tr>
                        <!-- <tr class="mobg">
                            <td class="c"><input type="checkbox" /></td>
                            <td class="c"><%=i%></td>
                            <td class="c">콘크리트</td>
                            <td class="c">200-200-15</td>
                            <td class="c">㎏</td>
                            <td class="c">지식경제부</td>
                            <td class="c">3</td>
                            <td class="c">30%</td>
                            <td class="c">100</td>
                            <td class="c">1</td>
                            <td class="c">폐기</td>
                            <td class="c">2015-06-30</td>
                        </tr> -->
                    </table>
                </dd>

                <dd class="bbsbtnbox1">
                    <div class="pageing" id="area_paging"></div>
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
