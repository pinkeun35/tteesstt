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
});

function form_reset() {
	$("#search_list tr:not(:first)").remove();
	$('#sid').val('');
	$('#sname').val('');
	$('#stel').val('');
}

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/member/get_employer_list", { page: vPage })
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
						tr_html += '<td class="c">' + jdata.item[key].uname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].utel1 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].uid + '</td>';
						tr_html += '<td class="c"><button type="button" class="st5" onclick="remove_employer(\'' + jdata.item[key].uid + '\', \'' + jdata.item[key].uname + '\');">삭제</button></td>';
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

function search_member() {
	if ($('#sid').val() == '' && $('#sname').val() == '' && $('#stel').val() == '') {
		alert('가입자 검색을 위한 조건을 입력하여 주십시오.');
		$('#sid').focus();
		return;
	}
	
	var condition_cnt = 0;
	if ($('#sid').val() != '' && $('#sname').val() != '') {
		condition_cnt++;
	}
	if ($('#sid').val() != '' && $('#stel').val() != '') {
		condition_cnt++;
	}
	if ($('#sname').val() != '' && $('#stel').val() != '') {
		condition_cnt++;
	}
	if (condition_cnt == 0) {
		alert('가입자 검색을 위한 조건을 입력하여 주십시오.\n\n가입자 검색은 회원정보 2가지 이상의 정보로 검색합니다.');
		return;
	}

	$.post( "/member/employer_search", { sid: $('#sid').val(), sname: $('#sname').val(), stel: $('#stel').val() })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$("#search_list tr:not(:first)").remove();
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c">' + jdata.item[key].uname + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].uid + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].utel1 + '</td>';
						tr_html += '<td class="c"><button type="button" class="st2" onclick="add_employer(\'' + jdata.item[key].uid + '\', \'' + jdata.item[key].uname + '\');">추가</button></td>';
						tr_html += '</tr>';
						
						$("#search_list").append( tr_html );
						
						line_num--;
					});
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

function add_employer(vId, vName) {
	if (confirm('해당 회원 ' + vName + '(' + vId + ')에 대한 소속원 목록으로 등록하시겠습니까?')) {
		$.post( "/member/employer_add", { aid: vId })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					alert('소속원 정보 추가 작업에 성공하였습니다.');
					search_member();
				}
				else if (jdata.status == "fail") {
					alert('소속원 정보 추가 작업에 실패하였습니다.');
				}
				list_reload($('#now_page').val());
	  		}, "json");
	}
}

function remove_employer(vId, vName) {
	if (confirm('해당 회원 ' + vName + '(' + vId + ')에 대한 소속원 목록에서 삭제하시겠습니까?')) {
		$.post( "/member/employer_remove", { rid: vId })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					alert('소속원 정보 삭제 작업에 성공하였습니다.');
				}
				else if (jdata.status == "fail") {
					alert('소속원 정보 삭제 작업에 실패하였습니다.');
				}
				list_reload($('#now_page').val());
	  		}, "json");
	}
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_1_10"><img src="/app/views/images/common/img_subtitle1_10.gif" alt="MEMBER" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">MEMBER</a> <span>&gt;</span> <strong>소속원목록</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
        	
            <p><strong><?php echo $this->session->userdata('lcco2_name');?>(<?php echo $session_id;?>)</strong>님의 직원으로 등록된 회원목록 입니다.</p>
            
            <dl class="tablebox1">
                <dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbslistbox1"><input type="hidden" id="now_page" value="1" />
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="*" />
                        <col width="*" />
                        <col width="*" />
                        <col width="*" />
                        </colgroup>
                        <tr>
                            <th class="tl">No.</th>
                            <th>이름</th>
                            <th>연락처</th>
                            <th>E-Mail</th>
                            <th class="tr">작업</th>
                        </tr>
                        <!-- <tr class="mobg">
                            <td class="c"><%=i %></td>
                            <td><a href="#;" class="btn_diapup105" >홍길순</a></td>
                            <td class="c">010-9876-5432</td>
                            <td class="c">akfja@naver.com</td>
                            <td class="c"><button type="button" class="st5">삭제</button></td>
                        </tr> -->
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <!--div class="btntype1"><button type="button">목록</button></div-->
                    <div class="btntype2"><button type="button" class="st1" id="btn_diapup106">직원추가</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>

        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup106">
	<p><strong>E-Mail</strong>(일반회원) 또는 <strong>ID</strong>(기업회원)으로 로그인하세요.<br />
    <input class="inp1" name="sid" id="sid" placeholder="가입자ID(E-Mail)을 입력하세요" /><br /><input class="inp1" name="sname" id="sname" placeholder="가입자 이름을 입력하세요" /><br /><input name="stel" id="stel" class="inp1" placeholder="가입자 연락처를 입력하세요" /><a href="javascript:search_member();" class="btn_login"><img src="/app/views/images/common/btn_employercheck.gif" alt="가입자검색" /></a>
    </p>
    <div id="employercheck">
        <dl id="employercon" class="tablebox1">
            <p>가입자검색은 가입한 회원정보 2가지 이상의 정보로 검색됩니다.</p>
            <p>가입자 검색결과 입니다.<br />직원으로 등록하려면 추가를 클릭해주세요.</p>
            <dd class="bbslistbox1">
                <table id="search_list" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                    <colgroup>
                    <col width="15%" />
                    <col width="45%" />
                    <col width="25%" />
                    <col width="15%" />
                    </colgroup>
                    <tr>
                        <th class="tl">이름</th>
                        <th>아이디</th>
                        <th>연락처</th>
                        <th class="tr">&nbsp;</th>
                    </tr>
                    <!-- <tr class="mobg">
                        <td class="c"><%=i%></td>
                        <td class="c">adkdkaf@dkajfkljal.com</td>
                        <td class="c">010-9876-5432</td>
                        <td class="c"><button type="button" class="st2">추가</button></td>
                    </tr> -->
                </table>
            </dd>
            <dd class="bbsbtnbox1" style="display:none;">
                <!--div class="btntype1"><button type="button">목록</button></div-->
                <!--div class="btntype2"><button type="button" class="st1">직원추가</button></div-->
                <div class="pageing"><a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page1.gif" alt="처음" /></a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page2.gif" alt="이전" /></a> <a href="#;">5</a> <a href="#;">6</a> <a href="#;">7</a> <a href="#;">8</a> <a href="#;" class="strong">9</a> <a href="#;">10</a> <a href="#;">11</a> <a href="#;">12</a> <a href="#;">13</a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page3.gif" alt="다음" /></a> <a href="#;" class="pageimg"><img src="/app/views/images/common/icon_page4.gif" alt="마지막" /></a></div>
            </dd>
        </dl>
        </div>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>