<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->
<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>

<script type="text/javascript">
<!--
$(function() {
<?php
	if ($load_personal) {
		echo "	list_reload(1);"."\n";
	}
	if ($load_group) {
		echo "	list_reload2(1);"."\n";
	}
?>
	// form_reset();
});

function project_add(vGubun, vEnt) {
	document.location = "/usedata/project_insert/gubun/" + vGubun + '/eid/' + vEnt;
}

    function list_reload(vPage) {
        $('#now_page').val(vPage);
	$.post( "/usedata/get_project_person_list", { page: vPage })
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
						tr_html += '<td><a href="/usedata/project_detail/gubun/p/seq/' + jdata.item[key].seq + '" class="btn_diapup158">' + jdata.item[key].prjname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].location + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].date_end + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].charge_name + '</td>';
						tr_html += '<td class="c"> </td>';
						tr_html += '<td class="c"><button type="button" class="st3" onclick="location.href=\'/statistics/statistics_zone/project/' + jdata.item[key].seq + '\';">통계보기</button></td>';
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

function list_reload2(vPage) {
	$('#now_page2').val(vPage);
	$.post( "/usedata/get_project_group_list", { page: vPage })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$('#area_nowpage2').text(jdata.now_page);
				$('#area_totalpage2').text(jdata.page_total);
				$("#area_item2 tr:not(:first)").remove();

				var line_num = jdata.total_record - ((jdata.now_page - 1) * jdata.page_size);

				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td><a href="/usedata/project_detail/gubun/e/seq/' + jdata.item[key].seq + '" class="btn_diapup158">' + jdata.item[key].prjname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].location + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].date_end + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].charge_name + '</td>';
						tr_html += '<td class="c"> </td>';
						tr_html += '<td class="c"><button type="button" class="st3" onclick="location.href=\'/statistics/statistics_zone/project/' + jdata.item[key].seq + '\';">통계보기</button></td>';
						tr_html += '</tr>';

						$("#area_item2").append( tr_html );

						line_num--;
					});

					page_navigation2(jdata.now_page, jdata.page_total, jdata.page_size);
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="7">데이터가 없습니다.</td>';
					tr_html += '</tr>';

					$("#area_item2").append( tr_html );
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

		<h2 class="titlebox"><span class="title_2_1"><img src="/app/views/images/common/img_subtitle2_1.gif" alt="USE DATA" /></span></h2>

        <div class="path"><p><a href="#;"><img src="/app/views/images/common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">USE DATA</a> <span>&gt;</span> <strong>내 프로젝트</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
<?php
	if ($load_personal) {
?>
        	<p><strong><?php echo $this->session->userdata('lcco2_name'); ?></strong>님의 프로젝트</p>
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbslistbox1"><input type="hidden" id="now_page" value="1" />
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="*" />
                        <col width="*" />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="10%" />
                        </colgroup>
                        <tr>
                            <th class="tl">No.</th>
                            <th>프로젝트명</th>
                            <th>위치</th>
                            <th>완공(예정)일</th>
                            <th>담당자</th>
                            <th>수명</th>
                            <th class="tr">&nbsp;</th>
                        </tr>
                        <!-- <tr class="mobg">
                            <td class="c"><%=i %></td>
                            <td><a href="/project/prjdetail.jsp">세종시 국제도시 신축공사</a></td>
                            <td class="c">경기도 과천시 OO구</td>
                            <td class="c">2014.01.01</td>
                            <td class="c">홍순이</td>
                            <td class="c">30년</td>
                            <td class="c"><button type="button" class="st3">통계보기</button></td>
                        </tr> -->
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <!--div class="btntype1"><button type="button">목록</button></div-->
                    <div class="btntype2"><button type="button" class="st1" onclick="project_add('p', '');">새 프로젝트</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>
<?php
	}
	if ($load_group) {
?>
        	<p><strong><?php echo $group_name; ?></strong>님의 프로젝트</p>
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage2">1</strong>/<span id="area_totalpage2">1</span></dd>
                <dd class="bbslistbox1"><input type="hidden" id="now_page2" value="1" />
                    <table id="area_item2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="*" />
                        <col width="*" />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="10%" />
                        <col width="10%" />
                        </colgroup>
                        <tr>
                            <th class="tl">No.</th>
                            <th>프로젝트명</th>
                            <th>위치</th>
                            <th>완공(예정)일</th>
                            <th>담당자</th>
                            <th>수명</th>
                            <th class="tr">&nbsp;</th>
                        </tr>
                        <!-- <tr class="mobg">
                            <td class="c"><%=i %></td>
                            <td>><a href="/project/prjdetail.jsp">세종시 국제도시 신축공사</a></td>
                            <td class="c">경기도 과천시 OO구</td>
                            <td class="c">2014.01.01</td>
                            <td class="c">홍순이</td>
                            <td class="c">30년</td>
                            <td class="c"><button type="button" class="st3">통계보기</button></td>
                        </tr> -->
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <!--div class="btntype1"><button type="button">목록</button></div-->
                    <div class="btntype2"><button type="button" class="st1" onclick="project_add('e','<?php echo $session_group; ?>');">새 프로젝트</button></div>
                    <div class="pageing" id="area_paging2"></div>
                </dd>
            </dl>
<?php
	}
?>
        </div>
        <!--//본문 -->

    </div>
</div>
<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>
