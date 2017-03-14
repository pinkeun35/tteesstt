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
			url: '/member/modify_form',
			type: 'POST',
			data:  formData,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data, textStatus, jqXHR) {
				alert('회원정보 변경이 완료되었습니다.');
				$('#fupload').val('');
				file_info = data;
				make_fileinfo();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('회원정보 변경에 오류가 발생되었습니다.\n\n다시 확인하여 주십시오.');
				document.location.replace('/member/modify');
			}          
		});
		e.preventDefault(); //Prevent Default action. 
		e.unbind();
	});
	
	make_fileinfo();
});

var auth_num_rcv = "";
var auth_num_error = 0;
var file_info = '<?php echo json_encode($memberimage); ?>';

function get_authnum() {
	if ($('#opw').val() == "") {
		alert('기존 비밀번호를 입력해 주세요.');
		$('#opw').focus();
		return;
	}
	var return_password = check_pw();
	if (return_password != "0") {
		$('#upw1').focus();
		return;
	}
	
	var send_check = $('#opw').val() + "";
	send_check += "|"+$('#upw1').val();
	send_check += "|"+$('#upw2').val();
	
 	var rsakey = new RSAKey();
   	rsakey.setPublic(publickey, "10001");
	var enc = rsakey.encrypt(send_check);
	
	$('#enc_data').val(enc);
	
	$.post( "/member/pwchange_auth", { encrypted: enc })
		.done(function( data ) {
			if (data.status == "success") {
				$('#auth_email_addr').text('<?php if ($member->mtype == "2") { echo $member->cemail; } else { echo $session_id; } ?>');
				auth_num_rcv = data.auth_num;
				auth_num_error = 0;
				$('#confirm_num').val("");
				
				$( "#diapup103" ).dialog( "close" );
				
				$( "#diapup101" ).dialog( "open" );
			}
			else if (data.status == "fail") {
				alert('입력하신 정보가 일치하지 않습니다.\n\n다시 확인하여 주세요.');
				return;
			}
  		}, "json");

}

function vertfy_authnum() {
	if (auth_num_rcv != "") {
		if ($('#confirm_num').val() == auth_num_rcv) {
			$( "#diapup101" ).dialog( "close" );
			change_pw();
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

function change_pw() {
	$.post( "/member/pwchange_confirm", { encrypted: $('#enc_data').val() })
		.done(function( data ) {
			if (data.status == "success") {
				alert('비밀번호 변경이 정상 진행되었습니다.');
			}
			else if (data.status == "fail") {
				alert('입력하신 정보가 일치하지 않습니다.\n\n다시 확인하여 주세요.');
				return;
			}
  		}, "json");
}

function change_info() {
	var member_type = "<?php echo $member->mtype; ?>";
	if (member_type == "1") {
		if (check_tel($('#utel1'), '연락처') != "0") {
			$('#utel1').focus();
			return;
		}
	} else if (member_type == "2") {
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
	
	$("#uploadform").submit();

}

function support_secede() {
	if ($('#cpw').val() == "") {
		alert('비밀번호를 입력하여 주세요.');
		$('#cpw').focus();
		return;
	}
	if ($('#cwhy').val() == "") {
		alert('탈퇴이유를 입력하여 주세요.');
		$('#cwhy').focus();
		return;
	}

	var send_check = $('#cpw').val() + "";
	send_check += "|"+$('#cwhy').val();
	
 	var rsakey = new RSAKey();
   	rsakey.setPublic(publickey, "10001");
	var enc = rsakey.encrypt(send_check);
	
	$.post( "/member/secede_confirm", { encrypted: enc })
		.done(function( data ) {
			if (data.status == "success") {
				$( "#diapup104" ).dialog( "close" );
				alert('회원 탈퇴가 완료되었습니다.\n\n메인 화면으로 이동됩니다.');
				document.location.replace('/');
			}
			else if (data.status == "fail") {
				alert('입력하신 정보가 일치하지 않습니다.\n\n다시 확인하여 주세요.');
				return;
			}
  		}, "json");
}

function make_fileinfo() {
	var temp_html = '';
	var idx_no = 0;
	var pre_html = '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	var jdata = jQuery.parseJSON( file_info );
	$.each(jdata, function(key,state){
		if (idx_no > 0)
			temp_html += pre_html;
		temp_html += '<a href="javascript:view_image(' + idx_no + ');">' + jdata[key].filename + ' [미리보기]</a> <a href="javascript:delete_image(' + jdata[key].seq + ')"><img src="/app/views/images/common/icon_del1.gif" alt="삭제"/></a>';
		idx_no++;
	});
	$("#file_info_area").html(temp_html);
}

function delete_image(vIdx) {
	if (confirm('선택하신 파일을 삭제하시겠습니까?')) {
		$.post( "/member/delete_image", { idx: vIdx })
			.done(function( data ) {
				file_info = JSON.stringify(data);
				make_fileinfo();
	  		}, "json");
	  }
}

function view_image(vIdx) {
	var jdata = jQuery.parseJSON( file_info );
	
	var image_width = 0;
	var image_height = 0;
	
	var img = new Image();
	img.src = '/uploads/' + jdata[vIdx].filename;
	img.onload = function() {
	  image_width = this.width;
	  image_height = this.height;
	  
	  run_image(this.src, this.width, this.height)
	}
}

function run_image(vSrc, vWidth, vHeight) {
	var newImg = '<img src=' + vSrc + '></img>';
     $('#ladiv')
        .html($(newImg)
        .animate({ height: vHeight, width: vWidth }, 1500));
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_1_9"><img src="/app/views/images/common/img_subtitle1_9.gif" alt="MEMBER" /></span></h2>
        
        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">MEMBER</a> <span>&gt;</span> <strong>회원정보변경</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
        	<p><strong><?php echo $member->uname; ?>(<?php echo $session_id; ?>)</strong>님은 <u><?php if ($member->mtype == "2") { echo "사업자회원"; } else { echo "개인회원"; } ?></u>입니다.</p>
            <dl class="tablebox6">
                <dd>
                    <table class="tablewritetype6" border="0" cellpadding="0" cellspacing="0">
                      <tr class="tdtype_007">
                        <th>아이디</th>
                        <td colspan="3"><label>
                          <?php echo $session_id; ?></label></td>
                      </tr>
                      <tr class="tdtype_008">
                        <th><?php if ($member->mtype == "2") { echo "회사명"; } else { echo "이름"; } ?></th>
                        <td colspan="3"><?php echo $member->uname; ?></td>
                      </tr>
                      <form id="uploadform" name="uploadform" method="post" enctype="multipart/form-data">
                      <tr class="tdtype_007"<?php if ($session_mtype == "2") { ?> style="display:none;"<?php } ?>>
                        <th>연락처</th>
                        <td><input class="inp3" type="text" numberhypenOnly="true" name="utel1" id="utel1" maxlength="15" value="<?php echo $member->utel1; ?>" /></td>
                      </tr>
                    </table>
                    <table class="tablewritetype6" border="0" cellpadding="0" cellspacing="0"<?php if ($session_mtype == "1") { ?> style="display:none;"<?php } ?>>
                      <tr class="tdtype_007">
                        <th>연락처</th>
                        <td><input class="inp3" type="text" numberhypenOnly="true" name="utel2" id="utel2" maxlength="15" value="<?php echo $member->utel2; ?>" /></td>
                        <th>사업자등록번호</th>
                        <td><input class="inp3" type="text" numberhypenOnly="true" name="ucrn" id="ucrn" maxlength="12" value="<?php echo $member->ucrn; ?>" /</td>
                      </tr>
                      <tr class="tdtype_008">
                        <th>담당자 이름</th>
                        <td><input class="inp3" type="text" name="cname" id="cname" maxlength="20" value="<?php echo $member->cname; ?>" /></td>
                        <th>담당저 연락처</th>
                        <td><input class="inp3" type="text" numberhypenOnly="true" name="ctel" id="ctel" maxlength="15" value="<?php echo $member->ctel; ?>" /></td>
                      </tr>
                      <tr class="tdtype_007">
                        <th>담당자 소속</th>
                        <td><input class="inp3" type="text" name="csosok" id="csosok" maxlength="20" value="<?php echo $member->csosok; ?>" /></td>
                        <th>담당자 E-Mail</th>
                        <td><input class="inp3" type="text" name="cemail" id="cemail" maxlength="25" value="<?php echo $member->cemail; ?>" /></td>
                      </tr>
                      <tr class="tdtype_008">
                        <th>사업자등록증</th>
                        <td colspan="3"><input type="file" class="file1" name="fupload" id="fupload" /><input type="hidden" id="enc_data" name="enc_data" value="" /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        	<span id="file_info_area"></span><!-- <a href="#;">사업자등록증1401010.jpg [미리보기]</a> <a href="#;"><img src="/app/views/images/common/icon_del1.gif" alt="삭제"/></a><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#;">사업자등록증1401010.jpg [미리보기]</a> <a href="#;"><img src="/app/views/images/common/icon_del1.gif" alt="삭제"/></a> -->
                        </td>
                      </tr>
                     </form>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype3"><button type="button" id="btn_diapup103" class="st1">비밀번호변경</button> <button type="button" class="st2" onclick="change_info();">회원정보변경</button>  <button type="button" id="btn_diapup104" class="st4">회원탈퇴</button> <button type="button" class="">취소</button></div>
                </dd>
            </dl>
        
    </div>
        <!--//본문 -->

    </div>
</div>

<div id="ladiv"></div>

<div id="diapup101">
	<p><strong id="auth_email_addr"></strong> 메일로 인증번호가 전송되었습니다.<br />전송받은 인증번호를 입력 후 인증하기버튼을 클릭해주세요.<br /><br />
    <input numberhypenOnly="true" id="confirm_num" name="confirm_num" class="inp1" maxlength="10" /><br /><br />
    <button type="button" class="st1" onclick="vertfy_authnum()">인증하기</button> <button type="button" class="st5">다시받기</button> <button type="button" class="" id="cbtn_diapup101">취소</button></p>
</div>

<div id="diapup103">
	<p><input class="inp1" type="password" name="opw" id="opw" maxlength="12" placeholder="기존 비밀번호를 입력하세요." /><br />
		<input class="inp1" type="password" name="upw1" id="upw1" maxlength="12" placeholder="변경할 비밀번호를 입력하세요." /><br />
		<input class="inp1" type="password" name="upw2" id="upw2" maxlength="12" placeholder="변경할 비밀번호를 다시 입력하세요." /><br /><br />
    <button type="button" id="btn_diapup101" class="st1" onclick="get_authnum();">인증번호받기</button>
    <button type="button" class="" id="cbtn_diapup103">취소</button><br /><br />변경할 비밀번호를 입력 후 인증번호 받기를 클릭하면<br /><strong><?php if ($member->mtype == "2") { echo $member->cemail; } else { echo $session_id; } ?></strong>로 인증번호가 전송됩니다.</p>
</div>

<div id="diapup104">
	<p><input class="inp1" type="password" name="cpw" id="cpw" maxlength="12" placeholder="비밀번호를 입력하세요." /><br />
		<input id="cwhy" name="cwhy" class="inp1" maxlength="60" placeholder="탈퇴이유" /><br /><br />
    <button type="button" id="" class="st4" onclick="support_secede();">탈퇴신청</button> <button type="button" class="" id="cbtn_diapup104">취소</button><br /><br />탈퇴후에도 기존의 <strong>기록된 정보는 유지</strong>됩니다.</p>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>