<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
	
<script type="text/javascript">
<!--
$(function() {
	$(document).on("keyup", "input:text[numberhypenOnly]", function() {$(this).val( $(this).val().replace(/[^0-9\-]/gi,"") );});
	
	$("#uploadform").submit(function(e)
	{
		var formObj = $(this);
		var formURL = formObj.attr("action");
		var formData = new FormData(this);
		$.ajax({
			url: '/member/submit_form',
			type: 'POST',
			data:  formData,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data, textStatus, jqXHR) {
				alert('회원가입이 완료되었습니다.');
				document.location.replace('/');
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('회원가입에 오류가 발생되었습니다.\n\n다시 확인하여 주십시오.');
				reset_form();
			}          
		});
		e.preventDefault(); //Prevent Default action. 
		e.unbind();
	});
});

var auth_num_rcv = "";
var auth_num_error = 0;

function changeuser(userGubun) {
	if (userGubun == "1") {
		$('#area_0').show();
		$('#area_1').hide();
		$('#change_label1').text("이름");
	} else if (userGubun == '2') {
		$('#area_0').hide();
		$('#area_1').show();
		$('#change_label1').text("회사명");
	}
}

function check_id() {
	if ($('#uemail').val() == "") {
		alert("아이디를 입력하여 주십시오.");
		return "1";
	}
	if ($('#uemail').val().length < 6) {
		alert("아이디는 6~25자 이내의 영문, 숫자를 사용하셔야 합니다.");
		return "2";
	}
	return "0";
}

function validEmail() {
	if (check_id() != "0") {
		$('#uemail').focus();
		return;
	}
	if ($('#memtype1').is(':checked') == true) {
		var email = $('#uemail').val();
		if (isValidEmail(email) == false) {
			alert('아이디 형식(이메일)이 올바르지 않습니다.\n\n다시 확인하여 주십시오.');
			$('#uemail').focus()
			return;
		}
		
		//이메일 DB 검색
		$('#check_id').val('');
		$.post( "/member/valid_email", { memtype: 1, vemail: $('#uemail').val() })
			.done(function( data ) {
				if (data.status == "success") {
					alert('사용하실 수 있는 아이디입니다.');
					$('#check_id').val($('#uemail').val());
				}
				else if (data.status == "fail") {
					alert('사용하실 수 없는 아이디이거나 이미 사용된 아이디입니다.');
				}
	  		}, "json");
		
		$('#check_id').val($('#uemail').val());
	} else if ($('#memtype2').is(':checked') == true) {
		if (isValidEmail(email) == true) {
			alert('사업자 회원은 Email 형식의 아이디를 사용하실 수 없습니다.');
			$('#uemail').focus()
			return;
		}
		
		//사업자 아이디 DB 검색
		$('#check_id').val('');
		$.post( "/member/valid_email", { memtype: 2, vemail: $('#uemail').val() })
			.done(function( data ) {
				if (data.status == "success") {
					alert('사용하실 수 있는 아이디입니다.');
					$('#check_id').val($('#uemail').val());
				}
				else if (data.status == "fail") {
					alert('사용하실 수 없는 아이디이거나 이미 사용된 아이디입니다.');
				}
	  		}, "json");
		
		$('#check_id').val($('#uemail').val());
	} 
}

function vertfy_authnum() {
	if (auth_num_rcv != "") {
		if ($('#confirm_num').val() == auth_num_rcv) {
			$( "#diapup101" ).dialog( "close" );
			$("#uploadform").submit();
		} else {
			if (auth_num_error < 4) {
				alert('입력하신 인증번호가 다릅니다. 다시 확인해 주세요.');
				$('#confirm_num').val("");
				$('#confirm_num').focus();
				auth_num_error++;
			} else {
				alert('인증번호 입력 오류가 5회를 초과하였습니다. 인증번호를 다시 발급받아 주세요.');
				auth_num_rcv = "";
				auth_num_error = 0;
				$('#confirm_num').val("");
				$( "#diapup101" ).dialog( "close" );
			}
		}
	}
}

function check_auth() {
	if (check_id() != "0") {
		$('#uemail').focus();
		return;
	}
	if ($('#uemail').val() != $('#check_id').val()) {
		alert('아이디 중복확인을 진행하여 주십시오.');
		$('#uemail').focus();
		return;
	}
	var return_password = check_pw();
	if (return_password != "0") {
		$('#upw1').focus();
		return;
	}
	if ($('#uname').val() == "") {
		if ($('#memtype1').is(':checked') == true) {
			alert('이름을 입력하여 주세요.');
		} else if ($('#memtype2').is(':checked') == true) {
			alert('회사명을 입력하여 주세요.');
		}
		$('#uname').focus();
		return;
	}

	var member_type = "1";
	if ($('#memtype1').is(':checked') == true) {
		if (check_tel($('#utel1'), '연락처') != "0") {
			$('#utel1').focus();
			return;
		}
	} else if ($('#memtype2').is(':checked') == true) {
		member_type = "2";
		if (check_tel($('#utel2'), '연락처') != "0") {
			$('#utel2').focus();
			return;
		}
		if ($('#ucrn').value == "") {
			alert("사업자등록번호를 입력하여 주세요.");
			$('#ucrn').focus();
			return;
		}
		if ($('#ucrn').val().length < 10) {
			alert("사업자등록번호를 올바르게 입력하여 주세요.");
			$('#ucrn').focus();
			return;
		}
		if ($('#cname').val() == "") {
			alert("담당자 이름를 입력하여 주세요.");
			$('#cname').focus();
			return;
		}
		if (check_tel($('#ctel'), '담당자 연락처') != "0") {
			$('#ctel').focus();
			return;
		}
		if ($('#csosok').val() == "") {
			alert("담당자 소속를 입력하여 주세요.");
			$('#csosok').focus();
			return;
		}
		if ($('#cemail').val() == "") {
			alert('담당자 Email을 입력하여 주세요.');
			$('#cemail').focus();
			return;
		}
		if (isValidEmail($('#cemail').val()) == false) {
			alert('아이디 형식(이메일)이 올바르지 않습니다.\n\n다시 확인하여 주십시오.');
			$('#cemail').focus();
			return;
		}
	}
	
	if ($('#agr_policy').is(':checked') == false) {
		alert('개인정보 취급방침에 동의하여 주세요.');
		$('#agr_policy').focus();
		return;
	}
	if ($('#agr_ment').is(':checked') == false) {
		alert('이용약관에 동의하여 주세요.');
		$('#agr_ment').focus();
		return;
	}
	
	var send_check = member_type + "";
	send_check += "|"+$('#uemail').val();
	send_check += "|"+$('#check_id').val();
	send_check += "|"+$('#upw1').val();
	send_check += "|"+$('#upw2').val();
	send_check += "|"+$('#uname').val();
	send_check += "|"+$('#cemail').val();
	
 	var rsakey = new RSAKey();
   	rsakey.setPublic(publickey, "10001");
	var enc = rsakey.encrypt(send_check);
	
	$('#enc_data').val(enc);
	
	$.post( "/member/join_auth", { encrypted: enc })
		.done(function( data ) {
			if (data.status == "success") {
				$('#auth_email_addr').text(data.auth_email);
				auth_num_rcv = data.auth_num;
				auth_num_error = 0;
				$('#confirm_num').val("");
				$( "#diapup101" ).dialog( "open" );
			}
			else if (data.status == "fail") {
			}
  		}, "json");
}

function reset_form() {
	$('#uemail').val('');
	$('#check_id').val('');
	$('#upw1').val('');
	$('#upw2').val('');
	$('#uname').val('');
	$('#utel1').val('');
	$('#utel2').val('');
	$('#ucrn').val('');
	$('#cname').val('');
	$('#ctel').val('');
	$('#csosok').val('');
	$('#cemail').val('');
	$('#enc_data').val('');
	$('#fupload').val('');
}
-->
</script>
<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_1_1"><img src="/app/views/images/common/img_subtitle1_1.gif" alt="MEMBER" /></span></h2>            
        
        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">MEMBER</a> <span>&gt;</span> <strong>회원가입</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            

            <dl class="tablebox6">
                <dd>
                    <table class="tablewritetype6" border="0" cellpadding="0" cellspacing="0">
                        <tr class="tdtype_007">
                            <th>회원구분</th>
                            <td colspan="3"><label><input type="radio" name="memtype" id="memtype1" value="1" checked="checked" onclick="changeuser(this.value);" /><strong>일반(개인)회원가입</strong></label> <label><input type="radio" name="memtype" id="memtype2" value="2" onclick="changeuser(this.value);" />사업자 회원가입</label><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; LCCO2 평가프로그램은 회원가입을 하셔야 이용할 수 있습니다.</td>
                        </tr>
                        <tr class="tdtype_008">
                            <th>아이디</th>
                            <td colspan="3"><input class="inp3" type="text" name="uemail" id="uemail" maxlength="25" /> <button type="button" class="stcheck" onclick="validEmail()">중복확인</button><input type="hidden" name="check_id" id="check_id" value=""></td>
                        </tr>
                        <tr class="tdtype_007">
                            <th>비밀번호</th>
                          <td colspan="3"><input class="inp3" type="password" name="upw1" id="upw1" maxlength="12" /> 비밀번호는 <strong>영문,숫자, 특수문자등을 섞어서6~12자리로 입력</strong>해야 합니다.</td>
                        </tr>
                        <tr class="tdtype_008">
                            <th>비밀번호확인</th>
                            <td colspan="3"><input class="inp3" type="password" name="upw2" id="upw2" maxlength="12" /> 비밀번호를 다시 입력하여 주세요.</td>
                        </tr>
                        <tr class="tdtype_007">
                            <th><span id="change_label1">이름</span></th>
                            <td colspan="3"><input class="inp3" type="text" name="uname" id="uname" maxlength="20" /></td>
                        </tr>
                        <form id="uploadform" name="uploadform" method="post" enctype="multipart/form-data">
                        <tr class="tdtype_008" id="area_0">
                            <th>연락처</th>
                            <td><input class="inp3" type="text" numberhypenOnly="true" name="utel1" id="utel1" maxlength="15" /></td>
                        </tr>
                    </table>
                    <table id="area_1" class="tablewritetype6" border="0" cellpadding="0" cellspacing="0" style="display:none;">
                        <tr class="tdtype_008">
                            <th>연락처</th>
                            <td><input class="inp3" type="text" numberhypenOnly="true" name="utel2" id="utel2" maxlength="15" /></td>
                            <th>사업자등록번호</th>
                            <td><input class="inp3" type="text" numberhypenOnly="true" name="ucrn" id="ucrn" maxlength="12" /></td>
                        </tr>
                        <tr class="tdtype_007">
                            <th>담당자 이름</th>
                            <td><input class="inp3" type="text" name="cname" id="cname" maxlength="20" /></td>
                            <th>담당저 연락처</th>
                            <td><input class="inp3" type="text" numberhypenOnly="true" name="ctel" id="ctel" maxlength="15" /></td>
                        </tr>
                        <tr class="tdtype_008">
                            <th>담당자 소속</th>
                            <td><input class="inp3" type="text" name="csosok" id="csosok" maxlength="20" /></td>
                            <th>담당자 E-Mail</th>
                            <td><input class="inp3" type="text" name="cemail" id="cemail" maxlength="25" /></td>
                        </tr>
                        <tr class="tdtype_007">
                            <th>사업자등록증</th><input type="hidden" id="enc_data" name="enc_data" value="" />
                            <td colspan="3"><input type="file" class="file1" name="fupload" id="fupload" /></td>
                        </tr>
                        </form>
                    </table>
                </dd>
                <dd>
                <br /><br />
                    <dl class="policybox">
                        <dt>개인정보 취급방침</dt>
                        <dd class="text"><iframe src="/main/policy" width="100%" height="140px" frameborder="0" border="0" marginwidth="0" marginheight="0" ></iframe></dd>
                        <dd class="agreecheck"><label><input type="checkbox" id="agr_policy" />동의</label></dd>
                    </dl>
                    <br /><br />
                    <dl class="agreementbox">
                        <dt>이용약관</dt>
                        <dd class="text"><iframe src="/main/agreement" width="100%" height="140px" frameborder="0" border="0" marginwidth="0" marginheight="0" ></iframe></dd>
                        <dd class="agreecheck"><label><input type="checkbox" id="agr_ment" />동의</label></dd>
                    </dl>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype3"><button type="button" id="btn_diapup101" class="st1" onclick="check_auth()">인증받기</button> <button type="button" class="st5" onclick="reset_form();">다시작성</button> <button type="button" class="">취소</button></div>
                </dd>
            </dl>
        
        </div>
        <!--//본문 -->            

    </div>
</div>

<div id="diapup101">
	<p><strong id="auth_email_addr"></strong> 메일로 인증번호가 전송되었습니다.<br />전송받은 인증번호를 입력 후 인증하기버튼을 클릭해주세요.<br /><br />
    <input numberhypenOnly="true" id="confirm_num" name="confirm_num" class="inp1" maxlength="10" /><br /><br />
    <button type="button" class="st1" onclick="vertfy_authnum()">인증하기</button> <button type="button" class="st5">다시받기</button> <button type="button" class="" id="cbtn_diapup101">취소</button></p>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>