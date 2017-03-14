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
var gonggu_insert = '<button type="button" id="abtn_diapup110" class="st1" onclick="check_gonggu_insert();">공구등록</button> <button type="button" id="cbtn_diapup110" class="" onclick="close_record();">취소</button>';
var gonggu_modify = '<button type="button" class="st2" id="abtn_diapup110" onclick="check_gonggu_insert();">수정</button> <button type="button" id="cbtn_diapup110" class="" onclick="close_record();">취소</button> <button type="button" id="dbtn_diapup112" class="st5" onclick="check_gonggu_delete($(\'#sseq\').val());">삭제</button>';
var building_insert = '<button type="button" class="st3" id="abtn_diapup115" onclick="check_building_insert();">저장</button> <button type="button" id="cbtn_diapup115" class="" onclick="close_record_building();">취소</button>';
var building_modify = '<button type="button" class="st2" id="abtn_diapup115" onclick="check_building_insert();">수정</button> <button type="button" id="cbtn_diapup118" class="st5" onclick="close_record_building();">취소</button> <!-- <button type="button" id="gbtn_diapup118" class="st4">자재입력</button> -->';

$(function() {
	$(document).on("keyup", "input:text[numberdecimalOnly]", function() {$(this).val( $(this).val().replace(/[^0-9.]/gi,"") );});
	
	$("#dates").datepicker();
	$("#dates").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#datee").datepicker();
	$("#datee").datepicker("option", "dateFormat", "yy-mm-dd");

	$("#bdates").datepicker();
	$("#bdates").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#bdatee").datepicker();
	$("#bdatee").datepicker("option", "dateFormat", "yy-mm-dd");
	
	editor[0] = CKEDITOR.replace( 'contents1',{
		enterMode:'2',
		shiftEnterMode:'3'
	});
	
	editor[1] = CKEDITOR.replace( 'contents2',{
		enterMode:'2',
		shiftEnterMode:'3',
		toolbar:
	    [
			['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
			['Cut','Copy','Paste','PasteText','PasteFromWord'],
			['Undo','Redo','SelectAll','RemoveFormat'],
			['NumberedList','BulletedList','-','Outdent','Indent','Blockquote']
	    ]
	});
	
	get_gonggu_list();

	
	$("#excelform").submit(function(e)
	{
		var formObj = $(this);
		var formURL = formObj.attr("action");
		var formData = new FormData(this);
		$.ajax({
			url: '/usedata/upload_excel_building',
			type: 'POST',
			data:  formData,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data, textStatus, jqXHR) {
				$( "#diapup116" ).dialog( "close" );
				get_gonggu_list();
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

function form_reset_gonggu_writeform() {
	$('#gname').val('');
	$('#dates').val('');
	$('#datee').val('');
	$('#cname').val('');
	$('#garea').val('');
	$('#contents1').val('');
	
	$("#dates").datepicker("setDate","");
	$("#datee").datepicker("setDate","");
	editor[0].setData('');
	editor[0].updateElement();
	
	$("#diapup110").dialog({
        title: '공구추가'
    });
    $('#area_gonggu_btn').html(gonggu_insert);
}

function form_reset_building_writeform() {
	$('#bname').val('');
	$('#blocation').val('');
	$('#blife').val('');
	$('#bscale').val('');
	$('#btype').val('A');
	$('#bcname').val('');
	$('#contents1').val('');
	
	$("#bdates").datepicker("setDate","");
	$("#bdatee").datepicker("setDate","");
	editor[1].setData('');
	editor[1].updateElement();
	
	$("#diapup115").dialog({
        title: '건축물정보등록(개별등록)'
    });

	$('#area_building_btn').html(building_insert);
}

function check_gonggu_insert() {
	if ($('#gname').val() == "") {
		alert('공구명을 입력하세요.');
		$('#gname').focus();
		return;
	}
	if ($('#dates').val() == "") {
		alert('시작일을 선택하여 주세요.');
		$('#dates').focus();
		return;
	}
	if ($('#datee').val() == "") {
		alert('종료(예정)일을 선택하여 주세요.');
		$('#datee').focus();
		return;
	}
	if ($('#cname').val() == "") {
		alert('담당자를 입력하세요.');
		$('#cname').focus();
		return;
	}
	if ($('#garea').val() == "") {
		alert('면적을 선택하세요.');
		$('#garea').focus();
		return;
	}
	editor[0].updateElement();
	if ($('#contents1').val() == "") {
		alert('내용을 입력하세요.');
		return;
	}
	
	var action_url = "/usedata/insert_zone/<?php echo $this->uri->assoc_to_uri($param); ?>";
	if ($('#sseq').val() != '') {
		action_url = "/usedata/update_zone/<?php echo $this->uri->assoc_to_uri($param); ?>";
	}
	if (confirm('해당 내역을 저장하시겠습니까?')) {
		$.post( action_url, {
			gname: $('#gname').val(),
			dates: $('#dates').val(),
			datee: $('#datee').val(),
			cname: $('#cname').val(),
			garea: $('#garea').val(),
			contents1: $('#contents1').val(),
			seq: $('#sseq').val()
			})
			.done(function( data ) {
				if (data.status == "success") {
				//	alert('자료 저장에 성공하였습니다.');
					$( "#diapup110" ).dialog( "close" );
					
					get_gonggu(data.record);
				} else if (data.status == "fail") {
					alert('자료 저장에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function get_gonggu_list() {
	$.post( '/usedata/get_zone_list/<?php echo $this->uri->assoc_to_uri($param); ?>', {  })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$("#area_zone dd").remove();
				$("select[name='gonggu'] option").remove();
				$("select[name='gonggu2'] option").remove();
				
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var dd_html = '';
						dd_html += '<dd>';
						dd_html += '<table id="area_building_' + jdata.item[key].seq + '" class="tableviewtype5" border="0" cellpadding="0" cellspacing="0">';
						dd_html += '<colgroup>';
						dd_html += '<col width="15%" />';
						dd_html += '<col width="30%" />';
						dd_html += '<col width="15%" />';
						dd_html += '<col width="20%" />';
						dd_html += '<col width="20%" />';
						dd_html += '</colgroup>';
						dd_html += '<tr>';
					//	dd_html += '<th colspan="4">1공구<button  class="mbtn_diapup111">공구수정</button> <button class="btn_diapup115">건물설정</button></th>';
						dd_html += '<th colspan="5">' + jdata.item[key].zone_name + '<button class="" onclick="get_gonggu_modify(' + jdata.item[key].seq + ');">공구수정</button> <button class="" onclick="pop_building_insert(<?php echo $param['seq'];?>,' + jdata.item[key].seq + ');">건물설정</button></th>';
						dd_html += '</tr>';
						if (jdata.item[key].building != "") {
							var barr = jdata.item[key].building.split(",");
							
							for (var i=0; i<barr.length; i++) {
								var building_data = barr[i].split("|");
								dd_html += '<tr>';
								dd_html += '<td>' + building_data[1] + '</td>';
								dd_html += '<td>' + building_data[2] + '</td>';
								dd_html += '<td>' + building_data[3] + '</td>';
								dd_html += '<td>' + building_data[4] + '</td>';
								dd_html += '<td class="tr"><button  class="" onclick="get_building_modify(' + building_data[0] + ')">건물수정</button> <button class="" onclick="check_building_delete(' + building_data[0] + ');">건물삭제</button></td>';
								dd_html += '</tr>';
							}
						} else {
							dd_html += '<tr>';
							dd_html += '<td colspan="5" class="tr">등록된 건물 내역이 없습니다.</td>';
							dd_html += '</tr>';
						}
						dd_html += '</table>';
						dd_html += '</dd>';
						
						$("#area_zone").append( dd_html );
						
						$("<option></option>").attr("selected", false).text(jdata.item[key].zone_name).attr("value", jdata.item[key].seq).appendTo("select[name='gonggu']");
						$("<option></option>").attr("selected", false).text(jdata.item[key].zone_name).attr("value", jdata.item[key].seq).appendTo("select[name='gonggu2']");
					});
				} else {
					var dd_html = '';
					
					$("#area_zone").append( dd_html );
				}
			} else if (data.status == "fail") {
				alert('자료 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function get_gonggu(vSeq) {
	$.post( '/usedata/get_zone_record/<?php echo $this->uri->assoc_to_uri($param); ?>', { record: vSeq })
		.done(function( data ) {
			if (data.status == "success") {
				print_gonggu(vSeq, data.item);
	
				$('#sseq').val(vSeq);
				$('#record_json').val(JSON.stringify(data.item));
			} else if (data.status == "fail") {
				alert('자료 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function get_gonggu_modify(vSeq) {
	$.post( '/usedata/get_zone_record/<?php echo $this->uri->assoc_to_uri($param); ?>', { record: vSeq })
		.done(function( data ) {
			if (data.status == "success") {
				$('#sseq').val(vSeq);
				$('#record_json').val(JSON.stringify(data.item));
				
				pop_modify();
			} else if (data.status == "fail") {
				alert('자료 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function print_gonggu(vSeq, vData) {
	$('#a_gname').text(vData.zone_name);
	$('#a_dates').text(vData.date_start);
	$('#a_datee').text(vData.date_end);
	$('#a_cname').text(vData.cname);
	$('#a_garea').text(vData.area);
	$('#a_contents1').html(vData.contents);
	
	$( "#diapup111" ).dialog( "open" );
}

function pop_modify() {
	$( "#diapup111" ).dialog( "close" );
	
	var jdata = jQuery.parseJSON($('#record_json').val());

	$('#gname').val(jdata.zone_name);
	$('#cname').val(jdata.cname);
	$('#garea').val(jdata.area);
	$('#contents1').val(jdata.contents);
	
	$("#dates").datepicker("setDate",jdata.date_start);
	$("#datee").datepicker("setDate",jdata.date_end);
	editor[0].setData($('#contents1').val());
	editor[0].updateElement();
	
	$("#diapup110").dialog({
        title: '공구상세정보 변경'
    });
    $('#area_gonggu_btn').html(gonggu_modify);

	$( "#diapup110" ).dialog( "open" );
}

function close_record() {
	$('#sseq').val('');
	$('#record_json').val('');
	
	form_reset_gonggu_writeform();
	
	get_gonggu_list();
    
    $( "#diapup110" ).dialog( "close" );
    $( "#diapup111" ).dialog( "close" );
}

function check_gonggu_delete(vSeq) {
	if (confirm("해당 공구 내역을 삭제하시겠습니까?\n\n삭제하시면 등록된 모든 관련정보도 삭제됩니다.")) {
		$.post( '/usedata/delete_zone_record/<?php echo $this->uri->assoc_to_uri($param); ?>', { record: vSeq })
			.done(function( data ) {
				if (data.status == "success") {
					close_record();
				} else if (data.status == "fail") {
					alert('자료 삭제에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function pop_building_insert(vProject, vGonggu) {
	form_reset_building_writeform();
	$( "#diapup115" ).dialog( "open" );
}

function check_building_insert() {
	if ($('#bname').val() == "") {
		alert('건물명을 입력하세요.');
		$('#bname').focus();
		return;
	}
	if ($('#blocation').val() == "") {
		alert('위치를 입력하여 주세요.');
		$('#blocation').focus();
		return;
	}
	if ($('#bdates').val() == "") {
		alert('시작일을 선택하여 주세요.');
		$('#bdates').focus();
		return;
	}
	if ($('#bdatee').val() == "") {
		alert('종료(예정)일을 선택하여 주세요.');
		$('#bdatee').focus();
		return;
	}
	if ($('#blife').val() == "") {
		alert('예상수명을 입력하세요.');
		$('#blife').focus();
		return;
	}
	if ($('#bscale').val() == "") {
		alert('규모를 입력하세요.');
		$('#bscale').focus();
		return;
	}
	if ($('#bcname').val() == "") {
		alert('담당자를 입력하세요.');
		$('#bcname').focus();
		return;
	}
	editor[1].updateElement();
	if ($('#contents2').val() == "") {
		alert('내용을 입력하세요.');
		return;
	}
	
	var action_url = "/usedata/insert_building/<?php echo $this->uri->assoc_to_uri($param); ?>";
	if ($('#bseq').val() != '') {
		action_url = "/usedata/update_building/<?php echo $this->uri->assoc_to_uri($param); ?>";
	}
	if (confirm('해당 내역을 저장하시겠습니까?')) {
		$.post( action_url, {
			gonggu: $('#gonggu').val(),
			bname: $('#bname').val(),
			blocation: $('#blocation').val(),
			bdstart: $('#bdates').val(),
			bdend: $('#bdatee').val(),
			blife: $('#blife').val(),
			bscale: $('#bscale').val(),
			btype: $('#btype').val(),
			cname: $('#bcname').val(),
			contents2: $('#contents2').val(),
			seq: $('#bseq').val()
			})
			.done(function( data ) {
				if (data.status == "success") {
				//	alert('자료 저장에 성공하였습니다.');
					$( "#diapup115" ).dialog( "close" );
					
					get_building(data.record);
				} else if (data.status == "fail") {
					alert('자료 저장에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function get_building(vSeq) {
	$.post( '/usedata/get_building_record/<?php echo $this->uri->assoc_to_uri($param); ?>', { record: vSeq })
		.done(function( data ) {
			if (data.status == "success") {
				print_building(vSeq, data.item);
	
				$('#bseq').val(vSeq);
				$('#brecord_json').val(JSON.stringify(data.item));
			} else if (data.status == "fail") {
				alert('자료 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function get_building_modify(vSeq) {
	$.post( '/usedata/get_building_record/<?php echo $this->uri->assoc_to_uri($param); ?>', { record: vSeq })
		.done(function( data ) {
			if (data.status == "success") {
				$('#bseq').val(vSeq);
				$('#brecord_json').val(JSON.stringify(data.item));
				
				pop_modify_building();
			} else if (data.status == "fail") {
				alert('자료 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function print_building(vSeq, vData) {
	$('#a_gonggu').text(vData.zone_name);
	$('#a_bname').text(vData.bname);
	$('#a_blocation').text(vData.location);
	$('#a_bdates').text(vData.date_start);
	$('#a_bdatee').text(vData.date_end);
	$('#a_blife').text(vData.life);
	$('#a_bscale').text(vData.scale);
	$('#a_btype').text(vData.building_type);
	$('#a_bcname').text(vData.charge);
	$('#a_contents2').html(vData.contents);
	
	$( "#diapup117" ).dialog( "open" );
}

function pop_modify_building() {
	$( "#diapup117" ).dialog( "close" );
	
	var jdata = jQuery.parseJSON($('#brecord_json').val());

	$('#gonggu').val(jdata.zone_seq);
	$('#bname').val(jdata.bname);
	$('#blocation').val(jdata.location);
	$('#blife').val(jdata.life);
	$('#bscale').val(jdata.scale);
	$('#btype').val(jdata.building_type);
	$('#bcname').val(jdata.charge);
	$('#contents2').val(jdata.contents);
	
	$("#bdates").datepicker("setDate",jdata.date_start);
	$("#bdatee").datepicker("setDate",jdata.date_end);
	editor[1].setData($('#contents2').val());
	editor[1].updateElement();
	
	$("#diapup115").dialog({
        title: '건축물정보변경'
    });
    $('#area_building_btn').html(building_modify);

	// print_building($('#bseq').val(), jdata);
	$( "#diapup115" ).dialog( "open" );
}

function close_record_building() {
	$('#bseq').val('');
	$('#brecord_json').val('');
	
	form_reset_building_writeform();
	
	get_gonggu_list();
    
    $( "#diapup117" ).dialog( "close" );
    $( "#diapup115" ).dialog( "close" );
}

function check_building_delete(vSeq) {
	if (confirm("해당 건물 내역을 삭제하시겠습니까?")) {
		$.post( '/usedata/delete_building_record/<?php echo $this->uri->assoc_to_uri($param); ?>', { record: vSeq })
			.done(function( data ) {
				if (data.status == "success") {
					close_record_building();
				} else if (data.status == "fail") {
					alert('자료 삭제에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function check_delete_project() {
	$.post( '/usedata/delete_project/<?php echo $this->uri->assoc_to_uri($param); ?>', { record: 0 })
		.done(function( data ) {
			if (data.status == "success") {
				$( "#diapup108" ).dialog( "close" );
				$( "#diapup109" ).dialog( "open" );
			} else if (data.status == "fail") {
				alert('자료 삭제에 싫패하였습니다.');
			}
  		}, "json");
}

function verify_excel() {
	if ($('#gonggu2').val() == '') {
		alert('공구를 선택하여 주십시오.');
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
	
	$("#excelform").submit();
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_2_1"><img src="/app/views/images//common/img_subtitle2_1.gif" alt="USE DATA" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images//common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">USE DATA</a> <span>&gt;</span> <strong>내 프로젝트</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">

            <dl class="tablebox4_1">
                <dd>
                    <table class="tableviewtype4" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="15%" />
                        <col width="35%" />
                        <col width="15%" />
                        <col width="35%" />
                        </colgroup>
                        <tr>
                            <th>프로젝트명</th>
                            <td colspan="3"><strong><?php if (isset($data[0])) { echo $data[0]->prjname; }?></strong></td>
                        </tr>
                        <tr>
                            <th>위치</th>
                            <td colspan="3"><?php if (isset($data[0])) { echo $data[0]->location; }?></td>
                        </tr>
                        <tr>
                            <th>시작일</th>
                            <td><?php if (isset($data[0])) { echo $data[0]->date_start; }?></td>
                            <th>완공(예정)일</th>
                            <td><?php if (isset($data[0])) { echo $data[0]->date_end; }?></td>
                        </tr>
                        <tr>
                            <th>담당자</th>
                            <td><?php if (isset($data[1])) { echo $data[1]->charge_name; }?></td>
                            <th>면적</th>
                            <td><?php if (isset($data[0])) { echo $data[0]->area; }?>㎡</td>
                        </tr>
                        <tr>
                            <th>폐기장</th>
                            <td><?php if (isset($data[0])) { echo $data[0]->distance_refuse; }?>㎞</td>
                            <th>재활용장</th>
                            <td><?php if (isset($data[0])) { echo $data[0]->distance_recycle; }?>㎞</td>
                        </tr>
                        <tr>
                            <td colspan="4"><?php if (isset($data[0])) { echo stripslashes($data[0]->contents); }?></td>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype2"><button type="button" id="btn_diapup110" class="st1">공구설정</button></div>
                </dd>
            </dl>
            
            <dl class="tablebox4_1" id="area_zone">
                <!-- <dd>
                    <table class="tableviewtype5" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="15%" />
                        <col width="35%" />
                        <col width="15%" />
                        <col width="35%" />
                        </colgroup>
                        <tr>
                            <th colspan="4">1공구<button  class="mbtn_diapup111">공구수정</button> <button class="btn_diapup115">건물설정</button></th>
                        </tr>
                        <tr>
                            <td>박물관 본관</th>
                            <td>홍길도</td>
                            <td>2015.01.01</td>
                            <td class="tr">철근콘크리트</th>
                        </tr>
                        <tr>
                            <td>박물관 본관</th>
                            <td>홍길도</td>
                            <td>2015.01.01</td>
                            <td class="tr">철근콘크리트</th>
                        </tr>
                    </table>
                </dd>
                <dd>
                    <table class="tableviewtype5" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="15%" />
                        <col width="35%" />
                        <col width="15%" />
                        <col width="35%" />
                        </colgroup>
                        <tr>
                            <th colspan="4">2공구<button  class="mbtn_diapup111">공구수정</button> <button class="btn_diapup115">건물설정</button></th>
                        </tr>
                        <tr>
                            <td>박물관 본관</th>
                            <td>홍길도</td>
                            <td>2015.01.01</td>
                            <td class="tr">철근콘크리트</th>
                        </tr>
                        <tr>
                            <td>박물관 본관</th>
                            <td>홍길도</td>
                            <td>2015.01.01</td>
                            <td class="tr">철근콘크리트</th>
                        </tr>
                    </table>
                </dd> -->
            </dl>
           <dl class="tablebox4_1">
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" class="" onclick="document.location='/usedata/project_list';">목록</button> <button type="button" class="st2" onclick="document.location='/usedata/project_modify/<?php echo $this->uri->assoc_to_uri($param); ?>';">수정</button> <button id="btn_diapup108" type="button" class="st5">삭제</button></div>
                    <div class="btntype2"><!-- <button type="button" class="st1">계산수행</button> <button type="button" class="st3">통계보기</button> --></div>
                </dd>
            </dl>

        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup108">
	<p>프로젝트를 삭제하면 설정된 공구, 건물정보 및 건설재료, 에너지등<br />모든 관련정보가 삭제되고, 이후 복구되지 않습니다.<br /><strong>그래도 삭제하시겠습니까?</strong><br /><br />
    <button type="button" id="dbtn_diapup108" class="st1" onclick="check_delete_project();">삭제하기</button> <button type="button" class="" id="cbtn_diapup108">취소</button></p>
</div>

<div id="diapup109">
	<p>프로젝트의 모든정보가 삭제 되었습니다.<br /><br /><button type="button" class="" id="cbtn_diapup109" onclick="document.location='/usedata/project_list';">확인</button></p>
</div>

<div id="diapup110">
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="15%" />
                <col width="35%" />
                <col width="15%" />
                <col width="35%" />
                </colgroup>
                <tr>
                    <th>공구명</th>
                    <td colspan="3"><input class="inp1" name="gname" id="gname" maxlength="50" value="" /></td>
                </tr><input type="hidden" id="sseq" value="" />
                <tr>
                    <th>시작일</th>
                    <td><input class="inp2" name="dates" id="dates" readonly="readonly" value="" /> <img src="/app/views/images/common/icon_calen.gif" onclick="calendarclick_event('dates');" /></td>
                    <th>종료(예정)일</th>
                    <td><input class="inp2" name="datee" id="datee" readonly="readonly" value="" /> <img src="/app/views/images/common/icon_calen.gif" onclick="calendarclick_event('datee');" /></td>
                </tr>
                <tr>
                    <th>담당자</th>
                    <td><input class="inp2" name="cname" id="cname" maxlength="50" value="" /></td>
                    <th>면적</th>
                    <td><input class="inp2" numberdecimalOnly="true" name="garea" id="garea" maxlength="15" value="" /> ㎡</td>
                </tr>
                <tr>
                    <td colspan="4"><textarea class="ckeditor" id="contents1" name="contents1" cols="100" rows="10"></textarea></td>
                </tr><input type="hidden" id="record_json" />
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3" id="area_gonggu_btn"></div>
        </dd>
    </dl>
</div>

<div id="diapup111">
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="15%" />
                <col width="35%" />
                <col width="15%" />
                <col width="35%" />
                </colgroup>
                <tr>
                    <th>공구명</th>
                    <td colspan="3"><span id="a_gname"></span></td>
                </tr>
                <tr>
                    <th>시작일</th>
                    <td><span id="a_dates"></span></td>
                    <th>종료(예정)일</th>
                    <td>2<span id="a_datee"></span></td>
                </tr>
                <tr>
                    <th>담당자</th>
                    <td><span id="a_cname"></span></td>
                    <th>면적</th>
                    <td><span id="a_garea"></span> ㎡</td>
                </tr>
                <tr>
                    <td colspan="4"><span id="a_contents1"></span></td>
                </tr>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st3" onclick="close_record()">확인</button> <button type="button" class="mbtn_diapup111" onclick="pop_modify()">수정</button> <button type="button" id="dbtn_diapup111" class="" onclick="check_gonggu_delete($('#sseq').val())">삭제</button></div>
        </dd>
    </dl>
</div>

<div id="diapup113">
	<p>1공구를 삭제하면 1공구에 등록된 모든건축물의 정보도 삭제됩니다..<br /><strong>1공구정보를 삭제 하시겠습니까?</strong><br /><br />
    <button type="button" id="abtn_diapup113" class="st1">삭제하기</button> <button type="button" class="" id="cbtn_diapup113">취소</button></p>
</div>

<div id="diapup114">
	<p>1공구 정보 및 1공구의<br />건축물 정보등 관련정보가 모두 삭제 되었습니다.<br /><br /><button type="button" class="" id="cbtn_diapup114">확인</button></p>
</div>

<div id="diapup115">
	<p><strong>개별등록</strong> &nbsp; |  &nbsp; <a href="#;" id="obtn_diapup115">Excel등록</a></p>
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
                    <th>공구선택</th>
                    <td colspan="3"><select id="gonggu" name="gonggu"></select></td>
                </tr><input type="hidden" id="bseq" />
                <tr>
                    <th>건물명</th>
                    <td colspan="3"><input class="inp1" id="bname" name="bname" maxlength="50" /></td>
                </tr>
                <tr>
                    <th>위치</th>
                    <td colspan="3"><input class="inp1" id="blocation" name="blocation" maxlength="50" /></td>
                </tr>
                <tr>
                    <th>시작일</th>
                    <td><input class="inp2" name="bdates" id="bdates" readonly="readonly" value="" /> <img src="/app/views/images/common/icon_calen.gif" onclick="calendarclick_event('bdates');" /></td>
                    <th>종료(예정)일</th>
                    <td><input class="inp2" name="bdatee" id="bdatee" readonly="readonly" value="" /> <img src="/app/views/images/common/icon_calen.gif" onclick="calendarclick_event('bdatee');" /></td>
                </tr>
                <tr>
                    <th>예상수명</th>
                    <td><input class="inp2" numberdecimalOnly="true" name="blife" id="blife" maxlength="4" value="" /> 년</td>
                    <th>규모</th>
                    <td><input class="inp2" numberdecimalOnly="true" name="bscale" id="bscale" maxlength="15" value="" /> ㎡</td>
                </tr>
                <tr>
                    <th>구조</th>
                    <td><select name="btype" id="btype">
                    	<option value="A">A type</option><option value="B">B type</option>
                    </select></td>
                    <th>담당자</th>
                    <td><input class="inp2" name="bcname" id="bcname" value="" /></td>
                </tr>
                <tr>
                    <td colspan="4"><textarea class="ckeditor" id="contents2" name="contents2" cols="100" rows="8"></textarea></td>
                </tr><input type="hidden" id="brecord_json" />
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3" id="area_building_btn"></div>
        </dd>
    </dl>
</div>

<div id="diapup116">
	<p><a href="#;" id="obtn_diapup116">개별등록</a> &nbsp; |  &nbsp; <strong>Excel등록</strong></p>
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
                <tr><input type="hidden" name="project2" value="<?php echo $param['seq']; ?>">
                    <th>공구선택</th>
                    <td colspan="3"><select id="gonggu2" name="gonggu2"></select></td>
                </tr>
                <tr>
                    <th>건물명</th>
                    <td colspan="3"><input type="file" class="file1" name="excelupload" id="excelupload" /></td>
                </tr>
                </form>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup157" onclick="verify_excel();">등록</button> <button type="button" id="cbtn_diapup116" class="">취소</button></div>
        </dd>
    </dl>
</div>


<div id="diapup117">
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
                    <th>공구선택</th>
                    <td colspan="3"><span id="a_gonggu"></span></td>
                </tr>
                <tr>
                    <th>건물명</th>
                    <td colspan="3"><span id="a_bname"></span></td>
                </tr>
                <tr>
                    <th>위치</th>
                    <td colspan="3"><span id="a_blocation"></span></td>
                </tr>
                <tr>
                    <th>시작일</th>
                    <td><span id="a_bdates"></span></td>
                    <th>종료(예정)일</th>
                    <td><span id="a_bdatee"></span></td>
                </tr>
                <tr>
                    <th>예상수명</th>
                    <td><span id="a_blife"></span>년</td>
                    <th>규모</th>
                    <td><span id="a_bscale"></span>㎡</td>
                </tr>
                <tr>
                    <th>구조</th>
                    <td><span id="a_btype"></span> type</td>
                    <th>담당자</th>
                    <td><span id="a_bcname"></span></td>
                </tr>
                <tr>
                    <td colspan="4"><span id="a_contents2"></span></td>
                </tr>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st1" id="abtn_diapup117" onclick="close_record_building();">확인</button> <button type="button" class="st2" id="mbtn_diapup117" onclick="pop_modify_building();">수정</button> <button type="button" id="dbtn_diapup117" class="st5" onclick="check_building_delete($('#bseq').val());">삭제</button><!-- <button type="button" id="gbtn_diapup117" class="st4">자재입력</button> --></div>
        </dd>
    </dl>
</div>

<div id="diapup118">
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
                    <th>공구선택</th>
                    <td colspan="3"><select><option>제1공구</option><option>제2공구</option></select></td>
                </tr>
                <tr>
                    <th>건물명</th>
                    <td colspan="3"><input value="박물관본관" /></td>
                </tr>
                <tr>
                    <th>위치</th>
                    <td colspan="3"><input class="inp1" value="강남" /></td>
                </tr>
                <tr>
                    <th>시작일</th>
                    <td><input class="inp2" value="2105-05-01"/> <a href="#;"><img src="/app/views/images//common/icon_calen.gif" /></a></td>
                    <th>종료(예정)일</th>
                    <td><input class="inp2" value="2105-05-01" /> <a href="#;"><img src="/app/views/images//common/icon_calen.gif" /></a></td>
                </tr>
                <tr>
                    <th>예상수명</th>
                    <td><input class="inp2" value="20" /> 년</td>
                    <th>규모</th>
                    <td><input class="inp2" value="200000" /> ㎡</td>
                </tr>
                <tr>
                    <th>구조</th>
                    <td><select><option>A type</option><option>B type</option></select></td>
                    <th>담당자</th>
                    <td><input class="inp2" value="홍길동" /></td>
                </tr>
                <tr>
                    <td colspan="4">editer</td>
                </tr>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup118">수정</button> <button type="button" id="cbtn_diapup118" class="st5">취소</button> <!-- <button type="button" id="gbtn_diapup118" class="st4">자재입력</button> --></div>
        </dd>
    </dl>
</div>

<div id="diapup119">
	<p>박물관본관 정보를 삭제하면 박물관본관에 등록된 모든 관련정보도 삭제됩니다.<br /><strong>박물관본관 정보를 삭제 하시겠습니까?</strong><br /><br />
    <button type="button" id="abtn_diapup119" class="st1">삭제하기</button> <button type="button" class="" id="cbtn_diapup119">취소</button></p>
</div>

<div id="diapup120">
	<p>박물관본관의 정보가 모두 삭제 되었습니다.<br /><br /><button type="button" class="" id="cbtn_diapup120">확인</button></p>
</div>


<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>