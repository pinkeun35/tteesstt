<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
	
<script type="text/javascript">
<!--
$(function() {
	form_reset();
	list_reload(1);
	
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
			url: '/process/upload_excel_process',
			type: 'POST',
			data:  formData,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data, textStatus, jqXHR) {
				$( "#diapup122" ).dialog( "close" );
				list_reload(1);
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

var btn_write = '<button type="button" class="st3" id="abtn_diapup122" onclick="check_data();">추가</button> <button type="button" id="cbtn_diapup122" class="" onclick="close_pop(\'diapup122\');">취소</button>';
var btn_edit = '<button type="button" class="st3" id="mbtn_diapup124" onclick="check_data();">수정</button> <button type="button" id="cbtn_diapup124" class="" onclick="close_record();">취소</button>';

function form_reset() {
	$("select[name='step1'] option").remove();
	$("<option></option>").attr("selected", true).text("::선택없음::").attr("value", "0").appendTo("select[name='step1']");
	$("select[name='step2'] option").remove();
	$("<option></option>").attr("selected", true).text("::선택없음::").attr("value", "0").appendTo("select[name='step2']");
	$('#pname').val('');
	$('#pdesc').val('');
	
	change_file(false);
	
	$("#diapup122").dialog({
        title: '공정등록(직접등록)'
    });
    
    $('#area_buttonarea').html(btn_write);
	
	$('#area_step1').text('');
	$('#area_step2').text('');
	$('#area_step3').text('');
	$('#area_desc').text('');

	reload_step1();
}

function reload_step1() {
	$.post( '/process/step1_initialize', {})
		.done(function( jdata ) {
			if (jdata.status == "success") {
			//	alert('자료 ' + alert_text + '에 성공하였습니다.');
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						$("<option></option>").attr("selected", false).text(jdata.item[key].process).attr("value", jdata.item[key].seq).appendTo("select[name='step1']");
					});
				}
			}
			else if (data.status == "fail") {
				alert('자료 초기화에 싫패하였습니다.');
			}
  		}, "json");
}

function reload_step2(vStep) {
	$("select[name='step2'] option").remove();
	$("<option></option>").attr("selected", true).text("::선택없음::").attr("value", "0").appendTo("select[name='step2']");
	
	if (vStep != "0") {
		$.post( '/process/step2_initialize', {step1: vStep})
			.done(function( jdata ) {
				if (jdata.status == "success") {
				//	alert('자료 ' + alert_text + '에 성공하였습니다.');
					if ( jdata.item.length > 0 ) {
						$.each(jdata.item, function(key,state){
							$("<option></option>").attr("selected", false).text(jdata.item[key].process).attr("value", jdata.item[key].step2).appendTo("select[name='step2']");
						});
					}
				}
				else if (data.status == "fail") {
					alert('자료 초기화에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function reload_step2_seq(vStep, vNextstep) {
	$("select[name='step2'] option").remove();
	$("<option></option>").attr("selected", true).text("::선택없음::").attr("value", "0").appendTo("select[name='step2']");
	
	if (vStep != "0") {
		$.post( '/process/step2_initialize', {step1: vStep})
			.done(function( jdata ) {
				if (jdata.status == "success") {
				//	alert('자료 ' + alert_text + '에 성공하였습니다.');
					if ( jdata.item.length > 0 ) {
						$.each(jdata.item, function(key,state){
							if (parseInt(vNextstep) == parseInt(jdata.item[key].step2)) {
								$("<option></option>").attr("selected", true).text(jdata.item[key].process).attr("value", jdata.item[key].step2).appendTo("select[name='step2']");
							} else {
								$("<option></option>").attr("selected", false).text(jdata.item[key].process).attr("value", jdata.item[key].step2).appendTo("select[name='step2']");
							}
						});
					}
				}
				else if (data.status == "fail") {
					alert('자료 초기화에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function check_data() {
	if ($('#pname').val() == "") {
		alert("공정명을 입력하여 주십시오.");
		$('#pname').focus();
		return;
	}
	var job_url = '/process/insert_process';
	if ($('#sseq').val() != '') {
		job_url = '/process/update_process';
	}
	$.post( job_url, {
		step1: $('#step1').val(),
		step2: $('#step2').val(),
		pname: $('#pname').val(),
		pdesc: $('#pdesc').val(),
		seq: $('#sseq').val()
		})
		.done(function( data ) {
			if (data.status == "success") {
			//	alert('자료 ' + alert_text + '에 성공하였습니다.');
				form_reset();
				get_record(data.seq);
				list_reload($('#now_page').val());
			}
			else if (data.status == "fail") {
				alert('자료 저장에 싫패하였습니다.');
			}
  		}, "json");
}

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/process/get_process_list", { page: vPage })
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
						tr_html += '<td class="c"><input type="checkbox" name="cbxseq" value="' + jdata.item[key].seq + '" /></td>';
						tr_html += '<td><a href="javascript:get_record(' + jdata.item[key].seq + ');" class="btn_diapup158">' + jdata.item[key].name_case1 + '</a></td>';
						tr_html += '<td class="c"><a href="javascript:get_record(' + jdata.item[key].seq + ');" class="btn_diapup158">' + jdata.item[key].name_case2 + '</a></td>';
						tr_html += '<td class="c"><a href="javascript:get_record(' + jdata.item[key].seq + ');" class="btn_diapup158">' + jdata.item[key].name_case3 + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].description + '</td>';
						tr_html += '</tr>';
						
						$("#area_item").append( tr_html );
						
						line_num--;
					});
				
					page_navigation(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="5">데이터가 없습니다.</td>';
					tr_html += '</tr>';
					
					$("#area_item").append( tr_html );
				}
			}
			else if (jdata.status == "fail") {
				alert('자료 목록 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function get_record(vRecord) {
	$('#sseq').val(vRecord);
	$.post( "/process/get_process_record", { record: vRecord })
		.done(function( jdata ) {
			$( "#diapup122" ).dialog( "close" );

			if (jdata.status == "success") {
				$("#area_step1").text(jdata.item.name_case1);
				$("#area_step2").text(jdata.item.name_case2);
				$("#area_step3").text(jdata.item.name_case3);
				$("#area_desc").text(jdata.item.description);
				$('#record_json').val(JSON.stringify(jdata.item));
				
				$( "#diapup121" ).dialog( "open" );
			}
			else if (jdata.status == "fail") {
				alert('Data 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function close_record() {
	$('#sseq').val('');
	$('#record_json').val('');
	$( "#diapup121" ).dialog( "close" );
	$( "#diapup122" ).dialog( "close" );
	
	$("#diapup122").dialog({
        title: '공정등록(직접등록)'
    });
    
    $('#area_buttonarea').html(btn_write);
}

function pop_modify() {
	$( "#diapup121" ).dialog( "close" );

	var jdata = jQuery.parseJSON($('#record_json').val());
	
	$('#step1').val(jdata.step1);
	reload_step2_seq(jdata.step1, jdata.step2);
	$('#pname').val(jdata.process);
	$('#pdesc').val(jdata.description);
	
	change_file(false);
	
	$("#diapup122").dialog({
        title: '공정정보변경'
    });
    
 	$('#area_buttonarea').html(btn_edit);

	$( "#diapup122" ).dialog( "open" );
}

function pop_delete() {
	delete_record($('#sseq').val());
}

function delete_record(vRecords) {
	if (confirm('선택된 내역을 삭제하시겠습니까?')) {
		$( "#diapup121" ).dialog( "close" );
		$( "#diapup122" ).dialog( "close" );
		
		$.post( "/process/delete_process", { record: vRecords })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					$('#sseq').val('');
					$('#record_json').val('');
					
					list_reload($('#now_page').val());
				}
				else if (jdata.status == "fail") {
					alert('Data 삭제에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function add_process() {
	$('#pname').val('');
	$('#pdesc').val('');
	$('#area_buttonarea').html(btn_write);
		$("#diapup122").dialog({
        title: '공정정보추가'
    });
    
    change_file(false);

	$( "#diapup122" ).dialog( "open" );
}

function change_file(vFlag) {
	if (vFlag == false) {
		$('#ipt_area1').show();
		$('#ipt_area2').show();
		$('#ipt_area4').show();
		$('#ipt_area3').hide();
		$('#ipt_area5').hide();
		var temp_html = '<strong>직접등록</strong> &nbsp; |  &nbsp; <a href="javascript:change_file(true);" id="obtn_diapup122">Excel등록</a>';
		$('#ipt_area0').html(temp_html);
	} else {
		$('#ipt_area1').hide();
		$('#ipt_area2').hide();
		$('#ipt_area4').hide();
		$('#ipt_area3').show();
		$('#ipt_area5').show();
		var temp_html = '<a href="javascript:change_file(false);" id="obtn_diapup123">직접등록</a> &nbsp; |  &nbsp; <strong>Excel등록</strong>';
		$('#ipt_area0').html(temp_html);
	}
}

function verify_excel() {
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
	$('#hs1').val($('#step1').val());
	$('#hs2').val($('#step2').val());
	
	$("#excelform").submit();
}

function close_pop(vPopname) {
	$( "#"+vPopname ).dialog( "close" );
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_2_2"><img src="/app/views/images//common/img_subtitle2_2.gif" alt="USE DATA" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images//common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">USE DATA</a> <span>&gt;</span> <strong>공정관리</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbslistbox1"><input type="hidden" id="now_page" value="1" />
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="200" />
                        <col width="200" />
                        <col width="200" />
                        <col width="*" />
                        </colgroup>
                        <tr>
                            <th class="tl">선택</th>
                            <th>1차 공정</th>
                            <th>2차 공정</th>
                            <th>3차 공정</th>
                            <th class="tr">Memo</th>
                        </tr>
                        <!-- <tr class="mobg">
                            <td class="c" onclick="javascript:alert('체크박스선택');"><input type="checkbox" /></td>
                            <td class="btn_diapup121">건축</td>
                            <td class="btn_diapup121">전시</td>
                            <td class="btn_diapup121">로비</td>
                            <td class="btn_diapup121">메모메모모메</td>
                        </tr> -->
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" id="btn_deleteitem" class="st5">삭제</button></div>
                    <div class="btntype2"><button type="button" id="btn_diapup122" class="st1" onclick="add_process();">추가</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
        

        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup121">
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="20%" />
                <col width="30%" />
                <col width="20%" />
                <col width="30%" />
                </colgroup>
                <tr>
                    <th>1차 공정</th>
                    <td colspan="3"><span id="area_step1"></span></td>
                </tr>
                <tr>
                    <th>2차 공정</th>
                    <td colspan="3"><span id="area_step2"></span></td>
                </tr>
                <tr>
                    <th>3차 공정</th>
                    <td colspan="3"><span id="area_step3"></span></td>
                </tr>
                <tr>
                    <th>Memo</th>
                    <td colspan="3"><span id="area_desc"></span></td>
                </tr><input type="hidden" id="record_json" />
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="" id="cbtn_diapup121" onclick="close_record();">닫기</button> <button type="button" class="st3" id="mbtn_diapup121" onclick="pop_modify();">수정</button> <button type="button" class="st5" id="dbtn_diapup121" onclick="pop_delete();">삭제</button></div>
        </dd>
    </dl>
</div>

<div id="diapup122">
	<p id="ipt_area0"><strong>직접등록</strong> &nbsp; |  &nbsp; <a href="javascript:change_file(true);" id="obtn_diapup122">Excel등록</a></p>
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="20%" />
                <col width="30%" />
                <col width="20%" />
                <col width="30%" />
                </colgroup>
                <tr>
                    <th>1차 공정</th>
                    <td colspan="3"><select id="step1" name="step1" onchange="reload_step2(this.value);">
                    	<option value="0">::선택없음::</option>
					</select></td>
                </tr><input type="hidden" id="sseq" value="" />
                <tr>
                    <th>2차 공정</th>
                    <td colspan="3"><select id="step2" name="step2">
                    	<option value="0">::선택없음::</option>
					</select>
                </tr>
                <tr id="ipt_area1">
                    <th>공정명</th>
                    <td colspan="3"><input class="inp1" name="pname" id="pname" maxlength="50" value="" /></td>
                </tr>
                <tr id="ipt_area2">
                    <th>메모</th>
                    <td colspan="3"><textarea class="textarea1" id="pdesc" name="pdesc"></textarea></td>
                </tr>
                <form id="excelform" name="excelform" method="post" enctype="multipart/form-data">
                <tr id="ipt_area3">
                    <th>엑셀파일</th>
                    <td><input type="file" class="file1" name="excelupload" id="excelupload" /></td>
                </tr><input type="hidden" id="hs1" name="hs1" value=""><input type="hidden" id="hs2" name="hs2" value="">
                </form>
            </table>
        </dd>
        <dd class="bbsbtnbox1" id="ipt_area4">
            <div class="btntype3" id="area_buttonarea"><button type="button" class="st3" id="abtn_diapup122" onclick="check_data();">추가</button> <button type="button" id="cbtn_diapup122" class="" onclick="close_pop('diapup122');">취소</button></div>
        </dd>
        <dd class="bbsbtnbox1" id="ipt_area5">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup157" onclick="verify_excel();">등록</button> <button type="button" id="cbtn_diapup123" class="">취소</button></div>
        </dd>
    </dl>
</div>

<div id="diapup123">
	<p><a href="#;" id="obtn_diapup123">직접등록</a> &nbsp; |  &nbsp; <strong>Excel등록</strong></p>
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="20%" />
                <col width="30%" />
                <col width="20%" />
                <col width="30%" />
                </colgroup>
                <form id="excelform" name="excelform" method="post" enctype="multipart/form-data">
                <tr>
                    <th>1차 공정</th>
                    <td colspan="3"><select><option>토목</option><option>건축</option></select></td>
                </tr>
                <tr>
                    <th>2차 공정</th>
                    <td colspan="3"><select><option>전시</option><option>로비</option></select></td>
                </tr>
                <tr>
                    <th>엑셀파일</th>
                    <td><input type="file" class="file1" name="excelupload" id="excelupload" /></td>
                </tr>
                </form>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st3" id="abtn_diapup123">추가</button> <button type="button" id="cbtn_diapup123" class="">취소</button></div>
        </dd>
    </dl>
</div>

<div id="diapup125">
	<p>총<span id="area_deleteitem"></span>의 정보를 삭제 하시겠습니까?<br /><br />
    <button type="button" id="abtn_diapup125" class="st1">삭제하기</button> <button type="button" class="" id="cbtn_diapup125">취소</button></p>
</div>

<div id="diapup126">
	<p>총OO의 정보가 모두 삭제 되었습니다.<br /><br /><button type="button" class="" id="cbtn_diapup126">확인</button></p>
</div>


<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>