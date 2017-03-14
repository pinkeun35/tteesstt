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
			url: '/admin/upload_excel_lcienergy',
			type: 'POST',
			data:  formData,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data, textStatus, jqXHR) {
				$( "#diapup160" ).dialog( "close" );
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

function check_data() {
	if ($('#ename').val() == "") {
		alert('에너지원을 입력하세요.');
		$('#ename').focus();
		return;
	}
	if ($('#ecaloric').val() == "") {
		alert('발열량을 입력하세요.');
		$('#ecaloric').focus();
		return;
	}
	if ($('#eco2').val() == "") {
		alert('Co2배출량을 입력하세요.');
		$('#eco2').focus();
		return;
	}
	if ($('#eunit').val() == "") {
		alert('기준단위 입력하세요.');
		$('#eunit').focus();
		return;
	}
	if ($('#ecul').val() == "") {
		alert('자료출처를 입력하세요.');
		$('#ecul').focus();
		return;
	}

	var alert_text = "입력";
	var action_url = "/admin/lcidb_insertenergy";
	var reload_page = "1";
	if ($('#sseq').val() != "") {
		alert_text = "수정";
		action_url = "/admin/lcidb_updateenergy";
		reload_page = $('#now_page').val();
	}
	if (confirm('해당 내역을 ' + alert_text + '하시겠습니까?')) {
		var jrecycle = "0";
		if ($('#jrecycle2').is(':checked') == true) {
			jrecycle = "1";
		}
		$.post( action_url, {
			ename: $('#ename').val(),
			ecaloric: $('#ecaloric').val(),
			eco2: $('#eco2').val(),
			esb: $('#esb').val(),
			ecfc: $('#ecfc').val(),
			eso: $('#eso').val(),
			epo: $('#epo').val(),
			ehch: $('#ehch').val(),
			eho: $('#eho').val(),
			eunit: $('#eunit').val(),
			ecul: $('#ecul').val(),
			eupdate: $('#eupdate').val(),
			sseq: $('#sseq').val()
			})
			.done(function( data ) {
				if (data.status == "success") {
				//	alert('자료 ' + alert_text + '에 성공하였습니다.');
					form_reset();
					$( "#diapup159" ).dialog( "close" );

					list_reload(reload_page);
				}
				else if (data.status == "fail") {
					alert('자료 ' + alert_text + '에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function form_reset() {
	var now = new Date();
	var year= now.getFullYear();
	var mon = (now.getMonth()+1)>9 ? ''+(now.getMonth()+1) : '0'+(now.getMonth()+1);
	var day = now.getDate()>9 ? ''+now.getDate() : '0'+now.getDate();
	var chan_val = year + '-' + mon + '-' + day;

	$('#sseq').val('');
	$('#ename').val('');
	$('#ecaloric').val('');
	$('#jco2').val('');
	//$('#jsb').val('');
	//$('#jcfc').val('');
	//$('#jso').val('');
	//$('#jpo').val('');
	//$('#jhch').val('');
	//$('#jho').val('');
	$('#junit').val('');
	$('#jcul').val('');
	$('#jupdate').val('');
	$('#area_writertitle').text('등록자');
	$('#area_writer').text('<?php echo $session_id; ?>');
	$('#area_date').text(chan_val);
}

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/admin/get_lcienergy_list", { page: vPage })
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
						tr_html += '<td><a href="javascript:get_record(' + jdata.item[key].seq + ');" class="btn_diapup158">' + jdata.item[key].ename + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].ecaloric + '</td>';
						//tr_html += '<td class="c">' + jdata.item[key].eco2 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].eunit + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].eculceo + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].wdate.substring(0, 10) + '</td>';
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

function get_record(vRecord) {
	$('#sseq').val(vRecord);
	$.post( "/admin/get_lcienergy_record", { record: vRecord })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$( "#diapup159" ).dialog( "open" );

				$('#ename').val(jdata.item.ename);
				$('#ecaloric').val(jdata.item.ecaloric);
				$('#eco2').val(jdata.item.eco2);
				$('#esb').val(jdata.item.esb);
				$('#ecfc').val(jdata.item.ecfc);
				$('#eso').val(jdata.item.eso);
				$('#epo').val(jdata.item.epo);
				$('#ehch').val(jdata.item.ehch);
				$('#eho').val(jdata.item.eho);
				$('#eunit').val(jdata.item.eunit);
				$('#ecul').val(jdata.item.eculceo);
				$('#eupdate').val(jdata.item.eupdate);
				$('#area_writertitle').text('등록자');
				$('#area_writer').text(jdata.item.wdate);
				$('#area_date').text(jdata.item.substring(0, 10));

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
		$.post( "/admin/lcidb_deleteenergy", { record: vRecords })
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
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_6_3"><img src="/app/views/images/common/img_subtitle6_3.gif" alt="ADMIN" /></span></h2>

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">ADMIN</a> <span>&gt;</span> <strong>LCI DB관리</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">

            <dl id="adminlisttapmenu" class="adminlisttapmenu4">
            	<dd class="menu1"><a href="/admin/lcidb_listjajae">자재</a></dd>
                <dd class="menu4"><a href="#;" class="strong">Energy</a></dd>
                <dd class="menu5"><a href="/admin/lcidb_listmachine">장비</a></dd>
            </dl>

            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbslistbox1"><input type="hidden" id="now_page" value="1" />
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="7%" />
                        <col width="*" />
                        <!--<col width="10%" />-->
                        <col width="10%" />
                        <col width="7%" />
                        <col width="11%" />
                        <col width="11%" />
                        </colgroup>
                        <tr>
                            <th class="tl"><input type="checkbox" id="all_record_select" onclick="checkbox_checkall();" /></th>
                            <th>No.</th>
                            <th>에너지원</th>
                            <th>발열량</th>
                            <!--<th>Co2배출량</th>-->
                            <th>기준단위</th>
                            <th>출처</th>
                            <th class="tr">등록일(수정일)</th>
                        </tr>
                        <!-- <tr class="mobg">
                            <td class="c"><input type="checkbox" /></td>
                            <td class="c"><%=i%></td>
                            <td><a href="#;" class="btn_diapup161">콘크리트</a></td>
                            <td class="c">5012</td>
                            <td class="c">5500</td>
                            <td class="c">㎏</td>
                            <td class="c">50%</td>
                            <td class="c">2014-12-30</td>
                        </tr> -->
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" id="btn_deleteitem" class="">삭제</button></div>
                    <div class="btntype2"><button type="button" id="btn_diapup159" class="st2">등록</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
        </div>
        <!--//본문 -->

    </div>
</div>

<div id="diapup159">
	<p><strong>직접등록</strong> &nbsp; |  &nbsp; <a href="#;" id="obtn_diapup159">Excel등록</a></p>
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="20%" />
                <col width="30%" />
                <col width="20%" />
                <col width="30%" />
                </colgroup>
                <form id="dataform" name="dataform" method="post">
                <tr>
                    <th>에너지원</th>
                    <td><input class="inp1" name="ename" id="ename" maxlength="50" value="" /></td>
                </tr><input type="hidden" id="sseq" value="" />
                <tr>
                    <th>발열량</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="ecaloric" id="ecaloric" maxlength="15" value="" /></td>
                </tr>
								<tr>
                    <th>기준단위</th>
                    <td><input class="inp1" name="eunit" id="eunit" maxlength="10" value="" /></td>
                </tr>
                 <tr>
                    <th>자원소모(KgSb)</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="esb" id="esb" maxlength="15" value="" /></td>
                </tr>
								<tr>
                    <th>지구온난화(Kgco2)</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="eco2" id="eco2" maxlength="15" value="" /></td>
                </tr>
                 <tr>
                    <th>오존층영향(KgCFC11)</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="ecfc" id="ecfc" maxlength="15" value="" /></td>
                </tr>
                 <tr>
                    <th>산성화(KgSO2)</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="eso" id="eso" maxlength="15" value="" /></td>
                </tr>
                 <tr>
                    <th>부영양화(KgPO4 3-)</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="epo" id="epo" maxlength="15" value="" /></td>
                </tr>
                 <tr>
                    <th>광화학적산화물 생성(KgC2H4)</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="ehch" id="ehch" maxlength="15" value="" /></td>
                </tr>
                 <tr>
                    <th>물발자국(Kg H2O)</th>
                    <td><input class="inp1" numberdecimalOnly="true" name="eho" id="eho" maxlength="15" value="" /></td>
                </tr>
                <tr>
                    <th>출처</th>
                    <td><input class="inp1" name="ecul" id="ecul" maxlength="50" value="" /></td>
                </tr>
                <tr>
                    <th>자료갱신</th>
                    <td><input class="inp1" name="eupdate" id="eupdate" maxlength="50" value="" /></td>
                </tr>
                <tr>
                    <th><span id="area_writertitle">등록자</span></th>
                    <td><span id="area_writer"></span></td>
                </tr>
                <tr>
                    <th>등록일</th>
                    <td><span id="area_date"></span></td>
                </tr>
                </form>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup159" onclick="check_data();">등록</button> <button type="reset" class="st4">다시쓰기</button> <button type="button" id="cbtn_diapup159" class="">취소</button></div>
        </dd>
    </dl>
</div>

<div id="diapup160">
	<p><a href="#;" id="obtn_diapup160">직접등록</a> &nbsp; |  &nbsp; <strong>Excel등록</strong></p>
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
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup160" onclick="verify_excel();">등록</button> <button type="button" id="cbtn_diapup160" class="">취소</button></div>
        </dd>
    </dl>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>
