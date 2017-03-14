<?php
	include_once(APPPATH.'/views/header.php');
	
	$data_gubun = 0;
	if (isset($param['gubun'])) {
		$data_gubun = (int)$param['gubun'];
	}
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
	
<script type="text/javascript">
<!--
$(function() {
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
});

function check_search() {
	$('#spart').val($('#sgubun').val());
	$('#skeyword').val($('#stext').val());
	
	list_reload(1);
}

function list_reload(vPage) {
	$('#now_page').val(vPage);

	$.post( "/admin/get_member_list", {
			page: vPage,
			gubun: $('#now_gubun').val(),
			spart: $('#spart').val(),
			skeyword: $('#skeyword').val()
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
						tr_html += '<td class="c"><input type="checkbox" name="cbxseq" value="' + jdata.item[key].seq + '" /></td>';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].uid + '</a></td>';
						tr_html += '<td class="c"><a href="javascript:get_record(\'' + jdata.item[key].uid + '\');" class="btn_diapup151">' + jdata.item[key].uname + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].utel1 + '</td>';
						if (jdata.item[key].mtype == "1")
							tr_html += '<td class="c">개인</td>';
						else if (jdata.item[key].mtype == "2")
							tr_html += '<td class="c">기업</td>';
						else if (jdata.item[key].mtype == "3")
							tr_html += '<td class="c">관리자</td>';
						tr_html += '<td class="c">' + jdata.item[key].wdate + '</td>';
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
	$.post( "/admin/get_member_record", { record: vRecord })
		.done(function( jdata ) {
			$( "#diapup122" ).dialog( "close" );

			$('#pjseq').val(vRecord);
			if (jdata.status == "success") {
				make_infopop(vRecord, jdata.info, jdata.person, jdata.group);
				$( "#diapup151" ).dialog( "open" );
			}
			else if (jdata.status == "fail") {
				alert('Data 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function change_tab(vIdx) {
	if (vIdx == 1) {
		$('#tit_02').html('<a href="javascript:change_tab(1);" class="strong">상세정보</a>');
		$('#tit_03').html('<a href="javascript:change_tab(2);">Project</a>');
		$('#tit_04').show();
		$('#tit_05').hide();
		
		$('#adminlisttapmenu2').removeClass('adminlisttapmenu20');
		$('#adminlisttapmenu2').addClass('adminlisttapmenu10');
	} else if (vIdx == 2) {
		$('#tit_02').html('<a href="javascript:change_tab(1);">상세정보</a>');
		$('#tit_03').html('<a href="javascript:change_tab(2);" class="strong">Project</a>');
		$('#tit_04').hide();
		$('#tit_05').show();
		
		$('#adminlisttapmenu2').removeClass('adminlisttapmenu10');
		$('#adminlisttapmenu2').addClass('adminlisttapmenu20');
	}
}
function make_infopop(vRecord, info, person, group) {
	$('#cuid').val(vRecord);
	change_tab(1);
	
	if (info.mtype == "1") {
		$('#tit_01').html('E-Mail');
		$('#info_04').html('개인회원');
		$('#info_05').html('해당없음');
		$('#info_06').html('');
		$('#info_07').html('');
		$('#info_08').html('');
		$('#info_09').html('');
		$('#info_10').html('');
		$('#info_11').html('');
	} else if (info.mtype == "2") {
		$('#tit_01').html('아이디');
		$('#info_04').html('기업회원');
		$('#info_05').html(info.ucrn);
		$('#info_06').html('');
		$('#info_07').html(info.utel2);
		$('#info_08').html(info.cname);
		$('#info_09').html(info.ctel);
		$('#info_10').html(info.cemail);
		$('#info_11').html('');
	}
	$('#info_01').html(vRecord);
	$('#info_02').html(info.uname);
	$('#info_03').html(info.utel1);
	
	$("#project_list tr:not(:first)").remove();
	var line_cnt = 0;
	if ( person.length > 0 ) {
		$.each(person, function(key,state){
			line_cnt++;
			var tr_html = '';
			tr_html += '<tr class="mobg">';
			tr_html += '<td class="c"><input type="checkbox" disabled="disabled" /></td>';
			tr_html += '<td class="c">' + line_cnt + '</td>';
			tr_html += '<td class="c">' + person[key].prjname + '</td>';
			tr_html += '<td class="c">' + person[key].charge_name + '</td>';
			tr_html += '</tr>';
			
			$("#project_list").append( tr_html );
		});
	}
	if ( group.length > 0 ) {
		$.each(group, function(key,state){
			line_cnt++;
			var tr_html = '';
			tr_html += '<tr class="mobg">';
			tr_html += '<td class="c"><input type="checkbox" disabled="disabled" /></td>';
			tr_html += '<td class="c">' + line_cnt + '</td>';
			tr_html += '<td class="c">' + group[key].prjname + '</td>';
			tr_html += '<td class="c">' + group[key].charge_name + '</td>';
			tr_html += '</tr>';
			
			$("#project_list").append( tr_html );
		});
	}
	if (line_cnt == 0) {
		var tr_html = '';
		tr_html += '<tr class="mobg">';
		tr_html += '<td class="c" colspan="4">검색된 내역이 없습니다.</td>';
		tr_html += '</tr>';
		
		$("#project_list").append( tr_html );
	}
}

function change_password() {
	if ($('#cpw').val() == "") {
		alert('변경하고자 하시는 비밀번호를 입력하여 주십시오.');
		$('#cpw').focus();
		return;
	}
	
	var send_check = $('#cuid').val() + "";
	send_check += "|"+$('#cpw').val();
	
 	var rsakey = new RSAKey();
   	rsakey.setPublic(publickey, "10001");
	var enc = rsakey.encrypt(send_check);
	
	$.post( "/admin/member_password_change", { encrypted: enc })
		.done(function( data ) {
			if (data.status == "success") {
				alert('비밀번호 변경이 정상 진행되었습니다.');
				$('#cpw').val('');
			}
			else if (data.status == "fail") {
				alert('입력하신 정보가 일치하지 않습니다.\n\n다시 확인하여 주세요.');
				return;
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
	/*
	if (confirm('선택된 내역을 삭제하시겠습니까?')) {
		$.post( "/admin/lcidb_deletejajae", { record: vRecords })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					list_reload($('#now_page').val());
				}
				else if (jdata.status == "fail") {
					alert('Data 읽기에 싫패하였습니다.');
				}
	  		}, "json");
	}
	*/
}

function go_member(vGubun) {
	for (var i=0; i<=4; i++) {
		$('#link_'+(i+1)).removeClass('strong');
		$('#adminlisttapmenu').removeClass('adminlisttapmenu'+(i+1));
	}
	$('#link_'+(vGubun+1)).addClass('strong');
	$('#adminlisttapmenu').addClass('adminlisttapmenu'+(vGubun+1));
	$('#sgubun').val('all');
	$('#stext').val('');
	$('#spart').val($('#sgubun').val());
	$('#skeyword').val('');
	if (vGubun < 4) {
		$('#now_gubun').val(vGubun);
	} else {
		$('#now_gubun').val('99');
	}
	list_reload(1);
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_6_1"><img src="/app/views/images/common/img_subtitle6_1.gif" alt="ADMIN" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">ADMIN</a> <span>&gt;</span> <strong>회원관리</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            <dl class="tablebox1">
                <dd class="bbssearchbox1">
                    <fieldset>
                    <legend>회원검색</legend>
                    <select id="sgubun" name="sgubun" >
                    	<option value="all">전체</option>
                    	<option value="uname">회원명</option>
                    	<option value="uid">아이디</option>
                    	<option value="utel1">연락처</option>
                    </select> <input name="stext" id="stext" class="inp1" placeholder="검색어를 입력하세요" />
                    <input type="image" src="/app/views/images/common/icon_zoom1.gif" class="sbtn" title="검색" onclick="check_search();" />
                </form>
                </dd>
            </dl>
            
            <dl id="adminlisttapmenu" class="adminlisttapmenu1">
            	<dd class="menu1"><a id="link_1" href="javascript:go_member(0);" class="strong">전체</a></dd>
                <dd class="menu2"><a id="link_2" href="javascript:go_member(1);">개인</a></dd>
                <dd class="menu3"><a id="link_3" href="javascript:go_member(2);">사업자</a></dd>
                <dd class="menu4"><a id="link_4" href="javascript:go_member(3);">관리자</a></dd>
                <dd class="menu5"><a id="link_5" href="javascript:go_member(4);">차단</a></dd>
            </dl>
            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbslistbox1"><input type="hidden" id="now_page" value="1" /><input type="hidden" id="now_gubun" value="<?php echo $data_gubun;?>" /><input type="hidden" id="spart" value="" /><input type="hidden" id="skeyword" value="" />
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="7%" />
                        <col width="*" />
                        <col width="150" />
                        <col width="200" />
                        </colgroup>
                        <tr>
                            <th class="tl"><input type="checkbox" id="all_record_select" onclick="checkbox_checkall();" /></th>
                            <th>No.</th>
                            <th>E-mail</th>
                            <th>이름</th>
                            <th>연락처</th>
                            <th>Level</th>
                            <th class="tr">가입일</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" id="btn_deleteitem" class="" onclick="alert('아직 정의되지 않은 기능입니다.');">삭제</button></div>
                    <div class="btntype2"><button type="button" id="btn_diapup140" class="st2">등록</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup151">
    <dl class="tablebox4_1">
        <dd>
            <p class="bbstitle1">홍길동 회원정보</p>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <tr><input type="hidden" id="cuid" name="cuid" />
                    <th><span id="tit_01">E-Mail</span></th>
                    <td><strong id="info_01"></strong></td>
                </tr>
                <tr>
                    <th>회원명</th>
                    <td><span id="info_02"></span></td>
                </tr>
                <tr>
                    <th>비밀번호</th>
                    <td><input class="inp3" type="password" name="cpw" id="cpw" maxlength="12" placeholder=""> <button class="" onclick="change_password();">비밀번호변경</button></td>
                </tr>
                <tr>
                    <th>연락처</th>
                    <td><strong id="info_03"></strong></td>
                </tr>
                <tr>
                    <th>레벨</th>
                    <td><strong id="info_04"></strong><!-- <select class="slt1"><option>개인회원</option><option>사업자회원</option></select> <button class="st1">권한변경</button> --></td>
                </tr>
            </table>
        </dd>
        <dd class="tapmenubox1">
            <dl id="adminlisttapmenu2" class="adminlisttapmenu10">
                <dd class="menu1" id="tit_02"><a href="javascript:change_tab(1);" class="strong">상세정보</a></dd>
                <dd class="menu2" id="tit_03"><a href="javascript:change_tab(2);">Project</a></dd>
            </dl>
        </dd>
        <dd id="tit_04">
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 1px #ddd;">
                <tr>
                    <th>사업자등록번호</th>
                    <td colspan="3"><strong id="info_05"></strong></td>
                </tr>
                <tr style="display:none;">
                    <th>주소</th>
                    <td colspan="3"><strong id="info_06"></strong></td>
                </tr>
                <tr style="display:none;">
                    <th>업태</th>
                    <td>건설업</td>
                    <th>종목</th>
                    <td>서비스</td>
                </tr>
                <tr>
                    <th>연락처</th>
                    <td><strong id="info_07"></strong></td>
                    <th>담당자</th>
                    <td><strong id="info_08"></strong></td>
                </tr>
                <tr>
                    <th>담당자 연락처</th>
                    <td><strong id="info_09"></strong></td>
                    <th>담당자 E-Mail</th>
                    <td><strong id="info_10"></strong></td>
                </tr>
                <tr>
                    <th>사업자등록증</th>
                    <td colspan="3"><strong id="info_11"></strong></td>
                </tr>
            </table>
        </dd>
        <dd class="tablebox1" id="tit_05">
            <table id="project_list" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl"><input type="checkbox" disabled="disabled" /></th>
                    <th>No.</th>
                    <th>Project명</th>
                    <th class="tr">담당자</th>
                </tr>
            </table>
		</dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st3" id="abtn_diapup151">확인</button><!-- <button type="button" class="st5" id="dbtn_diapup151">삭제</button> <button type="button" id="cbtn_diapup151" class="">취소</button> --></div>
        </dd>
    </dl>
</div>

<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>