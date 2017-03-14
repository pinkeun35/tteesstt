<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
	
<script type="text/javascript">
<!--
$(function() {
	list_reset();
});

function list_reset() {
	$("select[name='lproject'] option").remove();
	$("select[name='lzone'] option").remove();
	$("<option></option>").attr("selected", true).text("::공구선택::").attr("value", "0").appendTo("select[name='lzone']");
	$("select[name='lbuild'] option").remove();
	$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='lbuild']");
	
	load_listproject();
}

function load_listproject() {
	$.post( '/usedata/load_listproject', {})
		.done(function( jdata ) {
			if (jdata.status == "success") {
			//	alert('자료 ' + alert_text + '에 성공하였습니다.');
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						$("<option></option>").attr("selected", false).text(jdata.item[key].prjname).attr("value", jdata.item[key].seq).appendTo("select[name='lproject']");
					});
					
					load_listzone($('#lproject').val(), 'lzone', 'lbuild', '');
					load_changelist();
				}
			}
			else if (data.status == "fail") {
				alert('자료 초기화에 싫패하였습니다.');
			}
  		}, "json");
}

function load_listzone(vVal, vStep2, vStep3, vInit) {
	$.post( '/usedata/load_listzone', { project: vVal })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$("select[name='" + vStep2 + "'] option").remove();
				$("<option></option>").attr("selected", true).text("::공구선택::").attr("value", "0").appendTo("select[name='" + vStep2 + "']");
				$("select[name='" + vStep3 + "'] option").remove();
				$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='" + vStep3 + "']");
	
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						$("<option></option>").attr("selected", false).text(jdata.item[key].zone_name).attr("value", jdata.item[key].seq).appendTo("select[name='" + vStep2 + "']");
					});
					if (vInit != "") {
						$('#'+vStep2).val(vInit);
					}
				}

				if (vStep3 == 'lbuild') {
					load_changelist();
				}
			}
			else if (data.status == "fail") {
				alert('자료 초기화에 싫패하였습니다.');
			}
  		}, "json");
}

function load_listbuild(vVal, vStep1, vStep3, vInit) {
	if (vVal != '') {
		$.post( '/usedata/load_listbuilding', { project: vStep1, zone: vVal })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					$("select[name='" + vStep3 + "'] option").remove();
					$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='" + vStep3 + "']");
		
					if ( jdata.item.length > 0 ) {
						$.each(jdata.item, function(key,state){
							$("<option></option>").attr("selected", false).text(jdata.item[key].bname).attr("value", jdata.item[key].seq).appendTo("select[name='" + vStep3 + "']");
						});
						if (vInit != "") {
							$('#'+vStep3).val(vInit);
						}
					}

					if (vStep3 == 'lbuild') {
						load_changelist();
					}
				}
				else if (data.status == "fail") {
					alert('자료 초기화에 싫패하였습니다.');
				}
	  		}, "json");
	  }
}

function load_changelist() {
	list_reload(1);
	list_reload2(1);
}

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/sdata/get_preuse_list", {
			page: vPage,
			gubun: 0,
			project: $('#lproject').val(),
			zone: $('#lzone').val(),
			build: $('#lbuild').val(),
			step1: 0,
			step2: 0,
			step3: 0,
			recycle: 'yes'
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$('#area_nowpage').text(jdata.now_page);
				$('#area_totalpage').text(jdata.page_total);
				$("#area_item tr:not(:first)").remove();

				var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);
				
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td class="btn_diapup127"><a href="javascript:get_record(' + jdata.item[key].seq + ');">' + jdata.item[key].jname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].jstandard + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jvolume + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].post_use + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].wdate + '</td>';
						tr_html += '</tr>';
						
						$("#area_item").append( tr_html );
						
						line_num--;
					});
				
					page_navigation(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="6">데이터가 없습니다.</td>';
					tr_html += '</tr>';
					
					$("#area_item").append( tr_html );
				}
			}
			else if (jdata.status == "fail") {
				alert('자료 목록 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function list_reload2(vPage) {
	$('#now_page2').val(vPage);
	$.post( "/sdata/get_preuse_list", {
			page: vPage,
			gubun: 0,
			project: $('#lproject').val(),
			zone: $('#lzone').val(),
			build: $('#lbuild').val(),
			step1: 0,
			step2: 0,
			step3: 0,
			recycle: 'no'
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$('#area_nowpage2').text(jdata.now_page);
				$('#area_totalpage2').text(jdata.page_total);
				$("#area_item2 tr:not(:first)").remove();

				var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);
				
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td class="btn_diapup127"><a href="javascript:get_record(' + jdata.item[key].seq + ');">' + jdata.item[key].jname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].jstandard + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jvolume + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].post_use + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].wdate + '</td>';
						tr_html += '</tr>';
						
						$("#area_item2").append( tr_html );
						
						line_num--;
					});
				
					page_navigation2(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="6">데이터가 없습니다.</td>';
					tr_html += '</tr>';
					
					$("#area_item2").append( tr_html );
				}
			}
			else if (jdata.status == "fail") {
				alert('자료 목록 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function get_record(vRecord) {
	$('#sseq').val(vRecord);
	$.post( "/sdata/get_preuse_record", { record: vRecord })
		.done(function( jdata ) {
			$( "#diapup122" ).dialog( "close" );

			if (jdata.status == "success") {
				if (jdata.gubun == "0") {
					init_pop_infojajae(jdata.preuse, jdata.info, jdata.occurrence);
				} else if (jdata.gubun == "1") {
					init_pop_infomachine(jdata.preuse, jdata.info, jdata.occurrence);
				}
			}
			else if (jdata.status == "fail") {
				alert('Data 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function init_pop_infojajae(jpreuse, jinfo, joccurrence) {
	$('#info_title_area1').html('자재사용정보');
	$('#info_title_area2').html('자재기준정보');

	$('#jarea_01').html(jpreuse.jname);
	$('#jarea_02').html(jpreuse.jstandard);
	$('#jarea_03').html(jpreuse.jvolume);
	$('#jarea_04').html(jpreuse.junit);
	$('#jarea_05').html(jpreuse.weight);
	
	$('#jarea_07').html(jinfo.junit);
	$('#jarea_08').html(jinfo.jweight);
	$('#jarea_09').html(jinfo.jjugi);
	$('#jarea_10').html(jinfo.jsuseon);
	if (jinfo.jrecycle == "0")
		$('#jarea_11').html("폐기");
	else if (jinfo.jrecycle == "1")
		$('#jarea_11').html("재활용");
	$('#jarea_12').html(jinfo.jculceo);

	init_pop_maketable(jpreuse, jinfo, joccurrence);

	$('#info_table_j1').show();
	$('#info_table_j2').show();
	$('#info_table_m1').hide();
	$('#info_table_m2').hide();
	
	change_tab(3);

	$( "#diapup127" ).dialog( "open" );
}

function init_pop_infomachine(jpreuse, jinfo, joccurrence) {
	$('#info_title_area1').html('장비사용정보');
	$('#info_title_area2').html('장비기준정보');

	$('#marea_01').html(jpreuse.jname);
	$('#marea_02').html(jpreuse.jstandard);
	$('#marea_03').html(jpreuse.jvolume);
	$('#marea_04').html(jpreuse.junit);
	$('#marea_05').html(jpreuse.weight);

	$('#marea_07').html(jinfo.munit);
	$('#marea_08').html(jinfo.mileage);
	$('#marea_09').html(jinfo.mculceo);

	init_pop_maketable(jpreuse, jinfo, joccurrence);

	$('#info_table_j1').hide();
	$('#info_table_j2').hide();
	$('#info_table_m1').show();
	$('#info_table_m2').show();
	
	change_tab(3);
	
	$( "#diapup127" ).dialog( "open" );
}

function init_pop_maketable(jpreuse, jinfo, joccurrence) {

	$("#area_table1 tr:not(:first)").remove();
	var tr_html = '';
	tr_html += '<tr>';
	tr_html += '<td class="c">' + jpreuse.weight + '</td>';
	tr_html += '<td class="c">' + jpreuse.pre_use + '</td>';
	tr_html += '</tr>';
	$("#area_table1").append( tr_html );

	$("#area_table2 tr:not(:first)").remove();
	var tr_html = '';
	if ( joccurrence.length > 0 ) {
		$.each(joccurrence, function(key,state){
			if ((key + 1) < joccurrence.length) {
				tr_html += '<tr>';
				tr_html += '<td class="c">' + joccurrence[key].byear + '년 ' + joccurrence[key].bmonth + '월</td>';
				tr_html += '<td class="c">' + joccurrence[key].pre_use + '</td>';
				tr_html += '</tr>';
			}
		});
	} else {
		tr_html += '<tr>';
		tr_html += '<td class="c" colspan="2">데이터가 없습니다.</td>';
		tr_html += '</tr>';
	}
	$("#area_table2").append( tr_html );

	$("#area_table3 tr:not(:first)").remove();
	var tr_html = '';
	tr_html += '<tr>';
	tr_html += '<td class="c">' + jpreuse.post_use + '</td>';
	tr_html += '</tr>';
	$("#area_table3").append( tr_html );

}

function change_tab(vIdx) {
	$('#area_tab_all').removeClass("datalisttapmenu1");
	$('#area_tab_all').removeClass("datalisttapmenu2");
	$('#area_tab_all').removeClass("datalisttapmenu3");
	
	$('#area_tab1').removeClass("strong");
	$('#area_tab2').removeClass("strong");
	$('#area_tab3').removeClass("strong");
	
	$('#area_table1').hide();
	$('#area_table2').hide();
	$('#area_table3').hide();
	
	$('#area_tab_all').addClass("datalisttapmenu"+vIdx);
	$('#area_tab'+vIdx).addClass("strong");
	$('#area_table'+vIdx).show();
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_2_3"><img src="/app/views/images//common/img_subtitle2_3.gif" alt="USE DATA" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images//common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">USE DATA</a> <span>&gt;</span> <strong>자료관리</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
        	<p><select id="lproject" name="lproject" onchange="load_listzone(this.value, 'lzone', 'lbuild', '');">
            	<option>프로젝트 선택</option>
            </select>
            
            <select id="lzone" name="lzone" onchange="load_listbuild(this.value, $('#lproject').val(), 'lbuild', '');">
            	<option>공구 선택</option>
            </select>
            <select id="lbuild" name="lbuild" onchange="load_changelist();">
            	<option>건축물 선택</option>
            </select></p>
            
            <dl id="datalisttapmenu" class="datalisttapmenu3">
            	<dd class="menu1"><a href="/sdata/preuse_list">Pre-use</a></dd>
                <dd class="menu2"><a href="/sdata/use_list">Use</a></dd>
                <dd class="menu3"><a href="/sdata/postuse_list" class="strong">Post-use</a></dd>
            </dl>

            <p class="bbstitle2">재활용 자재정보</p>            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbslistbox1"><input type="hidden" id="now_page" value="1" />
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                            <col width="7%" />
                            <col width="*" />
                            <col width="13%" />
                            <col width="10%" />
                            <col width="10%" />
                            <col width="10%" />
                        </colgroup>
                        <tr>
                            <th class="tl">No.</th>
                            <th>자재명</th>
                            <th>자재규격</th>
                            <th>수량</th>
                            <th>Co2 배출량</th>
                            <th class="tr">등록일</th>
                        </tr>
                    </table>
                <dd class="bbsbtnbox1">
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
        
            
            <p class="bbstitle2">폐기 자재정보</p>            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage2">1</strong>/<span id="area_totalpage2">1</span></dd>
                <dd><input type="hidden" id="now_page2" value="1" />
                    <table id="area_item2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                            <col width="7%" />
                            <col width="*" />
                            <col width="13%" />
                            <col width="10%" />
                            <col width="10%" />
                            <col width="10%" />
                        </colgroup>
                        <tr>
                            <th class="tl">No.</th>
                            <th>자재명</th>
                            <th>자재규격</th>
                            <th>수량</th>
                            <th>Co2 배출량</th>
                            <th class="tr">등록일</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="pageing" id="area_paging2"></div>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype3"><button class="st1">Co2 계산</button></div>
                </dd>
            </dl>

        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup127">
<div class="contentswrap">
    <dl class="tablebox1">
        <dd>
	        <p id="info_title_area1" class="bbstitle1">자재사용정보</p>
            <table id="info_table_j1" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="20%" />
                <col width="35%" />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
                </colgroup>
                <tr>
                    <th class="tl">자재명</th>
                    <th>자재규격</th>
                    <th>수량</th>
                    <th>단위</th>
                    <th class="tr">무게</th>
                </tr>
                <tr class="mobg">
                	<td class="c"><span id="jarea_01"></span></td>
                	<td class="c"><span id="jarea_02"></span></td>
                	<td class="c"><span id="jarea_03"></span></td>
                	<td class="c"><span id="jarea_04"></span></td>
                	<td class="c"><span id="jarea_05"></span></td>
                </tr>
            </table>
            <table id="info_table_m1" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="20%" />
                <col width="35%" />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
                </colgroup>
                <tr>
                    <th class="tl">장비명</th>
                    <th>장비규격</th>
                    <th>사용시간</th>
                    <th>단위</th>
                    <th class="tr">Energy 사용량</th>
                </tr>
                <tr class="mobg">
                	<td class="c"><span id="marea_01"></span></td>
                	<td class="c"><span id="marea_02"></span></td>
                	<td class="c"><span id="marea_03"></span></td>
                	<td class="c"><span id="marea_04"></span></td>
                	<td class="c"><span id="marea_05"></span></td>
                </tr>
            </table>
            <br />
            <p id="info_title_area2" class="bbstitle1">자재기준정보</p>
            <table id="info_table_j2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">배출기준</th>
                    <th>기준단위</th>
                    <th>무게기준</th>
                    <th>수선주기</th>
                    <th>수선율</th>
                    <th>폐기/재활용</th>
                    <th class="tr">출처</th>
                </tr>
                <tr class="mobg">
                	<td class="c"><span id="jarea_06"></span></td>
                	<td class="c"><span id="jarea_07"></span></td>
                	<td class="c"><span id="jarea_08"></span></td>
                	<td class="c"><span id="jarea_09"></span></td>
                	<td class="c"><span id="jarea_10"></span></td>
                	<td class="c"><span id="jarea_11"></span></td>
                	<td class="c"><span id="jarea_12"></span></td>
                </tr>
            </table>
            <table id="info_table_m2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">배출기준</th>
                    <th>기준단위</th>
                    <th>연비</th>
                    <th class="tr">출처</th>
                </tr>
                <tr class="mobg">
                	<td class="c"><span id="marea_06"></span></td>
                	<td class="c"><span id="marea_07"></span></td>
                	<td class="c"><span id="marea_08"></span></td>
                	<td class="c"><span id="marea_09"></span></td>
                </tr>
            </table>
        </dd>
        <dd>    
            <p class="bbstitle1">배출량정보</p>
            <dl id="area_tab_all" class="datalisttapmenu1">
                <dd class="menu1"><a href="#;" id="area_tab1" class="strong" onfocus="this.blur()" onclick="change_tab(1);">Pre-use</a></dd>
                <dd class="menu2"><a href="#;" id="area_tab2" onclick="change_tab(2);">유지보수</a></dd>
                <dd class="menu3"><a href="#;" id="area_tab3" onclick="change_tab(3);">Post-use</a></dd>
            </dl>
            <table id="area_table1" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">무게</th>
                    <th class="tr">Co2배출량</th>
                </tr>
                <tr>
                	<td class="c">1</td>
                	<td class="c">1000</td>
                </tr>
            </table>
            <table id="area_table2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">예상시기</th>
                    <th class="tr">예상배출량</th>
                </tr>
                <tr>
                	<td class="c">2104-08-11</td>
                	<td class="c">1000</td>
                </tr>
            </table>
            <table id="area_table3" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>배출량</th>
                </tr>
                <tr>
                	<td class="c">999</td>
                </tr>
            </table>

        </dd>

        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" id="cbtn_diapup127">닫기</button> <button type="button" id="mbtn_diapup127" class="st3">수정</button> <button type="button" class="st1" id="rbtn_diapup127">재계산</button> <button type="button" class="st5" id="dbtn_diapup127">삭제</button></div>
        </dd>
    </dl>
</div>
</div>

<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>