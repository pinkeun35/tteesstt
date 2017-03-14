<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->
<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
	
<script type="text/javascript">
<!--
$(function() {
});

function find_id() {
	if ($('#fid_name').val() == "") {
		alert("이름을 입력하여 주십시오.");
		$('#fid_name').focus();
		return;
	}
	if ($('#fid_tel').val() == "") {
		alert("연락처를 입력하여 주십시오.");
		$('#fid_tel').focus();
		return;
	}
	
	$( "#diapup186" ).dialog( "close" );
	
	var send_check = $('#fid_name').val() + "";
	send_check += "|"+$('#fid_tel').val();
	
 	var rsakey = new RSAKey();
   	rsakey.setPublic(publickey, "10001");
	var enc = rsakey.encrypt(send_check);
	
	$('#enc_data').val(enc);
	
	$('#fid_name').val('');
	$('#fid_tel').val('');
	
	$.post( "/member/find_id", { encrypted: enc })
		.done(function( data ) {
			if (data.status == "success") {
				$('#area_idview').html(data.find_id);
				$('#area_ok_find_id').show();
				$('#area_fail_find_id').hide();
				$( "#diapup188" ).dialog( "open" );
			}
			else if (data.status == "fail") {
				$('#area_idview').html(data.find_id);
				$('#area_ok_find_id').hide();
				$('#area_fail_find_id').show();
				$( "#diapup188" ).dialog( "open" );
			}
  		}, "json");
}

function find_pw() {
	if ($('#find_uid').val() == "") {
		alert("아이디를 입력하여 주십시오.");
		$('#find_uid').focus();
		return;
	}
	if ($('#find_name2').val() == "") {
		alert("이름을 입력하여 주십시오.");
		$('#find_name2').focus();
		return;
	}
	
	$( "#diapup187" ).dialog( "close" );
	
	var send_check = $('#find_uid').val() + "";
	send_check += "|"+$('#find_name2').val();
	
 	var rsakey = new RSAKey();
   	rsakey.setPublic(publickey, "10001");
	var enc = rsakey.encrypt(send_check);
	
	$('#enc_data').val(enc);
	
	$('#find_uid').val('');
	$('#find_name2').val('');
	
	$.post( "/member/find_pw", { encrypted: enc })
		.done(function( data ) {
			if (data.status == "success") {
				$('#area_pwview').html(data.find_pw);
				$('#area_ok_find_pw').show();
				$('#area_fail_find_pw').hide();
				$( "#diapup189" ).dialog( "open" );
			}
			else if (data.status == "fail") {
				$('#area_pwview').html(data.find_id);
				$('#area_ok_find_pw').hide();
				$('#area_fail_find_pw').show();
				$( "#diapup189" ).dialog( "open" );
			}
  		}, "json");
}
-->
</script>
<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_1_2"><img src="/app/views/images/common/img_subtitle1_2.gif" alt="MEMBER" /></span></h2>            
        
        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">MEMBER</a> <span>&gt;</span> <strong>ID/PASSWORD 찾기</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            

<button id="btn_diapup186">아이디찾기</button> &nbsp; <button id="btn_diapup187">비밀번호찾기</button>
        
        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup186">
	<p>
    <strong>이름, 연락처</strong>를 입력하세요<br /><br />
    <span>이름 <input id="fid_name" name="fid_name" class="inp1" /><br />연락처 <input id="fid_tel" name="fid_tel" class="inp1" /></span>
    <a href="javascript:find_id();" id="sbtn_diapup186" class="btn_search1"><img src="/app/views/images/common/btn_search.gif" alt="찾기" /></a>
    </p>
</div>

<div id="diapup187">
	<p>
    <strong>아이디, 이름</strong>을 입력하세요<br /><br />
    <span>아이디 <input id="find_uid" name="find_uid" class="inp1" /><br />이름 <input id="find_name2" name="find_name2" class="inp1" /></span>
    <a href="javascript:find_pw();" id="sbtn_diapup187" class="btn_search1"><img src="/app/views/images/common/btn_search.gif" alt="찾기" /></a>
    </p>
</div>

<div id="diapup188">
	<p id="area_ok_find_id">회원님의 아이디는 <strong id="area_idview"></strong> 입니다.<br /><br />
    <button class="st5" id="cbtn_diapup188">닫기</button></p>
    <p id="area_fail_find_id">입력하신 정보와 일치하는 회원이 없습니다.<br /><br />
    <button class="st1" id="rbtn_diapup188">아이디 다시찾기</button></p>
</div>

<div id="diapup189">
	<p id="area_ok_find_pw">회원님의 임시 비밀번호는 <strong id="area_pwview"></strong> 입니다.<br />
		지금 바로 접속 후 비밀번호를 변경하여 주십시오.<br /><br />
     <button class="" id="cbtn_diapup189">닫기</button></p>
    <p id="area_fail_find_pw">입력하신 정보와 일치하는 회원이 없습니다.<br /><br />
    <button class="st1" id="rbtn_diapup189">비밀번호 다시찾기</button> <button class="st5" id="obtn_diapup189">아이디 찾기</button></p>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>