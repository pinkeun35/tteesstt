<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LCCO2</title>
<meta name="author" content="" />
<meta name="robots" content="" />
<meta name="Keywords" content="" />
<link rel="shortcut icon" href="" />
<link rel="stylesheet" type="text/css" href="/app/views/css/styles_14001.css" />
<script type="text/javascript" charset="utf-8" src="/app/views/js/flash_14001.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/jquery-ui-1.11.1.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/default_14001.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/RSA/jsbn.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/RSA/jsbn2.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/RSA/prng4.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/RSA/rng.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/RSA/rsa.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/RSA/rsa2.js"></script>
</head>

<body>
<!-- header -->
<div id="headerwrap">
	<div id="logo"><a href="/"><img src="/app/views/images/common/logo.png" alt="LCCO2평가프로그램 시스템" /></a></div>
	<dl id="topetcmenu">
    	<dd>
    	<?php
    	if ($session_id == '') {
    	?>
    	<a href="#;" id="btn_diapup102">로그인</a> &nbsp;|&nbsp; 
    	<?php
    	} else {
    	?>
    	<a href="/member/logout">로그아웃</a> &nbsp;|&nbsp; 
    	<a href="/member/modify">개인정보</a> &nbsp;|&nbsp; 
    	<?php
    	}
    	?>
    	<a href="/member/customer_list">고객센터</a></dd>
    </dl>
	<dl id="gnb">
        <dt id="gnb1"><a href="/member/mypage" onmouseover="MM_swapImage('gnb1','','/app/views/images/common/gnb_01_o.gif',0); MM_showHideLayers('gnbs1','','show','gnbs2','','hide','gnbs3','','hide','gnbs4','','hide','gnbs5','','hide','gnbs6','','hide')" onmouseout="MM_swapImgRestore(); MM_showHideLayers('gnbs1','','hide')"><img src="/app/views/images/common/gnb_01.gif" alt="Member" name="gnb1" id="gnb1" /></a></dt>
    	<?php
    	if ($session_id == '') {
    	?>
        <dd id="gnbs1" onmouseover="MM_showHideLayers('gnbs1','','show','gnbs2','','hide','gnbs3','','hide','gnbs4','','hide','gnbs5','','hide','gnbs6','','hide')" onmouseout="MM_swapImgRestore(); MM_showHideLayers('gnbs1','','hide')"><a href="/member/join" onmouseover="MM_swapImage('gnbsm1_1','','/app/views/images/common/gnbs1_01_o.gif',0)" onmouseout="MM_swapImgRestore()" ><img id="gnbsm1_1" src="/app/views/images/common/gnbs1_01.gif" alt="회원가입"  /></a><a href="/member/idpwloss" onmouseover="MM_swapImage('gnbsm1_2','','/app/views/images/common/gnbs1_02_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm1_2" src="/app/views/images/common/gnbs1_02.gif" alt="ID/PW 찾기" /></a><a href="/main/policy_view" onmouseover="MM_swapImage('gnbsm1_3','','/app/views/images/common/gnbs1_03_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm1_3" src="/app/views/images/common/gnbs1_03.gif" alt="개인정보" /></a><a href="/main/agreement_view" onmouseover="MM_swapImage('gnbsm1_4','','/app/views/images/common/gnbs1_04_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm1_4" src="/app/views/images/common/gnbs1_04.gif" alt="이용약관" /></a><a href="/member/customer_list" onmouseover="MM_swapImage('gnbsm1_5','','/app/views/images/common/gnbs1_05_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm1_5" src="/app/views/images/common/gnbs1_05.gif" alt="고객센터" /></a></dd>
    	<?php
    	} else {
    	?>
        <dd id="gnbs1" onmouseover="MM_showHideLayers('gnbs1','','show','gnbs2','','hide','gnbs3','','hide','gnbs4','','hide','gnbs5','','hide','gnbs6','','hide')" onmouseout="MM_swapImgRestore(); MM_showHideLayers('gnbs1','','hide')"><a href="/member/mypage" onmouseover="MM_swapImage('gnbsm1_6','','/app/views/images/common/gnbs1_06_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm1_6" src="/app/views/images/common/gnbs1_06.gif" alt="내프로젝트" /></a><a href="/member/mybbs_list" onmouseover="MM_swapImage('gnbsm1_7','','/app/views/images/common/gnbs1_07_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm1_7" src="/app/views/images/common/gnbs1_07.gif" alt="내게시물" /></a><a href="/member/myqna_list" onmouseover="MM_swapImage('gnbsm1_8','','/app/views/images/common/gnbs1_08_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm1_8" src="/app/views/images/common/gnbs1_08.gif" alt="내질문" /></a><a href="/member/modify" onmouseover="MM_swapImage('gnbsm1_9','','/app/views/images/common/gnbs1_09_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm1_9" src="/app/views/images/common/gnbs1_09.gif" alt="내정보수정" /></a><?php if ($session_mtype == 2) { ?><a href="/member/employer" onmouseover="MM_swapImage('gnbsm1_10','','/app/views/images/common/gnbs1_10_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm1_10" src="/app/views/images/common/gnbs1_10.gif" alt="소속원목록" /></a><?php } ?></dd>
    	<?php
    	}
    	?>
        <dt id="gnb2"><a href="/usedata/project_list" onmouseover="MM_swapImage('gnb2','','/app/views/images/common/gnb_02_o.gif',0); MM_showHideLayers('gnbs1','','hide','gnbs2','','show','gnbs3','','hide','gnbs4','','hide','gnbs5','','hide','gnbs6','','hide')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs2','','hide')"><img src="/app/views/images/common/gnb_02.gif" alt="Member" id="gnb2" name="gnb2" /></a></dt>
        <dd id="gnbs2" onmouseover="MM_showHideLayers('gnbs1','','hide','gnbs2','','show','gnbs3','','hide','gnbs4','','hide','gnbs5','','hide','gnbs6','','hide')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs2','','hide')"><a href="/usedata/project_list" onmouseover="MM_swapImage('gnbsm2_1','','/app/views/images/common/gnbs2_01_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm2_1" src="/app/views/images/common/gnbs2_01.gif" alt="내프로젝트" /></a><a href="/process/process_list" onmouseover="MM_swapImage('gnbsm2_2','','/app/views/images/common/gnbs2_02_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm2_2" src="/app/views/images/common/gnbs2_02.gif" alt="공정관리" /></a><a href="/sdata/preuse_list" onmouseover="MM_swapImage('gnbsm2_3','','/app/views/images/common/gnbs2_03_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm2_3" src="/app/views/images/common/gnbs2_03.gif" alt="자료관리" /></a></dd>
        <dt id="gnb3"><a href="/lcidb/materials_list" onmouseover="MM_swapImage('gnb3','','/app/views/images/common/gnb_03_o.gif',0); MM_showHideLayers('gnbs1','','hide','gnbs2','','hide','gnbs3','','show','gnbs4','','hide','gnbs5','','hide','gnbs6','','hide')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs3','','hide')"><img src="/app/views/images/common/gnb_03.gif" alt="Member" id="gnb3" name="gnb3" /></a></dt>
        <dd id="gnbs3" onmouseover="MM_showHideLayers('gnbs1','','hide','gnbs2','','hide','gnbs3','','show','gnbs4','','hide','gnbs5','','hide','gnbs6','','hide')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs3','','hide')"><a href="/lcidb/materials_list" onmouseover="MM_swapImage('gnbsm3_1','','/app/views/images/common/gnbs3_01_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm3_1" src="/app/views/images/common/gnbs3_01.gif" alt="건설자재" /></a><a href="/lcidb/energy_list" onmouseover="MM_swapImage('gnbsm3_2','','/app/views/images/common/gnbs3_02_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm3_2" src="/app/views/images/common/gnbs3_02.gif" alt="Energy" /></a><a href="/lcidb/equip_list" onmouseover="MM_swapImage('gnbsm3_3','','/app/views/images/common/gnbs3_03_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm3_3" src="/app/views/images/common/gnbs3_03.gif" alt="건설장비" /></a></dd>
        <dt id="gnb4"><a href="/statistics/statistics_list" onmouseover="MM_swapImage('gnb4','','/app/views/images/common/gnb_04_o.gif',0); MM_showHideLayers('gnbs1','','hide','gnbs2','','hide','gnbs3','','hide','gnbs4','','show','gnbs5','','hide','gnbs6','','hide')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs4','','hide')"><img src="/app/views/images/common/gnb_04.gif" alt="Member" id="gnb4" name="gnb4" /></a></dt>
        <dd id="gnbs4" onmouseover="MM_showHideLayers('gnbs1','','hide','gnbs2','','hide','gnbs3','','hide','gnbs4','','show','gnbs5','','hide','gnbs6','','hide')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs4','','hide')"><a href="/statistics/statistics_list" onmouseover="MM_swapImage('gnbsm4_1','','/app/views/images/common/gnbs4_01_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm4_1" src="/app/views/images/common/gnbs4_01.gif" alt="전체통계" /></a><a href="/statistics/lifecycle_list" onmouseover="MM_swapImage('gnbsm4_2','','/app/views/images/common/gnbs4_02_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm4_2" src="/app/views/images/common/gnbs4_02.gif" alt="생애주기" /></a><a href="/statistics/maintenance_list" onmouseover="MM_swapImage('gnbsm4_3','','/app/views/images/common/gnbs4_03_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm4_3" src="/app/views/images/common/gnbs4_03.gif" alt="유지보수" /></a></dd>
        <dt id="gnb5"><a href="/community/notice_list" onmouseover="MM_swapImage('gnb5','','/app/views/images/common/gnb_05_o.gif',0); MM_showHideLayers('gnbs1','','hide','gnbs2','','hide','gnbs3','','hide','gnbs4','','hide','gnbs5','','show','gnbs6','','hide')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs5','','hide')"><img src="/app/views/images/common/gnb_05.gif" alt="Member" id="gnb5" name="gnb5" /></a></dt>
        <dd id="gnbs5" onmouseover="MM_showHideLayers('gnbs1','','hide','gnbs2','','hide','gnbs3','','hide','gnbs4','','hide','gnbs5','','show','gnbs6','','hide')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs5','','hide')"><a href="/community/notice_list" onmouseover="MM_swapImage('gnbsm5_1','','/app/views/images/common/gnbs5_01_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm5_1" src="/app/views/images/common/gnbs5_01.gif" alt="공지사항" /></a><a href="/community/free_list" onmouseover="MM_swapImage('gnbsm5_2','','/app/views/images/common/gnbs5_02_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm5_2" src="/app/views/images/common/gnbs5_02.gif" alt="자유게시판" /></a><a href="/community/data_list" onmouseover="MM_swapImage('gnbsm5_3','','/app/views/images/common/gnbs5_03_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm5_3" src="/app/views/images/common/gnbs5_03.gif" alt="자료실" /></a><a href="/community/qna_list" onmouseover="MM_swapImage('gnbsm5_4','','/app/views/images/common/gnbs5_04_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm5_4" src="/app/views/images/common/gnbs5_04.gif" alt="질문하기" /></a></dd>
    	<?php
    	if ($session_id != '' && $session_mtype == "3") {
    	?>
		<dt id="gnb6"><a href="/admin/lcidb_listjajae" onmouseover="MM_swapImage('gnb6','','/app/views/images/common/gnb_06_o.gif',0); MM_showHideLayers('gnbs1','','hide','gnbs2','','hide','gnbs3','','hide','gnbs4','','hide','gnbs5','','hide','gnbs6','','show')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs6','','hide')"><img src="/app/views/images/common/gnb_06.gif" alt="Member" id="gnb6" name="gnb6" /></a></dt>
        <dd id="gnbs6" onmouseover="MM_showHideLayers('gnbs1','','hide','gnbs2','','hide','gnbs3','','hide','gnbs4','','hide','gnbs5','','hide','gnbs6','','show')" onmouseout="MM_swapImgRestore();MM_showHideLayers('gnbs6','','hide')"><a href="/admin/member_list" onmouseover="MM_swapImage('gnbsm6_1','','/app/views/images/common/gnbs6_01_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm6_1" src="/app/views/images/common/gnbs6_01.gif" alt="회원관리" /></a><a href="/admin/popup_list" onmouseover="MM_swapImage('gnbsm6_2','','/app/views/images/common/gnbs6_02_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm6_2" src="/app/views/images/common/gnbs6_02.gif" alt="팝업관리" /></a><a href="/admin/lcidb_listjajae" onmouseover="MM_swapImage('gnbsm6_3','','/app/views/images/common/gnbs6_03_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm6_3" src="/app/views/images/common/gnbs6_03.gif" alt="LCI DB관리" /></a><a href="/admin/similar_list" onmouseover="MM_swapImage('gnbsm6_4','','/app/views/images/common/gnbs6_04_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm6_4" src="/app/views/images/common/gnbs6_04.gif" alt="유사용어" /></a><a href="/admin/substitute_list" onmouseover="MM_swapImage('gnbsm6_5','','/app/views/images/common/gnbs6_05_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm6_5" src="/app/views/images/common/gnbs6_05.gif" alt="단위관리" /></a><a href="/admin/bbs_list" onmouseover="MM_swapImage('gnbsm6_6','','/app/views/images/common/gnbs6_06_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm6_6" src="/app/views/images/common/gnbs6_06.gif" alt="게시판관리" /></a><!-- <a href="/admin/7.jsp" onmouseover="MM_swapImage('gnbsm6_7','','/app/views/images/common/gnbs6_07_o.gif',0)" onmouseout="MM_swapImgRestore()"><img id="gnbsm6_7" src="/app/views/images/common/gnbs6_07.gif" alt="통계관리" /></a> -->
        <?php
        }
        ?>
</dd>
    </dl>
</div>
<?php
	if ($session_id == '') {
?>
<div id="diapup102">
	<p><strong>E-Mail</strong>(일반회원) 또는 <strong>ID</strong>(기업회원)으로 로그인하세요.<br /><br />
    <input id="gid" name="gid" class="inp1" maxlength="25" /><br /><input id="gpw" name="gpw" type="password" class="inp1" maxlength="25" /><a href="javascript:check_glogin();" class="btn_login"><img src="/app/views/images/common/btn_login.gif" alt="로그인" /></a><br /><br />
    <button type="button" class="st1">ID찾기</button> <button type="button" class="st2">비밀번호찾기</button> <button type="button" class="" id="abtn_diapup102">회원가입</button></p>
</div>
<?php
	}
?>
<hr />
