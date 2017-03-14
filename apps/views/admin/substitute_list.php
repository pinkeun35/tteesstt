<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
	
<script type="text/javascript">
<!--
$(function() {
	$(document).on("keyup", "input:text[numberdecimalOnly]", function() {$(this).val( $(this).val().replace(/[^0-9.]/gi,"") );});
	
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
			url: '/admin/upload_excel_substitute',
			type: 'POST',
			data:  formData,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data, textStatus, jqXHR) {
				$( "#diapup170" ).dialog( "close" );
				list_reload(1);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert("입력에 오류가 발생되었습니다.\n\n다시 확인하여 주세요.");
			}          
		});
		e.preventDefault(); //Prevent Default action. 
		e.unbind();
	});
});

function form_reset() {
	var now = new Date();
	var year= now.getFullYear();
	var mon = (now.getMonth()+1)>9 ? ''+(now.getMonth()+1) : '0'+(now.getMonth()+1);
	var day = now.getDate()>9 ? ''+now.getDate() : '0'+now.getDate();
	var chan_val = year + '-' + mon + '-' + day;

	$('#sseq').val('');
	$('#jajae').val('');
	$('#jname').val('');
	$('#junit').val('');
	$('#iunit').val('');
	$('#correct').val('');
	$('#area_writertitle').text('등록자');
	$('#area_writer').text('<?php echo $session_id; ?>');
	$('#area_date').text(chan_val);
}

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/admin/get_substitute_list", { page: vPage })
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
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td><a href="javascript:get_record(' + jdata.item[key].seq + ');" class="btn_diapup158">' + jdata.item[key].jname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].junit + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].iunit + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].correct + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].wdate.substring(0, 10) + '</td>';
						tr_html += '</tr>';
						
						$("#area_item").append( tr_html );
						
						line_num--;
					});
				
					page_navigation(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="7">데이터가 없습니다.</td>';
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
	$.post( "/admin/get_substitute_record", { record: vRecord })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$( "#diapup169" ).dialog( "open" );
				
				$('#jajae').val(jdata.item.jajae_seq);
				$('#jname').val(jdata.item.jname);
				$('#junit').val(jdata.item.junit);
				$('#iunit').val(jdata.item.iunit);
				$('#correct').val(jdata.item.correct);
				$('#area_writertitle').text('등록자');
				$('#area_writer').text(jdata.item.uid);
				$('#area_date').text(jdata.item.wdate.substring(0, 10));

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
		$.post( "/admin/substitute_delete", { record: vRecords })
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

	$("#excelform").submit();
}

function search_reset() {
	$('#search_text').val('');
	$("#search_item tr:not(:first)").remove();
}

function check_data() {
	if ($('#jname').val() == "") {
		alert('자재명을 선택하여 주세요.');
		$('#jname').focus();
		return;
	}
	if ($('#iunit').val() == "") {
		alert('입력단위를 입력하여 주세요.');
		$('#iunit').focus();
		return;
	}
	if ($('#correct').val() == "") {
		alert('치환보정값을 입력하여 주세요.');
		$('#correct').focus();
		return;
	}
	
	var alert_text = "입력";
	var action_url = "/admin/substitute_insert";
	var reload_page = "1";
	if ($('#sseq').val() != "") {
		alert_text = "수정";
		action_url = "/admin/substitute_update";
		reload_page = $('#now_page').val();
	}
	if (confirm('해당 내역을 ' + alert_text + '하시겠습니까?')) {
		$.post( action_url, {
			jajae: $('#jajae').val(),
			jname: $('#jname').val(),
			junit: $('#junit').val(),
			iunit: $('#iunit').val(),
			correct: $('#correct').val(),
			sseq: $('#sseq').val()
			})
			.done(function( data ) {
				if (data.status == "success") {
				//	alert('자료 ' + alert_text + '에 성공하였습니다.');
					form_reset();
					$( "#diapup169" ).dialog( "close" );
					
					list_reload(reload_page);
				}
				else if (data.status == "fail") {
					alert('자료 ' + alert_text + '에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function search_action() {
	if ($('#search_text').val() == '') {
		alert('표준용어를 입력하세요.');
		$('#search_text').focus();
		return;
	}
		$.post( '/admin/lcijajae_substitute_search', {
			search_text: $('#search_text').val()
			})
			.done(function( data ) {
				if (data.status == "success") {
				//	alert('표준용어 검색에 성공하였습니다.');
					$("#search_item tr:not(:first)").remove();
					if ( data.item.length > 0 ) {
						var line_num = 1;
						$.each(data.item, function(key,state){
							var tr_html = '';
							tr_html += '<tr class="mobg">';
							tr_html += '<td class="c">' + line_num + '</td>';
							tr_html += '<td class="c">' + data.item[key].jname + '</td>';
							tr_html += '<td class="c">' + data.item[key].junit + '</td>';
							tr_html += '<td class="c"><button class="st1" onclick="item_select(' + data.item[key].seq + ',\'' + data.item[key].jname + '\',\'' + data.item[key].junit + '\');">선택</button></td>';
							tr_html += '</tr>';
							
							$("#search_item").append( tr_html );
							line_num++;
						});
					}
				}
				else if (data.status == "fail") {
					alert('표준용어 검색에 싫패하였습니다.');
				}
	  		}, "json");
}

function item_select(vSeq, vName, vUnit) {
	$('#jajae').val(vSeq);
	$('#jname').val(vName);
	$('#junit').val(vUnit);

	$( "#diapup172" ).dialog( "close" );
	
	$('#similar').focus();
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_6_5"><img src="/app/views/images/common/img_subtitle6_5.gif" alt="ADMIN" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">ADMIN</a> <span>&gt;</span> <strong>단위관리</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">

            <dl id="adminlisttapmenu" class="adminlisttapmenu4">
            	<dd class="menu4"><a href="#;" class="strong">치환단위</a></dd>
                <dd class="menu5"><a href="/admin/correct_list">보정단위</a></dd>
            </dl>

            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbslistbox1"><input type="hidden" id="now_page" value="1" />
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="7%" />
                        <col width="*" />
                        <col width="7%" />
                        <col width="7%" />
                        <col width="11%" />
                        <col width="11%" />
                        </colgroup>
                        <tr>
                            <th class="tl"><input type="checkbox" id="all_record_select" onclick="checkbox_checkall();" /></th>
                            <th>No.</th>
                            <th>자재명</th>
                            <th>자재단위</th>
                            <th>입력단위</th>
                            <th>치환보정값</th>
                            <th class="tr">등록일(수정일)</th>
                        </tr>
                        <!-- <tr class="mobg">
                            <td class="c"><input type="checkbox" /></td>
                            <td class="c"><%=i%></td>
                            <td class="c"><a href="#;" class="btn_diapup171">콘크리트a</a></td>
                            <td class="c">㎏</td>
                            <td class="c">㎏</td>
                            <td class="c">100</td>
                            <td class="c">2014-12-30</td>
                        </tr> -->
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" id="btn_deleteitem" class="">삭제</button></div>
                    <div class="btntype2"><button type="button" id="btn_diapup169" class="st2">등록</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup169">
	<p><strong>직접등록</strong> &nbsp; |  &nbsp; <a href="#;" id="obtn_diapup169">Excel등록</a></p>
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
                    <th>자재명</th>
                    <td><input class="inp2" name="jname" id="jname" value="" readonly="readonly" /> <button class="btn_diapup172">검색</button></td>
                </tr><input type="hidden" id="sseq" value="" /><input type="hidden" id="jajae" value="" />
                <tr>
                    <th>자재단위</th>
                    <td><input class="inp1" name="junit" id="junit" maxlength="10" value="" readonly="readonly" /></td>
                </tr>
                <tr>
                    <th>입력단위</th>
                    <td><input class="inp1" name="iunit" id="iunit" maxlength="10" value="" /></td>
                </tr>
                <tr>
                    <th>치환보정값</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="correct" id="correct" maxlength="15" value="" /></td>
                </tr>
                <tr>
                    <th><span id="area_writertitle">등록자</span></th>
                    <td><span id="area_writer"></span></td>
                </tr>
                <tr>
                    <th>등록일</th>
                    <td><span id="area_date"></span></td>
                </tr>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup169" onclick="check_data();">등록</button> <button type="reset" class="st4">다시쓰기</button> <button type="button" id="cbtn_diapup169" class="">취소</button></div>
        </dd>
    </dl>
</div>

<div id="diapup170">
	<p><a href="#;" id="obtn_diapup170">직접등록</a> &nbsp; |  &nbsp; <strong>Excel등록</strong></p>
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="20%" />
                <col width="*" />
                </colgroup>
                <form id="excelform" name="excelform" method="post" enctype="multipart/form-data">
                <tr>
                    <th>엑셀파일</th>
                    <td><input type="file" class="file1" name="excelupload" id="excelupload" /></td>
                </tr>
                </form>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup170" onclick="verify_excel();">등록</button> <button type="button" id="cbtn_diapup170" class="">취소</button></div>
        </dd>
    </dl>
</div>

<div id="diapup172">
	<p><input class="inp1" name="search_text" id="search_text" maxlength="50" value="" placeholder="자재명을 입력하세요." /> <button class="stzoom2" onclick="search_action();">검색</button></p>
    <dl class="tablebox1">
        <dd>
            <table id="search_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">No.</th>
                    <th>제목</th>
                    <th>단위</td>
                    <th class="tr">선택</th>
                </tr>
                <!-- <tr class="mobg">
                    <td class="c"><%=i%></td>
                    <td class="c">55<%=i%></td>
                    <td class="c">kg</td>
                    <td class="c"><button class="st1">선택</button></td>
                </tr> -->
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" id="cbtn_diapup172" class="">닫기</button></div>
        </dd>
    </dl>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>