<?php
	include_once(APPPATH.'/views/header.php');
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

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/community/get_bbs_mylist", { bbs: $('#bbs').val(), page: vPage, spart: $('#spart').val(), skeyword: $('#skeyword').val() })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$('#bseq').val('');
				$('#jdata').val('');
				$('#bjob').val('');
				
				$('#area_nowpage').text(jdata.now_page);
				$('#area_totalpage').text(jdata.page_total);
				$("#area_item tr:not(:first)").remove();

				var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);
				
				if ( jdata.item.length > 0 ) {
					var file_icon = '<img src="/app/views/images/common/icon_download.gif" alt="다운로드">';
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						var reply_step = '';
						var reply_step_image = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c">' + line_num + '</td>';
						if (parseInt(jdata.item[key].blevel) > 0) {
							reply_step = ' style="padding-left:' + ((parseInt(jdata.item[key].blevel) - 1) * 1.2) + 'em;"';
							reply_step_image = ' <img src="/app/views/images/common/icon_rep.gif" alt="답글"> ';
						}
						tr_html += '<td' + reply_step + '>' + reply_step_image + '<a href="javascript:get_record(' + jdata.item[key].seq + ');" class="btn_diapup158">' + jdata.item[key].title + '</a></td>';
						if (jdata.item[key].cnt_file > 0)
							tr_html += '<td class="c">' + file_icon + '</td>';
						else
							tr_html += '<td class="c">&nbsp;</td>';
						tr_html += '<td class="c">' + jdata.item[key].wdate.substring(0, 10) + '</td>';
						tr_html += '</tr>';
						
						$("#area_item").append( tr_html );
						
						line_num--;
					});
				
					page_navigation(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="4">데이터가 없습니다.</td>';
					tr_html += '</tr>';
					
					$("#area_item").append( tr_html );
				}
			}
			else if (jdata.status == "fail") {
				alert('자료 목록 읽기에 싫패하였습니다.');
			}
  		}, "json");
}
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_1_8"><img src="/app/views/images/common/img_subtitle1_8.gif" alt="MEMBER" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">MEMBER</a> <span>&gt;</span> <strong>내 질문과 답변</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
        	<p><strong><?php echo $session_id; ?></strong>님의 질문과 답변에작성한 게시물입니다.</p>
            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbssearchbox1"><input type="hidden" id="now_page" value="1" /><input type="hidden" id="spart" value="" /><input type="hidden" id="skeyword" value="" />

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
                    <div class="btntype2"><?php if ($bbs_write && $session_id != "") { ?><button type="button" class="st1" onclick="go_write('bbs2');">작성</button><? } ?></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
        

        </div>
        <!--/본문 -->            

    </div>
</div>

<?php
//게시판 공통 Layer Popup
include_once(APPPATH.'/views/community/include_pop.php');
?>

<!-- /contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>