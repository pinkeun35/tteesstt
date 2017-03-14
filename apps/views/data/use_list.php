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

	$("#excelform").submit(function(e)
	{
		var formObj = $(this);
		var formURL = formObj.attr("action");
		var formData = new FormData(this);
		$.ajax({
			url: '/sdata/upload_excel_useinfo',
			type: 'POST',
			data:  formData,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data, textStatus, jqXHR) {
				$( "#diapup137" ).dialog( "close" );
				$( "#diapup130" ).dialog( "close" );
				$( "#diapup127" ).dialog( "close" );
				list_reload(1);
				list_reload2(1);
				alert(data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert("입력에 오류가 발생되었습니다.\n\n다시 확인하여 주세요.");
			}          
		});
		e.preventDefault(); //Prevent Default action. 
		e.unbind();
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

	$("select[name='eproject'] option").remove();
	$("select[name='ezone'] option").remove();
	$("<option></option>").attr("selected", true).text("::공구선택::").attr("value", "0").appendTo("select[name='ezone']");
	$("select[name='ebuild'] option").remove();
	$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='ebuild']");
	
	load_listproject();
}

function load_dataschedule (vPage) {
	$('#now_page2').val(vPage);
	
	$.post( '/sdata/get_use_schedule', {
			page: vPage,
			project: $('#lproject').val(),
			zone: $('#lzone').val(),
			build: $('#lbuild').val()
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {

				$("#schedule_item tr:not(:first)").remove();

				$('#area_nowpage2').text(jdata.now_page);
				$('#area_totalpage2').text(jdata.page_total);
				$("#area_item2 tr:not(:first)").remove();

				var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);
				
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var sum_data = parseFloat(jdata.item[key].data1) + parseFloat(jdata.item[key].data2) + parseFloat(jdata.item[key].data3) + parseFloat(jdata.item[key].data4) + parseFloat(jdata.item[key].data5);
						var tr_html = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c"><input type="checkbox" disabled="disabled" /></td>';
						tr_html += '<td class="c"><a href="javascript:get_dataschedule_record(' + jdata.item[key].seq + ');">' + jdata.item[key].bname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].data1 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].data2 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].data3 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].data4 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].data5 + '</td>';
						tr_html += '<td class="c">' + sum_data + '</td>';
						tr_html += '</tr>';
						
						$("#schedule_item").append( tr_html );
						
						line_num--;
					});
				
					page_navigation2(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="8">데이터가 없습니다.</td>';
					tr_html += '</tr>';
					
					$("#schedule_item").append( tr_html );
				}

			}
			else if (data.status == "fail") {
				alert('자료 읽기에 싫패하였습니다.');
			}
  		}, "json");
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
						$("<option></option>").attr("selected", false).text(jdata.item[key].prjname).attr("value", jdata.item[key].seq).appendTo("select[name='eproject']");
					});
					
					load_listzone($('#lproject').val(), 'lzone', 'lbuild', '');
					load_listzone($('#pproject').val(), 'pzone', 'pbuild', '');
					load_listzone($('#eproject').val(), 'ezone', 'ebuild', '');
					load_changelist();
					
					load_dataschedule(1);
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
	$('#lyear1').val('0');
	$('#lmonth1').val('0');
	$('#lyear2').val('0');
	$('#lmonth2').val('0');
	
	list_reload(1);
}

function list_reload(vPage) {
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
	
	$('#now_page').val(vPage);
	$.post( "/sdata/get_useinfo_list", {
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
				$('#area_nowpage').text(jdata.now_page);
				$('#area_totalpage').text(jdata.page_total);
				$("#area_item tr:not(:first)").remove();

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
						var sum_data = parseFloat(jdata.item[key].data1) + parseFloat(jdata.item[key].data2) + parseFloat(jdata.item[key].data3) + parseFloat(jdata.item[key].data4) + parseFloat(jdata.item[key].data5) + parseFloat(jdata.item[key].data6);
						var tr_html = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c"><input type="checkbox" name="cbxseq" value="' + jdata.item[key].seq + '" /></td>';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td class="c"><a href="javascript:get_useinfo_record(' + jdata.item[key].seq + ');">' + jdata.item[key].use_year + '년 ' + jdata.item[key].use_month + '월</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].data1	+ '</td>';
						tr_html += '<td class="c">' + jdata.item[key].data2 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].data3 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].data4 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].data5 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].data6 + '</td>';
						tr_html += '<td class="c">' + sum_data + '</td>';
						tr_html += '</tr>';
						
						$("#area_item").append( tr_html );
						
						line_num--;
					});
				
					page_navigation(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="10">데이터가 없습니다.</td>';
					tr_html += '</tr>';
					
					$("#area_item").append( tr_html );
				}
			}
			else if (jdata.status == "fail") {
				alert('자료 목록 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function get_useinfo_record(vRecord) {
	$('#sseq').val(vRecord);
	$.post( "/sdata/get_useinfo_record", { record: vRecord })
		.done(function( jdata ) {
			$( "#diapup122" ).dialog( "close" );

			if (jdata.status == "success") {
				$('#sseq').val(vRecord);
				
				modify_pop_useinfo(jdata.item);
				$( "#diapup141" ).dialog( "open" );
			}
			else if (jdata.status == "fail") {
				alert('Data 읽기에 싫패하였습니다.');
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
		$.post( "/sdata/delete_useinfo", { record: vRecords })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					list_reload($('#now_page').val());
				}
				else if (jdata.status == "fail") {
					alert('Data 읽기에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

var pop_useinfo_insert = '<button type="button" class="st3" id="abtn_diapup141" onclick="check_useinfo();">등록</button> <button type="button" id="cbtn_diapup141" class="">취소</button>';
var pop_useinfo_modify = '<button type="button" class="st3" id="abtn_diapup141" onclick="check_useinfo();">수정</button> <button type="button" id="cbtn_diapup141" class="">취소</button>';

var pop_dataschedule_insert = '<button type="button" class="st3" id="mbtn_diapup140" onclick="insert_dataschedule();">등록</button> <button type="button" id="cbtn_diapup140" class="" onclick="close_pop_dataschedule();">취소</button>';
var pop_dataschedule_modify = '<button type="button" class="st3" id="mbtn_diapup140" onclick="modify_dataschedule();">수정</button> <button type="button" id="cbtn_diapup140" class="" onclick="close_pop_dataschedule();">취소</button>';

function init_pop_useinfo() {
	$('#sseq').val('');
	$('#eyear').val('<?php echo ''.date("Y"); ?>');
	$('#emonth').val('<?php echo ''.date("m"); ?>');
	
	$('#edata1').val('');
	$('#edata2').val('');
	$('#edata3').val('');
	$('#edata4').val('');
	$('#edata5').val('');
	$('#edata6').val('');
	$('#area_useinfo_btn').html(pop_useinfo_insert);
	$("#diapup141").dialog({
        title: 'Use Energy 사용정보 직접입력'
    });
}

function modify_pop_useinfo(jdata) {
	$('#pproject').val(jdata.project_seq);
	load_listzone(jdata.project_seq, 'pzone', 'pbuild', jdata.zone_seq);
	load_listbuild(jdata.zone_seq, jdata.project_seq, 'pbuild', jdata.build_seq);
	
	$('#eyear').val(jdata.use_year);
	$('#emonth').val(jdata.use_month);
	
	$('#edata1').val(jdata.data1);
	$('#edata2').val(jdata.data2);
	$('#edata3').val(jdata.data3);
	$('#edata4').val(jdata.data4);
	$('#edata5').val(jdata.data5);
	$('#edata6').val(jdata.data6);
	$('#area_useinfo_btn').html(pop_useinfo_modify);
	$("#diapup141").dialog({
        title: 'Use Energy 사용정보 수정'
    });
}

function pop_useinfo() {
	init_pop_useinfo();
	$( "#diapup141" ).dialog( "open" );
}

function check_useinfo() {
	if ($('#pzone').val() == "0") {
		alert('공구를 선택하여 주십시오.');
		$('#pzone').focus();
		return;
	}
	
	var action_url = '/sdata/insert_useinfo';
	if ($('#edata1').val() == "" && $('#edata2').val() == "" && $('#edata3').val() == "" && $('#edata4').val() == "" && $('#edata5').val() == "" && $('#edata6').val() == "") {
		alert('사용 정보는 한가지라도 입력되어야 합니다.');
		$('#jname').focus();
		return;
	}

	$.post( action_url, {
			pproject: $('#pproject').val(),
			pzone: $('#pzone').val(),
			pbuild: $('#pbuild').val(),
			eyear: $('#eyear').val(),
			emonth: $('#emonth').val(),
			edata1: $('#edata1').val(),
			edata2: $('#edata2').val(),
			edata3: $('#edata3').val(),
			edata4: $('#edata4').val(),
			edata5: $('#edata5').val(),
			edata6: $('#edata6').val(),
			sseq: $('#sseq').val()
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$( "#diapup141" ).dialog( "close" );
				
				$('#lyear1').val('0');
				$('#lmonth1').val('0');
				$('#lyear2').val('0');
				$('#lmonth2').val('0');
				list_reload(1);
			}
			else if (data.status == "fail") {
				alert('자료 저장에 싫패하였습니다.');
			}
  		}, "json");
}

function insert_dataschedule() {
	if ($('#ezone').val() == "0") {
		alert('공구를 선택하여 주세요.');
		$('#ezone').focus();
		return;
	}
	if ($('#ebuild').val() == "0") {
		alert('건물을 선택하여 주세요.');
		$('#ebuild').focus();
		return;
	}
	if ($('#sdata1').val() == "") {
		alert('냉방 정보를 입력하여 주세요.');
		$('#sdata1').focus();
		return;
	}
	if ($('#sdata2').val() == "") {
		alert('난방 정보를 입력하여 주세요.');
		$('#sdata2').focus();
		return;
	}
	if ($('#sdata3').val() == "") {
		alert('급탕 정보를 입력하여 주세요.');
		$('#sdata3').focus();
		return;
	}
	if ($('#sdata4').val() == "") {
		alert('환기 정보를 입력하여 주세요.');
		$('#sdata4').focus();
		return;
	}
	if ($('#sdata5').val() == "") {
		alert('조명 정보를 입력하여 주세요.');
		$('#sdata5').focus();
		return;
	}

	var action_url = '/sdata/insert_dataschedule';

	$.post( action_url, {
			project: $('#eproject').val(),
			zone: $('#ezone').val(),
			build: $('#ebuild').val(),
			sdata1: $('#sdata1').val(),
			sdata2: $('#sdata2').val(),
			sdata3: $('#sdata3').val(),
			sdata4: $('#sdata4').val(),
			sdata5: $('#sdata5').val(),
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$( "#diapup140" ).dialog( "close" );
				load_dataschedule(1);
			}
			else if (data.status == "fail") {
				alert('자료 저장에 싫패하였습니다.');
			}
  		}, "json");
}

function modify_dataschedule() {
	var action_url = '/sdata/update_dataschedule';

	$.post( action_url, {
			seq: $('#dsseq').val(),
			sdata1: $('#sdata1').val(),
			sdata2: $('#sdata2').val(),
			sdata3: $('#sdata3').val(),
			sdata4: $('#sdata4').val(),
			sdata5: $('#sdata5').val(),
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$( "#diapup140" ).dialog( "close" );
				load_dataschedule($('#now_page2').val());
			}
			else if (data.status == "fail") {
				alert('자료 저장에 싫패하였습니다.');
			}
  		}, "json");
}

function get_dataschedule_record(vRecord) {
	$('#sseq').val(vRecord);
	$.post( "/sdata/get_dataschedule_record", { record: vRecord })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$('#dsseq').val(vRecord);
				modify_pop_dataschedule(jdata.item);
				$( "#diapup140" ).dialog( "open" );
			}
			else if (jdata.status == "fail") {
				alert('Data 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function modify_pop_dataschedule(jdata) {
	$('#eproject').val(jdata.project_seq);
	load_listzone(jdata.project_seq, 'ezone', 'ebuild', jdata.zone_seq);
	load_listbuild(jdata.zone_seq, jdata.project_seq, 'ebuild', jdata.build_seq);
	
	change_file(false);
	
	$("#eproject").attr("disabled", true);
	$("#ezone").attr("disabled", true);
	$("#ebuild").attr("disabled", true);
	
	$('#sdata1').val(jdata.data1);
	$('#sdata2').val(jdata.data2);
	$('#sdata3').val(jdata.data3);
	$('#sdata4').val(jdata.data4);
	$('#sdata5').val(jdata.data5);
	$('#area_schedule_btn').html(pop_dataschedule_modify);
	$("#diapup140").dialog({
        title: 'Use Energy 사용 예상 정보 수정'
    });
}

function init_pop_dataschedule() {
	$('#dsseq').val('');
	
	change_file(false);
	
	$('#eproject').val($('#lproject').val());
	load_listzone($('#lproject').val(), 'ezone', 'ebuild', $('#lzone').val());
	load_listbuild($('#lzone').val(), $('#lproject').val(), 'ebuild', $('#lbuild').val());
	
	$("#eproject").attr("disabled", false);
	$("#ezone").attr("disabled", false);
	$("#ebuild").attr("disabled", false);
	
	$('#sdata1').val('');
	$('#sdata2').val('');
	$('#sdata3').val('');
	$('#sdata4').val('');
	$('#sdata5').val('');
	$('#area_schedule_btn').html(pop_dataschedule_insert);
	$("#diapup140").dialog({
        title: 'Use Energy 사용 예상 정보 등록'
    });
    
    $( "#diapup140" ).dialog( "open" );
}

function close_pop_dataschedule() {
	$( "#diapup140" ).dialog( "close" );
}

function subpage_change(page) {
	if (page != '')
		document.location = page;
}

function change_file(vFlag) {
	if (vFlag == false) {
		$('#table_info').show();
		$('#table_file').hide();
		$('#btn_info').show();
		$('#btn_file').hide();

		var temp_html = '<strong>직접등록</strong> &nbsp; |  &nbsp; <a href="javascript:change_file(true);" id="obtn_diapup122">Excel등록</a>';
		$('#ipt_area0').html(temp_html);
	} else {
		$('#table_info').hide();
		$('#table_file').show();
		$('#btn_info').hide();
		$('#btn_file').show();

		var temp_html = '<a href="javascript:change_file(false);" id="obtn_diapup123">직접등록</a> &nbsp; |  &nbsp; <strong>Excel등록</strong>';
		$('#ipt_area0').html(temp_html);
	}
}

function verify_excel() {
	if ($('#pzone').val() == "0") {
		alert('공구를 선택하여 주십시오.');
		$('#pzone').focus();
		return;
	}
	if ($('#excelupload').val() == '') {
		alert('엑셀 파일을 첨부하여 주세요.\n\n.xlsx 파일은 안됩니다.');
		return;
	}
	var extension = $('#excelupload').val().substring(($('#excelupload').val().lastIndexOf('.') + 1), $('#excelupload').val().length);
	extension = extension.toLowerCase();
	if (extension != "xls") {
		alert('xls 파일만 업로드 가능합니다.');
		return;
	}
	$('#fproject').val($('#pproject').val());
	$('#fzone').val($('#pzone').val());
	$('#fbuild').val($('#pbuild').val());

	$("#excelform").submit();
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
            
            <dl id="datalisttapmenu" class="datalisttapmenu2">
            	<dd class="menu1"><a href="/sdata/preuse_list">Pre-use</a></dd>
                <dd class="menu2"><a href="/sdata/use_list" class="strong">Use</a></dd>
                <dd class="menu3"><a href="/sdata/postuse_list">Post-use</a></dd>
            </dl>
            
            <p class="bbstitle1">사용 예정 정보</p>            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage2">1</strong>/<span id="area_totalpage2">1</span></dd>
                <dd class="bbsselectbox1"><input type="hidden" id="now_page2" value="1" />
                <form name="" method="" action="">
                    <fieldset>
                    <legend>데이터유형</legend>
                    <select id="subpage" name="subpage" onchange="subpage_change(this.value);"><option value="" selected="selected">Energy</option><option value="/sdata/maintenance_list">유지보수</option></select>
                    </fieldset>
                </form>
                </dd>
                <dd class="bbslistbox1">
                    <table id="schedule_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="21%" />
                        <col width="12%" />
                        <col width="12%" />
                        <col width="12%" />
                        <col width="12%" />
                        <col width="12%" />
                        <col width="12%" />
                        </colgroup>
                        <tr>
                            <th class="tl"><input type="checkbox" disabled="disabled" /></th>
                            <th>건물명</th>
                            <th>냉방</th>
                            <th>난방</th>
                            <th>급탕</th>
                            <th>환기</th>
                            <th>조명</th>
                            <th class="tr">합계</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype2"><button type="button" id="btn_diapup140" class="st3" onclick="init_pop_dataschedule();">등록</button></div>
                    <div class="pageing" id="area_paging2"></div>
                </dd>
            </dl>
        
            
            <p class="bbstitle2">실 사용량 정보</p>            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbssearchbox1"><input type="hidden" id="now_page" value="1" />
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
                    <input type="image" src="/app/views/images//common/icon_zoom1.gif" class="sbtn" title="검색" onclick="list_reload(1);" />
                    </fieldset>
                </dd>
                <dd>
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="7%" />
                        <col width="*" />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="12%" />
                        </colgroup>
                        <tr>
                            <th class="tl"><input type="checkbox" id="all_record_select" onclick="checkbox_checkall();" /></th>
                            <th>No.</th>
                            <th>사용일</th>
                            <th>전기</th>
                            <th>상수도</th>
                            <th>경유</th>
                            <th>도시가스</th>
                            <th>LPG</th>
                            <th>펠릿</th>
                            <th class="tr">합계</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" id="btn_deleteitem" class="st5">삭제</button></div>
                    <div class="btntype2"><button type="button" id="btn_diapup141" class="st3" onclick="pop_useinfo();">등록</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype3"><button class="st1">Co2 계산</button></div>
                </dd>
            </dl>

        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup140">
    <dl class="tablebox4_1">
        <dd>
	<p class="p1"><select id="eproject" name="eproject" onchange="load_listzone(this.value, 'ezone', 'ebuild', '');">
		<option>경기도 시립박물관</option>
	</select> <select id="ezone" name="ezone" onchange="load_listbuild(this.value, $('#eproject').val(), 'ebuild', '');">
		<option>제1공구</option>
	</select> <select id="ebuild" name="ebuild">
		<option>보관함1</option>
	</select></p>
	
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>냉방</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="sdata1" id="sdata1" maxlength="15" value="" /></td>
                </tr>
                <tr>
                    <th>난방</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="sdata2" id="sdata2" maxlength="15" value="" /></td>
                </tr>
                <tr>
                    <th>급탕</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="sdata3" id="sdata3" maxlength="15" value="" /></td>
                </tr>
                <tr>
                    <th>환기</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="sdata4" id="sdata4" maxlength="15" value="" /></td>
                </tr>
                <tr>
                    <th>조명</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="sdata5" id="sdata5" maxlength="15" value="" /></td>
                </tr>
            </table><input type="hidden" id="dsseq" value="">
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3" id="area_schedule_btn"><button type="button" class="st3" id="mbtn_diapup140" onclick="insert_dataschedule();">등록</button> <button type="button" id="cbtn_diapup140" class="">취소</button></div>
            <!-- <div class="btntype3"><button type="button" class="st3" id="mbtn_diapup140" onclick="modify_dataschedule();">등록</button> <button type="button" id="cbtn_diapup140" class="">취소</button></div> -->
        </dd>
    </dl>
</div>

<div id="diapup141">
	<p class="p1"><select id="pproject" name="pproject" onchange="load_listzone(this.value, 'pzone', 'pbuild', '');">
		<option>경기도 시립박물관</option>
	</select> <select id="pzone" name="pzone" onchange="load_listbuild(this.value, $('#pproject').val(), 'pbuild', '');">
		<option>제1공구</option>
	</select> <select id="pbuild" name="pbuild">
		<option>보관함1</option>
	</select></p>
	<p id="ipt_area0"><strong>직접등록</strong> &nbsp; |  &nbsp; <a href="javascript:change_file(true);" id="obtn_diapup122">Excel등록</a></p>
    <dl class="tablebox4_1">
        <dd>
            <table id="table_info" class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>사용일자</th>
                    <td><select id="eyear" name="eyear">
                    	<?php for ($i=2000; $i<=(date("Y")+100); $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; } ?>
                    </select>년 &nbsp;<select id="emonth" name="emonth">
                    	<?php for ($i=1; $i<=12; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; } ?>
                	</select>월</td>
                </tr><input type="hidden" id="sseq" name="sseq" />
                <tr>
                    <th>전기</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="edata1" id="edata1" maxlength="15" value="" /> ㎾</td>
                </tr>
                <tr>
                    <th>상수도</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="edata2" id="edata2" maxlength="15" value="" /> L</td>
                </tr>
                <tr>
                    <th>경유</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="edata3" id="edata3" maxlength="15" value="" /> L</td>
                </tr>
                <tr>
                    <th>도시가스</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="edata4" id="edata4" maxlength="15" value="" /> ㎥</td>
                </tr>
                <tr>
                    <th>LPG</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="edata5" id="edata5" maxlength="15" value="" /> ㎥</td>
                </tr>
                <tr>
                    <th>팰릿</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="edata6" id="edata6" maxlength="15" value="" /> ㎏</td>
                </tr>
            </table>
            <table id="table_file" class="tablewritetype4" border="0" cellpadding="0" cellspacing="0" style="display:none;">
                <form id="excelform" name="excelform" method="post" enctype="multipart/form-data">
                <tr>
                    <th>파일첨부</th>
                    <td><input type="file" class="file1" name="excelupload" id="excelupload" /></td>
                </tr>
                <input type="hidden" id="fproject" name="fproject" value=""><input type="hidden" id="fzone" name="fzone" value=""><input type="hidden" id="fbuild" name="fbuild" value="">
                </form>
            </table>
        </dd>
        <dd class="bbsbtnbox1" id="btn_info">
            <div class="btntype3" id="area_useinfo_btn"><button type="button" class="st3" id="abtn_diapup141" onclick="check_useinfo();">등록</button> <button type="button" id="cbtn_diapup141" class="">취소</button></div>
        </dd>
        <dd class="bbsbtnbox1" id="btn_file" style="display:none;">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup157" onclick="verify_excel();">등록</button> <button type="button" id="cbtn_diapup141_1" class="">취소</button></div>
        </dd>
    </dl>
</div>

<div id="diapup142">
	<p><a href="#;" id="obtn_diapup142">직접등록</a> &nbsp; |  &nbsp; <strong>Excel등록</strong></p>
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
            <div class="btntype3"><button type="button" class="st3" id="abtn_diapup142">등록</button> <button type="button" id="cbtn_diapup142" class="">취소</button></div>
        </dd>
    </dl>
</div>

<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>