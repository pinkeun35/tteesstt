<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="/app/views/js/CKeditor/ckeditor.js"></script>

<script type="text/javascript">
<!--
var editor = [ ];
var regext = /<?php echo $extension; ?>/i;

$(function() {
	list_reload(1);

	$("#dates").datepicker();
	$("#dates").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#datee").datepicker();
	$("#datee").datepicker("option", "dateFormat", "yy-mm-dd");

	editor[0] = CKEDITOR.replace( 'contents1',{
		enterMode:'2',
		shiftEnterMode:'3'
	});

	$("#dataform").submit(function(e)
	{
		var formObj = $(this);
		var formURL = formObj.attr("action");
		var formData = new FormData(this);
		$.ajax({
			url: '/community/insert_popup',
			type: 'POST',
			data:  formData,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data, textStatus, jqXHR) {
				close_write();
				if ($('#bseq').val() == "") {
					list_reload(1);
				} else {
					$('#bseq').val('');
					list_reload($('#now_page').val());
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert("입력에 오류가 발생되었습니다.\n\n다시 확인하여 주세요.");
			}          
		});
		e.preventDefault(); //Prevent Default action. 
		e.unbind();
	});

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

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/community/get_popup_list", { page: vPage })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$('#bseq').val('');
				$('#jdata').val('');
				
				$('#area_nowpage').text(jdata.now_page);
				$('#area_totalpage').text(jdata.page_total);
				$("#area_item tr:not(:first)").remove();

				var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);
				
				if ( jdata.item.length > 0 ) {
					var file_icon = '<img src="/app/views/images/common/icon_download.gif" alt="다운로드">';
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						var reply_step = '';
						var reply_step_image = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c"><input type="checkbox" name="cbxseq" value="' + jdata.item[key].seq + '" /></td>';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td><a href="javascript:get_record(' + jdata.item[key].seq + ');" class="btn_diapup158">' + jdata.item[key].title + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].date_start + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].date_end + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].uid + '</td>';
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

function get_record(vSeq) {
	$.post( '/community/get_popup_record', { record: vSeq })
		.done(function( data ) {
			if (data.status == "success") {
				$('#bseq').val(vSeq);
				$('#jdata').val(JSON.stringify(data));
				print_view(data.item);
			} else if (data.status == "fail") {
				alert('자료 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function print_view(info, editable) {
	$('#area_01').html(info.title);
	$('#area_02').html(info.uid);
	$('#area_03').html(info.wdate.substring(0,10));
	$('#area_05').html(info.contents);
	$('#area_06').html(info.date_start);
	$('#area_07').html(info.date_end);
	
	print_view_file(info.bfile, 'view');
		
	$( "#diapup153" ).dialog( "open" );
}

function print_view_file(vFile, vGubun) {
	if (vFile != "") {
		file_arr = vFile.split(",");
		var temp_html = "";
		for (var i=0; i<file_arr.length; i++) {
			fitem = file_arr[i].split("^");
			temp_html += '<a href="/uploads/popup/'+fitem[1]+'" target="lcco2file">'+fitem[1]+'</a>';
			if (vGubun == "modify") {
				temp_html += ' <a href="javascript:delete_file(' + fitem[0] + ',\'' + fitem[1] + '\');"><img src="/app/views/images/common/icon_del1.gif" alt="삭제"/></a>';
			}
			temp_html += '<br />';
		}
		
		if (vGubun == "view") {
			$('#area_04').html(temp_html);
		} else if (vGubun == "modify") {
			$('#warea_03').html('<br /><br />'+temp_html);
		}
	} else {
		$('#area_04').html('');
		$('#warea_03').html('');
	}
}

function delete_file(vIdx, vFname) {
	var jdata = jQuery.parseJSON($('#jdata').val());
	if (confirm('선택하신 파일을 정말로 삭제하시겠습니까?\n\n삭제하신 내역은 다시 복구할 수 없습니다.')) {
		$.post( '/community/delete_popup_file', { record: $('#bseq').val(), frecord: vIdx, fname: vFname })
			.done(function( data ) {
				if (data.status == "success") {
					$('#jdata').val(JSON.stringify(data));
					
					print_view_file(data.item.bfile, 'modify');
				} else if (data.status == "fail") {
					alert('자료 처리에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function go_delete(vSeq) {
	if (confirm('선택하신 내역을 정말로 삭제하시겠습니까?\n\n삭제하신 내역은 다시 복구할 수 없습니다.')) {
			$.post( '/community/delete_popup', { record: $('#bseq').val() })
				.done(function( data ) {
					if (data.status == "success") {
						close_view();
						list_reload($('#now_page').val());
					} else if (data.status == "fail") {
						alert('자료 처리에 싫패하였습니다.');
					}
		  		}, "json");
	}
}

function go_write() {
	$('#bseq').val('');
	$('#jdata').val('');
	$('#btitle').val('');
	$("#dates").datepicker("setDate","");
	$("#datee").datepicker("setDate","");
	$('#fupload').val('');
	$('#contents1').val('');
	editor[0].setData($('#contents1').val());
	editor[0].updateElement();
	$('#warea_01').html($('#nwriter').val());
	$('#warea_02').html($('#ndate').val());
	$('#warea_03').html('');

	$("#diapup155").dialog({
        title: '팝업 등록'
    });
	$( "#diapup155" ).dialog( "open" );
}

function go_modify(vJob) {
	var jdata = jQuery.parseJSON($('#jdata').val());
	
	$('#btitle').val(jdata.item.title);
	$("#dates").datepicker("setDate",jdata.item.date_start);
	$("#datee").datepicker("setDate",jdata.item.date_end);
	$('#fupload').val('');
	$('#contents1').val(jdata.item.contents);
	editor[0].setData($('#contents1').val());
	editor[0].updateElement();
	$('#warea_01').html(jdata.item.uid);
	$('#warea_02').html(jdata.item.wdate.substring(0,10));
	
	print_view_file(jdata.item.bfile, 'modify');

	$("#diapup155").dialog({
        title: '팝업 내용 수정'
    });
	
	close_view();

	$( "#diapup155" ).dialog( "open" );
}

function close_write() {
	$( "#diapup155" ).dialog( "close" );
}

function close_view() {
	$( "#diapup153" ).dialog( "close" );
}

function check_write() {
	if ($('#btitle').val() == '') {
		alert('제목을 입력하여 주십시오.');
		$('#btitle').val();
		return;
	}
	if ($('#dates').val() == '') {
		alert('시작일을 지정하여 주십시오.');
		return;
	}
	if ($('#datee').val() == '') {
		alert('종료일을 지정하여 주십시오.');
		return;
	}
	if ($('#fupload').val() != '') {
		var extension = $('#fupload').val().substring(($('#fupload').val().lastIndexOf('.') + 1), $('#fupload').val().length);
		extension = extension.toLowerCase();
		
		if (regext.test(extension) == false) {
			alert('첨부 가능한 파일이 아닙니다.\n\n다시 확인하여 주십시오.');
			return;
		}
	}
	editor[0].updateElement();
	if ($('#contents1').val() == "") {
		alert('내용을 입력하여 주십시오.');
		return;
	}
	
	$("#dataform").submit();
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
		$.post( "/community/delete_popup_select", { record: vRecords })
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
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_6_2"><img src="/app/views/images/common/img_subtitle6_2.gif" alt="ADMIN" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">ADMIN</a> <span>&gt;</span> <strong>팝업관리</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbslistbox1"><input type="hidden" id="now_page" value="1" />
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="7%" />
                        <col />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="20%" />
                        <col width="10%" />
                        </colgroup>
                        <tr>
                            <th class="tl"><input type="checkbox" id="all_record_select" onclick="checkbox_checkall();" /></th>
                            <th>No.</th>
                            <th>제목</th>
                            <th>시작일</th>
                            <th>종료일</th>
                            <th>등록자</th>
                            <th class="tr">등록일(수정일)</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" id="btn_deleteitem" class="">삭제</button></div>
                    <div class="btntype2"><button type="button" id="btn_diapup155" class="st2" onclick="go_write();">등록</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup153">
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
            	<colgroup>
                <col width="100" />
                </colgroup>
                <tr>
                    <th>제목</th>
                    <td colspan="3"><strong><span id="area_01"></span></strong></td>
                </tr>
                <tr>
                    <th>시작일</th>
                    <td><span id="area_06"></span></td>
                    <th>종료일</th>
                    <td><span id="area_07"></span></td>
                </tr>
                <tr>
                    <th>작성자</th>
                    <td><span id="area_02"></span></td>
                    <th>작성일</th>
                    <td><span id="area_03"></span></td>
                </tr>
                <tr>
                    <th>첨부파일</th>
                    <td colspan="3"><span id="area_04"></span></td>
                </tr>
                <tr>
                    <td colspan="4"><p style="text-align:left;" id="area_05"></p></th>
                </tr>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st1" id="abtn_diapup153">확인</button> <button type="button" class="st3" id="mbtn_diapup153" onclick="go_modify();">수정</button> <button type="button" class="st5" id="mbtn_diapup177" onclick="go_delete();">삭제</button> <button type="button" id="cbtn_diapup153" class="" onclick="close_view()">창 닫기</button></div>
        </dd>
    </dl>
</div>

<div id="diapup155">
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
            	<colgroup>
                <col width="100" />
                </colgroup>
                <form id="dataform" name="dataform" method="post" enctype="multipart/form-data">
                <tr><input type="hidden" name="bseq" id="bseq" value="">
                    <th>제목</th>
                    <td colspan="3"><input class="inp1" id="btitle" name="btitle" value="" /></td>
                </tr>
                <tr>
                    <th>시작일</th>
                    <td><input class="inp2" name="dates" id="dates" readonly="readonly" value="" /> <img src="/app/views/images/common/icon_calen.gif" onclick="calendarclick_event('dates');" /></td>
                    <th>종료일</th>
                    <td><input class="inp2" name="datee" id="datee" readonly="readonly" value="" /> <img src="/app/views/images/common/icon_calen.gif" onclick="calendarclick_event('datee');" /></td>
                </tr>
                <tr>
                    <th>작성자</th>
                    <td><span id="warea_01"></span></td>
                    <th>작성일</th>
                    <td><span id="warea_02"></span></td>
                </tr>
                <tr>
                    <th>첨부파일</th>
                    <td colspan="3"><input type="file" class="file1" name="fupload" id="fupload" /><span id="warea_03"></span></td>
                </tr>
                <tr>
                    <td colspan="4"><textarea class="ckeditor" id="contents1" name="contents1" cols="100%" rows="8"></textarea></th>
                </tr>
                </form>
                <input type="hidden" name="nwriter" id="nwriter" value="<?php echo $session_id; ?>"><input type="hidden" name="ndate" id="ndate" value="<?php echo date('Y-m-d');?>">
            </table><input type="hidden" id="jdata" name="jdata" />
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup155" onclick="check_write();">저장</button> <button type="reset" class="st4">다시쓰기</button> <button type="button" id="cbtn_diapup155" class="">취소</button></div>
        </dd>
    </dl>
</div>


<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>