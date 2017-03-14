<?php
	include_once(APPPATH.'/views/header.php');
	
	if (isset($param['type'])) {
		$check_type = $param['type'];
	} else {
		$check_type = "1";
	}
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
<script type="text/javascript" charset="utf-8" src="/app/views/js/bbs.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="/app/views/js/CKeditor/ckeditor.js"></script>

<script type="text/javascript">
<!--
var editor = [ ];
var regext = /<?php echo $extension; ?>/i;

$(function() {
	list_reload(1);

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
			url: '/community/insert_bbs',
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
});

function bbs_change(vGubun) {
	document.location = '/admin/bbs/type/' + vGubun;
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_6_6"><img src="/app/views/images/common/img_subtitle6_6.gif" alt="ADMIN" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">ADMIN</a> <span>&gt;</span> <strong>게시판관리</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            
            <dl id="adminlisttapmenu" class="adminlisttapmenu<?php echo $check_type;?>">
            	<dd class="menu1"><a href="/admin/bbs_list/type/1"<?php if ($check_type == "1") { echo ' class="strong"'; }?>>공지사항</a></dd>
                <dd class="menu2"><a href="/admin/bbs_list/type/2"<?php if ($check_type == "2") { echo ' class="strong"'; }?>>자유게시판</a></dd>
                <dd class="menu3"><a href="/admin/bbs_list/type/3"<?php if ($check_type == "3") { echo ' class="strong"'; }?>>자료실</a></dd>
                <dd class="menu4"><a href="/admin/bbs_list/type/4"<?php if ($check_type == "4") { echo ' class="strong"'; }?>>질문하기</a></dd>
                <dd class="menu5"><a href="/admin/bbs_list/type/5"<?php if ($check_type == "5") { echo ' class="strong"'; }?>>고객센터</a></dd>
            </dl>
            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbssearchbox1"><input type="hidden" id="now_page" value="1" /><input type="hidden" id="spart" value="" /><input type="hidden" id="skeyword" value="" />
                    <fieldset>
                    <legend>검색</legend>
                    <select id="sgubun" name="sgubun" >
                    	<option value="title">제목</option>
                    	<option value="contents">내용</option>
                    </select>
                    <input type="text" name="stext" id="stext" class="search" placeholder="검색어를 입력하세요" />
                    <input type="image" src="/app/views/images/common/icon_zoom1.gif" class="sbtn" title="검색" onclick="check_search();" />
                    </fieldset>
                </dd>
                <dd class="bbslistbox1">
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="*" />
                        <col width="100" />
                        <col width="150" />
                        </colgroup>
                        <tr>
                            <th class="tl">No.</th>
                            <th>제목</th>
                            <th>자료</th>
                            <th class="tr">작성일</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <!--div class="btntype1"><button type="button">목록</button></div-->
                    <div class="btntype2"><?php if ($bbs_write && $session_id != "") { ?><button type="button" class="st1" onclick="go_write();">작성</button><? } ?></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
        </div>
        <!--//본문 -->            

    </div>
</div>

<?php
//게시판 공통 Layer Popup
include_once(APPPATH.'/views/community/include_pop.php');
?>

<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>