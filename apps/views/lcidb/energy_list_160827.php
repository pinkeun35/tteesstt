<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
	
<script type="text/javascript">
<!--
$(function() {
	list_reload(1);
});

function check_search() {
	$('#skeyword').val($('#stext').val());
	
	list_reload(1);
}

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/lcidb/get_energy_list", { page: vPage, skeyword: $('#skeyword').val() })
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
						tr_html += '<td class="c"><input type="checkbox" name="cbxseq" disabled="disabled" /></td>';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td>' + jdata.item[key].ename + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].eunit + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].eculceo + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].eco2 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].wdate.substring(0, 10) + '</td>';
						tr_html += '</tr>';
						
						$("#area_item").append( tr_html );
						
						line_num--;
					});
					
					$('#stext').val($('#skeyword').val());
				
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
-->
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_3_2"><img src="/app/views/images/common/img_subtitle3_2.gif" alt="LCI DB" /></span></h2>            

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">LCI DB</a> <span>&gt;</span> <strong>ENERGY</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
            
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbssearchbox1"><input type="hidden" id="now_page" value="1" /><input type="hidden" id="skeyword" value="" />
                    <fieldset>
                    <legend>검색</legend>
                    <input name="stext" id="stext" class="bbsinput1" placeholder="검색어를 입력하세요" />
                    <input type="image" src="/app/views/images/common/icon_zoom1.gif" class="sbtn" title="검색" onclick="check_search();" />
                    </fieldset>
                </dd>
                <dd class="bbslistbox1">
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <th class="tl"><input type="checkbox" disabled="disabled" /></th>
                            <th>No.
                            <th>Energy명</th>
                            <th>단위</th>
                            <th>출처</th>
                            <th>Co2배출량</th>
                            <th class="tr">등록일(수정일)</th>
                        </tr>
                        <!-- <tr class="mobg">
                            <td class="c"><input type="checkbox" /></td>
                            <td class="c"><%=i%></td>
                            <td class="c">경유</td>
                            <td class="c">㎏</td>
                            <td class="c">지식경제부</td>
                            <td class="c">100</td>
                            <td class="c">2015-06-30</td>
                        </tr> -->
                    </table>
                </dd>
        
                <dd class="bbsbtnbox1">
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>

        </div>
        <!--//본문 -->            

    </div>
</div>

<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>