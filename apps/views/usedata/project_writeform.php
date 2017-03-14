<?php
	include_once(APPPATH.'/views/header.php');
	
	$cid_text = "";
	$cname_text = "";
	if (isset($param['eid'])) {
		if ($job_flag == "insert" && $param['eid'] == "") {
			$cid_text = $session_id;
			$cname_text = $this->session->userdata('lcco2_name');
		}
	}
	if (isset($data[1])) {
		$cid_text = $data[1]->charge_id;
		$cname_text = $data[1]->charge_name;
	}
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

<script src="/app/views/js/CKeditor/ckeditor.js"></script>

<script type="text/javascript">
<!--
var editor = [ ];
$(function() {
	$(document).on("keyup", "input:text[numberdecimalOnly]", function() {$(this).val( $(this).val().replace(/[^0-9.]/gi,"") );});
	
	$("#dates").datepicker();
	$("#dates").datepicker("option", "dateFormat", "yy-mm-dd");
	$("#datee").datepicker();
	$("#datee").datepicker("option", "dateFormat", "yy-mm-dd");
<?php
	if (isset($data[0])) {
		echo '	$("#dates").datepicker("setDate","'.$data[0]->date_start.'");'."\n";
		echo '	$("#datee").datepicker("setDate","'.$data[0]->date_end.'");'."\n";
	}
?>
	
	editor[1] = CKEDITOR.replace( 'contents1',{
		enterMode:'2',
		shiftEnterMode:'3'
	});
	
	$("#btn_selectitem").click(function() {
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
			select_record(selected_item);
		}
	});

});

function form_reset() {
	$("#search_list tr:not(:first)").remove();
	$('#sname').val('');
	$('#now_page').val('1');
}

function search_member() {
	if ($('#sname').val() == '') {
		alert('검색을 위한 담당자명을 입력하여 주십시오.');
		$('#sname').focus();
		return;
	}

	$('#now_page').val('1');
	$.post( "/member/employer_find", { sname: $('#sname').val(), group: '<?php echo $session_group; ?>', page: $('#now_page').val() })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$("#search_list tr:not(:first)").remove();
				if ( jdata.item.length > 0 ) {
					var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);
					
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c"><input type="checkbox" name="cbxseq" value="' + jdata.item[key].uid + '|' + jdata.item[key].uname + '" /></td>';
						tr_html += '<td class="c">' + jdata.item[key].uname + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].utel1 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].uid + '</td>';
						tr_html += '</tr>';
						
						$("#search_list").append( tr_html );
						
						line_num--;
					});
					
					page_navigation(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="4">검색된 내역이 없습니다.</td>';
					tr_html += '</tr>';
					
					$("#search_list").append( tr_html );
				}
			}
			else if (jdata.status == "fail") {
				$("#search_list tr:not(:first)").remove();
				
				var tr_html = '';
				tr_html += '<tr class="mobg">';
				tr_html += '<td class="c" colspan="4">회원 정보 검색에 실패하였습니다.</td>';
				tr_html += '</tr>';
				
				$("#search_list").append( tr_html );
			}
  		}, "json");
}

function select_record(vRecords) {
	// var temp_name = $('#cname').val();
	// var temp_id = $('#cid').val();
	var temp_name = "";
	var temp_id = "";

	var record_arr = vRecords.split(",");
	for (var i=0; i<record_arr.length; i++) {
		var loop_arr = record_arr[i].split("|");

		if (temp_id == "") {
			temp_id = loop_arr[0];
			temp_name = loop_arr[1];
		} else {
			temp_id += "," + loop_arr[0];
			temp_name += "," + loop_arr[1];
		}
	}

	var id_arr = temp_id.split(",");
	var name_arr = temp_name.split(",");
	
	temp_id = "";
	temp_name = "";
	for (var i=0; i<id_arr.length; i++) {
		var dup_cnt = 0;
		
		// alert('i : ' + i + ', k : ' + k);
		for (var k=(i+1); k<id_arr.length; k++) {
			// alert('i : ' + i + ', k : ' + k);
			
			if (id_arr[i] == id_arr[k])
				dup_cnt++;
		}
		
		if (dup_cnt == 0) {
			if (temp_id == "") {
				temp_id = id_arr[i];
				temp_name = name_arr[i];
			} else {
				temp_id += ',' + id_arr[i];
				temp_name += ',' + name_arr[i];
			}
		}
	}

	$('#cname').val(temp_name);
	$('#cid').val(temp_id);
	
	$( "#diapup107" ).dialog( "close" );
}

function check_data() {
	if ($('#pname').val() == "") {
		alert('프로젝트명을 입력하세요.');
		$('#pname').focus();
		return;
	}
	if ($('#plocation').val() == "") {
		alert('위치(주소지)를 입력하세요.');
		$('#plocation').focus();
		return;
	}
	if ($('#dates').val() == "") {
		alert('시작일을 입력하세요.');
		$('#dates').focus();
		return;
	}
	if ($('#datee').val() == "") {
		alert('완공(예정)일을 입력하세요.');
		$('#datee').focus();
		return;
	}
	if ($('#cname').val() == "") {
		alert('담당자를 선택하세요.');
		return;
	}
	if ($('#parea').val() == "") {
		alert('면적을 입력하세요.');
		$('#parea').focus();
		return;
	}
	if ($('#drefuse').val() == "") {
		alert('폐기장까지의 거리를 입력하세요.');
		$('#drefuse').focus();
		return;
	}
	if ($('#drecycle').val() == "") {
		alert('재활용장까지의 거리를 입력하세요.');
		$('#drecycle').focus();
		return;
	}
	editor[1].updateElement();
	if ($('#contents1').val() == "") {
		alert('설명을 입력하세요.');
		$('#contents1').focus();
		return;
	}
	
	var action_url = "<?php echo $save_url; ?>";
	if (confirm('해당 내역을 저장하시겠습니까?')) {
		$.post( action_url, {
			pname: $('#pname').val(),
			plocation: $('#plocation').val(),
			dates: $('#dates').val(),
			datee: $('#datee').val(),
			cid: $('#cid').val(),
			cname: $('#cname').val(),
			parea: $('#parea').val(),
			drefuse: $('#drefuse').val(),
			drecycle: $('#drecycle').val(),
			contents1: $('#contents1').val(),
			charge_id: $('#charge_id').val()
			})
			.done(function( data ) {
				if (data.status == "success") {
					alert('자료 저장에 성공하였습니다.');
					document.location = "<?php echo $return_url; ?>";
				} else if (data.status == "fail") {
					alert('자료 저장에 싫패하였습니다.');
				}
	  		}, "json");
	}
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_2_1"><img src="/app/views/images/common/img_subtitle2_1.gif" alt="USE DATA" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">USE DATA</a> <span>&gt;</span> <strong>내 프로젝트</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">

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
                            <th>프로젝트명</th>
                            <td colspan="3"><input class="inp1" name="pname" id="pname" maxlength="50" value="<?php if (isset($data[0])) { echo $data[0]->prjname; }?>" /></td>
                        </tr><input type="hidden" id="sseq" value="" />
                        <tr>
                            <th>위치</th>
                            <td colspan="3"><input class="inp1" name="plocation" id="plocation" maxlength="50" value="<?php if (isset($data[0])) { echo $data[0]->location; }?>" /></td>
                        </tr>
                        <tr>
                            <th>시작일</th>
                            <td><input class="inp2" name="dates" id="dates" readonly="readonly" value="" /> <img src="/app/views/images/common/icon_calen.gif" onclick="calendarclick_event('dates');" /></td>
                            <th>완공(예정)일</th>
                            <td><input class="inp2" name="datee" id="datee" readonly="readonly" value="" /> <img src="/app/views/images/common/icon_calen.gif" onclick="calendarclick_event('datee');" /></td>
                        </tr>
                        <tr>
                            <th>담당자</th>
                            <td><input class="inp2" name="cname" id="cname" readonly="readonly" value="<?php echo $cname_text; ?>" /> <?php if ( (isset($param['eid']) && $param['eid'] != "") || (isset($data[1]) && $param['gubun'] == "e") ) { ?><button id="btn_diapup107" class="st2">직원검색</button><?php } ?><input type="hidden" id="cid" name="cid" value="<?php echo $cid_text; ?>"><input type="hidden" id="charge_id" value="<?php if (isset($data[0]) && $param['gubun'] == "e") { echo $data[0]->uid; }?>" /></td>
                            <th>면적</th>
                            <td><input class="inp2" numberdecimalOnly="true" name="parea" id="parea" maxlength="15" value="<?php if (isset($data[0])) { echo $data[0]->area; }?>" /> ㎡</td>
                        </tr>
                        <tr>
                            <th>폐기장까지의 거리</th>
                            <td><input class="inp2" numberdecimalOnly="true" name="drefuse" id="drefuse" maxlength="15" value="<?php if (isset($data[0])) { echo $data[0]->distance_refuse;}?>" /> ㎞</td>
                            <th>재활용장까지의 거리</th>
                            <td><input class="inp2" numberdecimalOnly="true" name="drecycle" id="drecycle" maxlength="15" value="<?php if (isset($data[0])) { echo $data[0]->distance_recycle; }?>" /> ㎞</td>
                        </tr>
                        <tr>
                            <td colspan="4"><textarea class="ckeditor" id="contents1" name="contents1" cols="100" rows="10"><?php if (isset($data[0])) { echo $data[0]->contents; }?></textarea></td>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
<?php
	if (isset($data[1])) {
?>
                    <div class="btntype3"><button type="button" class="st2" onclick="check_data();">변경</button> <button type="button" class="" onclick="document.location='<?php echo $return_url; ?>';">취소</button></div>
<?php
	} else {
?>
                    <div class="btntype3"><button type="button" class="st2" onclick="check_data();">프로젝트 생성</button> <button type="button" class="st3">다시쓰기</button> <button type="button" class="" onclick="document.location='<?php echo $return_url; ?>';">취소</button></div>
<?php
	}
?>
                </dd>
            </dl>
        
    </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup107">
	<p><input class="inp1" id="sname" name="sname" /> <button class="st5" onclick="search_member();">검색</button></p>
    <div id="">
        <dl id="" class="tablebox1">
            <dd class="bbslistbox1"><input type="hidden" id="now_page" value="1" />
                <table id="search_list" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                    <colgroup>
                    <col width="10%" />
                    <col width="25%" />
                    <col width="30%" />
                    <col width="30%" />
                    </colgroup>
                    <tr>
                        <th class="tl">선택</th>
                        <th>이름</th>
                        <th>연락처</th>
                        <th class="tr">이메일</th>
                    </tr>
                    <!-- <tr class="mobg">
                        <td class="c"><input type="checkbox" /></td>
                        <td class="c">홍길동</td>
                        <td class="c">010-9876-5432</td>
                        <td class="c">adb@fka.com</td>
                    </tr> -->
                </table>
            </dd>
            <dd class="bbsbtnbox1">
                <div class="btntype2"><button type="button" id="btn_selectitem" class="st1">담당자추가</button></div>
                <div class="pageing" id="area_paging"></div>
            </dd>
        </dl>
        </div>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>