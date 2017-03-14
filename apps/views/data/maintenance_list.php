<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
	
<script type="text/javascript">
<!--
$(function() {
	$(document).on("keyup", "input:text[numberdecimalOnly]", function() {$(this).val( $(this).val().replace(/[^0-9.]/gi,"") );});
	
	list_reset();
	
	$("#btn_deleteitem").click(function() {
		var selected_item = '';
		$("input[name=cbxseq]:checked").each(function() {
			if (selected_item == '') {
				selected_item = $(this).val();
			} else {
				selected_item += ',' + $(this).val();
			}
		});
		if (selected_item == "") {
			alert('선택된 항목이 존재하지 않습니다.\n\n다시 확인하여 주십시오.');
		} else {
			delete_record(selected_item);
		}
	});
});

function list_reset() {
	$("select[name='lproject'] option").remove();
	$("select[name='lzone'] option").remove();
	$("<option></option>").attr("selected", true).text("::공구선택::").attr("value", "0").appendTo("select[name='lzone']");
	$("select[name='lbuild'] option").remove();
	$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='lbuild']");
	
	$("select[name='pproject'] option").remove();
	$("select[name='pzone'] option").remove();
	$("<option></option>").attr("selected", true).text("::공구선택::").attr("value", "0").appendTo("select[name='pzone']");
	$("select[name='pbuild'] option").remove();
	$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='pbuild']");
	
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
						$("<option></option>").attr("selected", false).text(jdata.item[key].prjname).attr("value", jdata.item[key].seq).appendTo("select[name='pproject']");
					});
					
					load_listzone($('#lproject').val(), 'lzone', 'lbuild', '');
					load_listzone($('#pproject').val(), 'pzone', 'pbuild', '');
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
	$.post( "/sdata/get_occurrence_uselist", {
			page: vPage,
			project: $('#lproject').val(),
			zone: $('#lzone').val(),
			build: $('#lbuild').val()
			
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$('#area_nowpage').text(jdata.now_page);
				$('#area_totalpage').text(jdata.page_total);
				$("#area_item tr:not(:first)").remove();

				var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);
				
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var temp_month = '0'+jdata.item[key].bmonth;
						var tr_html = "";
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td class="c"><a href="javascript:get_record(' + jdata.item[key].seq + ');">' + jdata.item[key].byear + '-' + temp_month.substring(temp_month.length, (temp_month.length - 2)) + '</a></td>';
						tr_html += '<td class="btn_diapup127"><a href="javascript:get_record(' + jdata.item[key].seq + ');">' + jdata.item[key].jname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].jstandard + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jco2 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].junit + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].pre_use + '</td>';
						tr_html += '</tr>';
						
						$("#area_item").append( tr_html );
						
						line_num--;
					});
				
					page_navigation(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="8">데이터가 없습니다.</td>';
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
	var select_year1 = $('#lyear1').val();
	var select_month1 = $('#lmonth1').val();
	var select_year2 = $('#lyear2').val();
	var select_month2 = $('#lmonth2').val();
	
	if (select_year1 == "0" && select_month1 != "0") {
		select_month1 = "0";
	}
	if (select_year2 == "0" && select_month2 != "0") {
		select_month2 = "0";
	}

	$('#umseq').val('');
	$('#now_page2').val(vPage);
	$.post( "/sdata/get_maintenance_list", {
			page: vPage,
			gubun: 0,
			project: $('#lproject').val(),
			zone: $('#lzone').val(),
			build: $('#lbuild').val(),
			year1: select_year1,
			month1: select_month1,
			year2: select_year2,
			month2: select_month2
			
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$('#area_nowpage2').text(jdata.now_page);
				$('#area_totalpage2').text(jdata.page_total);
				$("#area_item2 tr:not(:first)").remove();

				var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);
				
				if ( jdata.item.length > 0 ) {
					$("select[name='lyear1'] option").remove();
					$("<option></option>").attr("selected", false).text(":년도:").attr("value", "0").appendTo("select[name='lyear1']");
					$("select[name='lyear2'] option").remove();
					$("<option></option>").attr("selected", false).text(":년도:").attr("value", "0").appendTo("select[name='lyear2']");
					
					for (var i = jdata.year_min; i<=jdata.year_max; i++) {
						$("<option></option>").attr("selected", false).text(i).attr("value", i).appendTo("select[name='lyear1']");
						$("<option></option>").attr("selected", false).text(i).attr("value", i).appendTo("select[name='lyear2']");
					}
					
					$('#lyear1').val(select_year1);
					$('#lmonth1').val(select_month1);
					$('#lyear2').val(select_year2);
					$('#lmonth2').val(select_month2);

					$.each(jdata.item, function(key,state){
						var temp_month = '0'+jdata.item[key].umonth;
						var tr_html = "";
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c"><input type="checkbox" name="cbxseq" value="' + jdata.item[key].seq + '" /></td>';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td class="c"><a href="javascript:get_record(' + jdata.item[key].seq + ');">' + jdata.item[key].uyear + '-' + temp_month.substring(temp_month.length, (temp_month.length - 2)) + '</a></td>';
						tr_html += '<td class="btn_diapup127"><a href="javascript:get_record(' + jdata.item[key].seq + ');">' + jdata.item[key].jname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].jstandard + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jvolume + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].weight + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].duse + '</td>';
						tr_html += '</tr>';
						
						$("#area_item2").append( tr_html );
						
						line_num--;
					});
				
					page_navigation2(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="8">데이터가 없습니다.</td>';
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
	$('#umseq').val(vRecord);
	$.post( "/sdata/get_maintenance_record", { record: vRecord })
		.done(function( jdata ) {
			$( "#diapup122" ).dialog( "close" );

			if (jdata.status == "success") {
				pop_modify_maintenance(jdata.item);
			}
			else if (jdata.status == "fail") {
				alert('Data 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function pop_lcidb() {
	var vGubun =  $('#lcidb_gubun').val();
	
	if (vGubun == 'jajae') {
		$('#stext').attr("placeholder", "LCI DB 품목명을 입력하세요.");
		$('#parea_tit1').html('재료명');
		$('#parea_tit2').html('규격');
		$('#parea_tit3').html('Co2배출량');
		$('#parea_tit4').html('무게');
	} else if (vGubun == 'machine') {
		$('#stext').attr("placeholder", "LCI DB 장비명을 입력하세요.");
		$('#parea_tit1').html('장비명');
		$('#parea_tit2').html('규격');
		$('#parea_tit3').html('Co2배출량');
		$('#parea_tit4').html('연비');
	}
	$('#stext').val('');
	$("#search_area tr:not(:first)").remove();
	$( "#diapup137" ).dialog( "open" );
}

function search_lcidb() {
	if ($('#stext').val() == "") {
		alert('검색어를 입력하여 주십시오.');
		$('#stext').focus();
		return;
	}
	
	var run_url = "/admin/get_lcijajae_search";
	if ($('#lcidb_gubun').val() != 'jajae') {
		run_url = "/admin/get_lcimachine_search";
	}
	$.post( run_url, { search: $('#stext').val() })
		.done(function( jdata ) {
			if (jdata.status == "success") {
			//	alert('자료 ' + alert_text + '에 성공하였습니다.');
				$("#search_area tr:not(:first)").remove();
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c">' + jdata.item[key].vname + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].vstandard + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].vco2 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].vweight + '</td>';
						tr_html += '<td class="c"><button class="st1" onclick="search_select(' + jdata.item[key].seq + ',\'' + jdata.item[key].vname + '\',\'' + jdata.item[key].vstandard + '\',\'' + jdata.item[key].vunit + '\');">선택</button></td>';
						tr_html += '</tr>';
						
						$("#search_area").append( tr_html );
					});
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="5">데이터가 없습니다.</td>';
					tr_html += '</tr>';
					
					$("#search_area").append( tr_html );
				}
			}
			else if (data.status == "fail") {
				alert('자료 검색에 싫패하였습니다.');
			}
  		}, "json");
}

function search_select(vSequence, vName, vStandard, vUnit) {
	$('#jseq').val(vSequence);
	$('#jname').val(vName);
	$('#jstandard').val(vStandard);
	$('#junit').val(vUnit);
	
	$( "#diapup137" ).dialog( "close" );
	
	lcidb_jajae_readonly(true);
	
	$('#jvolume').focus();
}

function lcidb_jajae_readonly(vFlag) {
	if (vFlag == true) {
		$('#jname').prop('readonly', true);
		$('#jstandard').prop('readonly', true);
	} else if (vFlag == false) {
		$('#jseq').val('');
		$('#jname').val('');
		$('#jstandard').val('');
		$('#junit').val('');
		
		$('#jname').prop('readonly', false);
		$('#jstandard').prop('readonly', false);
	}
}

function pop_init_maintenance() {
	$('#uyear').val("<?php echo ''.date('Y'); ?>");
	$('#umonth').val("<?php echo ''.(int)date('m'); ?>");
	lcidb_jajae_readonly(false);
	$("#diapup183").dialog({
        title: '유지보수 수선정보 직접입력'
    });

	$( "#diapup183" ).dialog( "open" );
}

function pop_modify_maintenance(jdata) {
	$('#pproject').val(jdata.project_seq);
	load_listzone(jdata.project_seq, 'pzone', 'pbuild', jdata.zone_seq);
	load_listbuild(jdata.zone_seq, jdata.project_seq, 'pbuild', jdata.build_seq);

	$('#uyear').val(jdata.uyear);
	$('#umonth').val(jdata.umonth);
	if (jdata.jajae_seq == "") {
		$('#jseq').val('');
	} else {
		$('#jseq').val(jdata.jajae_seq);
		lcidb_jajae_readonly(true);
	}
	$('#jname').val(jdata.jname);
	$('#jstandard').val(jdata.jstandard);
	$('#jvolume').val(jdata.jvolume);
	$('#junit').val(jdata.junit);
	if (jdata.juseyn == "0") {
		$('input:radio[name=juseyn]').filter('[value=N]').prop('checked', true);
	} else {
		$('input:radio[name=juseyn]').filter('[value=Y]').prop('checked', true);
	}
	$("#diapup183").dialog({
        title: '유지보수 수선정보 수정'
    });

	$( "#diapup183" ).dialog( "open" );
}

function maintenance_check() {
	if ($('#pzone').val() == "0") {
		alert('공구를 선택하여 주십시오.');
		$('#pzone').focus();
		return;
	}
	if ($('#pbuild').val() == "0") {
		alert('건물을 선택하여 주십시오.');
		$('#pzone').focus();
		return;
	}
	
	if ($('#jname').val() == "") {
		alert('자재명을 입력하여 주십시오.');
		$('#jname').focus();
		return;
	}
	if ($('#jstandard').val() == "") {
		alert('자재규격을 입력하여 주십시오.');
		$('#jstandard').focus();
		return;
	}
	if ($('#jvolume').val() == "") {
		alert('자재수량을 입력하여 주십시오.');
		$('#jvolume').focus();
		return;
	}
	if ($('#junit').val() == "") {
		alert('단위를 입력하여 주십시오.');
		$('#junit').focus();
		return;
	}
	var juseyn = "0";
	if ($('#juseyn1').is(':checked') == true) {
		juseyn = "1";
	}
	
	var action_url = '/sdata/insert_maintenance';
	$.post( action_url, {
			uyear: $('#uyear').val(),
			umonth: $('#umonth').val(),
			jajae: $('#jseq').val(),
			jname: $('#jname').val(),
			jstandard: $('#jstandard').val(),
			jvolume: $('#jvolume').val(),
			junit: $('#junit').val(),
			juseyn: juseyn,
			lproject: $('#pproject').val(),
			lzone: $('#pzone').val(),
			lbuild: $('#pbuild').val(),
			umseq: $('#umseq').val()
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$( "#diapup137" ).dialog( "close" );
				$( "#diapup183" ).dialog( "close" );
				
				if ($('#umseq').val() == '') {
					list_reload2(1);
				} else {
					list_reload2($('#now_page2').val());
				}
			}
			else if (data.status == "fail") {
				alert('자료 저장에 싫패하였습니다.');
			}
  		}, "json");
}

function checkbox_checkall() {
	var check_status = true;
	if ($('#all_record_select').is(':checked') == false) {
		check_status = false;
	}
	$("input[name=cbxseq]:checkbox").each(function() {
		$(this).attr("checked", check_status);
	});
}

function delete_record(vRecords) {
	if (confirm('선택된 내역을 삭제하시겠습니까?')) {
		$.post( "/sdata/delete_maintenance", { record: vRecords })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					list_reload2($('#now_page2').val());
				}
				else if (jdata.status == "fail") {
					alert('Data 읽기에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function subpage_change(page) {
	if (page != '')
		document.location = page;
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_2_3"><img src="/app/views/images/common/img_subtitle2_3.gif" alt="USE DATA" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">USE DATA</a> <span>&gt;</span> <strong>자료관리</strong></p></div>

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

            <dl id="datalisttapmenu" class="datalisttapmenu2">
            	<dd class="menu1"><a href="/sdata/preuse_list">Pre-use</a></dd>
                <dd class="menu2"><a href="/sdata/maintenance_list" class="strong">Use</a></dd>
                <dd class="menu3"><a href="/sdata/postuse_list">Post-use</a></dd>
            </dl>
            
            <p class="bbstitle1">유지보수 예정 정보</p>            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbsselectbox1"><input type="hidden" id="now_page" value="1" />
                <form name="" method="" action="">
                    <fieldset>
                        <legend>데이터유형</legend>
                    <select id="subpage" name="subpage" onchange="subpage_change(this.value);"><option value="/sdata/use_list">Energy</option><option value="" selected="selected">유지보수</option></select>
                    </fieldset>
                </form>
                </dd>
                <dd class="bbslistbox1">
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="10%" />
                        <col width="*" />
                        <col width="150" />
                        <col width="200" />
                        <col width="100" />
                        <col width="200" />
                        </colgroup>
                        <tr>
                            <th class="tl">No.</th>
                            <th>예정일</th>
                            <th>자재명</th>
                            <th>규격</th>
                            <th>예정량</th>
                            <th>단위</th>
                            <th class="tr">Co2 배출량</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype2"><button type="button" id="btn_diapup140" class="st1">다시계산</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
        
            
            <p class="bbstitle2">유지보수 수선 정보</p>            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage2">1</strong>/<span id="area_totalpage2">1</span></dd>
                <dd class="bbssearchbox1"><input type="hidden" id="now_page2" value="1" />
                    <fieldset>
                    <legend>검색</legend>
                    <select id="lyear1" name="lyear1" >
                    	<option value="0">:년도:</option>
                    </select> <select id="lmonth1" name="lmonth1" >
                    	<option value="0">:월:</option>
                    	<?php for ($i=1; $i<=12; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; } ?>
                    </select> ~ <select id="lyear2" name="lyear2" >
                    	<option value="0">:년도:</option>
                    </select> <select id="lmonth2" name="lmonth2" >
                    	<option value="0">:월:</option>
                    	<?php for ($i=1; $i<=12; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; } ?>
                    </select>
                    <input type="image" src="/app/views/images//common/icon_zoom1.gif" class="sbtn" title="검색" onclick="list_reload2(1);" />
                    </fieldset>
                </dd>
                <dd>
                    <table id="area_item2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="7%" />
                        <col width="10%" />
                        <col width="*" />
                        <col width="150" />
                        <col width="200" />
                        <col width="100" />
                        <col width="200" />
                        </colgroup>
                        <tr>
                            <th class="tl"><input type="checkbox" id="all_record_select" onclick="checkbox_checkall();" /></th>
                            <th>No.</th>
                            <th>수선일</th>
                            <th>자재명</th>
                            <th>규격</th>
                            <th>수선량</th>
                            <th>단위</th>
                            <th class="tr">Co2 배출량</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" id="btn_deleteitem" class="st5">삭제</button></div>
                    <div class="btntype2"><button type="button" id="btn_diapup183" class="st3" onclick="pop_init_maintenance()">등록</button></div>
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

<div id="diapup181">
	<p>총OO의 유지보수 수선 정보를 삭제 하시겠습니까?<br />
	  삭제 후에는 복구 되지않습니다.<br /><br />
    <button type="button" id="abtn_diapup181" class="st1">삭제하기</button> <button type="button" class="" id="cbtn_diapup181">취소</button></p>
</div>

<div id="diapup182">
	<p>총OO의 유지보수 수선 정보가 모두 삭제 되었습니다.<br /><br /><button type="button" class="" id="cbtn_diapup182">확인</button></p>
</div>

<div id="diapup183">
	<p class="p1"><select id="pproject" name="pproject" onchange="load_listzone(this.value, 'pzone', 'pbuild', '');">
		<option>경기도 시립박물관</option>
	</select> <select id="pzone" name="pzone" onchange="load_listbuild(this.value, $('#pproject').val(), 'pbuild', '');">
		<option>제1공구</option>
	</select> <select id="pbuild" name="pbuild">
		<option>보관함1</option>
	</select></p>

	<p><strong>직접등록</strong> &nbsp; |  &nbsp; <a href="#;" id="obtn_diapup183">Excel등록</a></p>
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>사용일자</th>
                    <td><select id="uyear">
                    	<?php for ($i=2010; $i<=(int)date("Y"); $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; } ?>
                    </select>년 <select id="umonth">
                    	<?php for ($i=1; $i<=12; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; } ?>
                    </select>월</td>
                </tr>
                <tr>
                    <th><span id="jname_area">자재명</span></th>
                    <td><input class="inp2" name="jname" id="jname" maxlength="50" value="" /> <button type="button" id="pbtn_diapup130" class="stzoom2" onclick="pop_lcidb();">LCI DB 검색</button> <a href="javascript:lcidb_jajae_readonly(false);" class="del">X</a></td>
                </tr><input type="hidden" id="jseq"><input type="hidden" id="umseq">
                <tr>
                    <th><span id="jstandard_area">자재규격</span></th>
                    <td><input class="inp1" name="jstandard" id="jstandard" maxlength="50" value="" /></td>
                </tr>
                <tr>
                    <th><span id="jvolume_area">수량</span></th>
                    <td><input class="inp1" numberdecimalOnly="true" name="jvolume" id="jvolume" maxlength="15" value="" /></td>
                </tr>
                <tr>
                    <th>단위</th>
                    <td><input class="inp1" name="junit" id="junit" maxlength="10" value="" /></td>
                </tr>
                <tr>
                    <th>적용여부</th>
                    <td><label><input type="radio" id="juseyn1" name="juseyn" value="Y" checked>적용</label> <label><input type="radio" id="juseyn2" name="juseyn" value="N">미적용</label></td>
                </tr>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st3" id="abtn_diapup183" onclick="maintenance_check();">등록</button> <button type="button" id="cbtn_diapup183" class="">취소</button></div>
        </dd>
    </dl>
</div>

<div id="diapup184">
	<p><a href="#;" id="obtn_diapup184">직접등록</a> &nbsp; |  &nbsp; <strong>Excel등록</strong></p>
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>파일첨부</th>
                    <td><input type="file" class="file1"/></td>
                </tr>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st3" id="abtn_diapup184">등록</button> <button type="button" id="cbtn_diapup184" class="">취소</button></div>
        </dd>
    </dl>
</div>

<div id="diapup137">
	<p><input class="inp1" id="stext" name="stext" placeholder="LCI DB 품목명을 입력하세요." /> <button class="stzoom2" onclick="search_lcidb();">검색</button></p>
    <dl class="tablebox1">
        <dd>
            <table id="search_area" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr><input type="hidden" id="lcidb_gubun" value="jajae">
                    <th class="tl"><span id="parea_tit1">재료명</span></th>
                    <th><span id="parea_tit2">규격</span></th>
                    <th><span id="parea_tit3">Co2배출량</span></th>
                    <th><span id="parea_tit4">무게</span></th>
                    <th class="tr">선택</th>
                </tr>
                <tr class="mobg">
                    <td class="c">콘크리트 <%=i%></td>
                    <td class="c">m</td>
                    <td class="c">55<%=i%></td>
                    <td class="c">1<%=i%></td>
                    <td class="c"><button class="st1">선택</button></td>
                </tr>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" id="cbtn_diapup137" class="">닫기</button></div>
        </dd>
    </dl>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>