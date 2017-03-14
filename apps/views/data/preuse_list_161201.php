<?php
	include_once(APPPATH.'/views/header.php');
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>

<script type="text/javascript">
<!--
$(function() {
	$(document).on("keyup", "input:text[numberdecimalOnly]", function() {$(this).val( $(this).val().replace(/[^0-9.]/gi,"") );});
	$('#dbtn_diapup131').click(function(){
		var r = confirm("정말삭제하시겠습니까?");
		if (r == true) {

		var bufferString = [];
			$("input[name=cbxseq]:checked").each(function(){
					bufferString.push($(this).val());
			})
			var bf = bufferString.join(',');
			console.log(bf);
			$.post('/sdata/delete_preuse',{seq:bf}).
			done(function(){
				location.reload();
			})

		} else {

		}
	})

	list_reset();

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

	$("#excelform").submit(function(e)
	{
		var formObj = $(this);
		var formURL = formObj.attr("action");
		var formData = new FormData(this);
		$.ajax({
			url: '/sdata/upload_excel_preuse',
			type: 'POST',
			data:  formData,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
			success: function(data, textStatus, jqXHR) {
				$( "#diapup137" ).dialog( "close" );
				$( "#diapup130" ).dialog( "close" );
				$( "#diapup127" ).dialog( "close" );
				list_reload(1);
				list_reload2(1);
				alert(data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert("입력에 오류가 발생되었습니다.\n\n다시 확인하여 주세요.");
			}
		});
		e.preventDefault(); //Prevent Default action.
		e.unbind();
	});
});

function list_reset() {
	$("select[name='lproject'] option").remove();
	$("select[name='lzone'] option").remove();
	$("<option></option>").attr("selected", true).text("::공구선택::").attr("value", "0").appendTo("select[name='lzone']");
	$("select[name='lbuild'] option").remove();
	$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='lbuild']");
	$("select[name='lstep1'] option").remove();
	$("<option></option>").attr("selected", true).text("::1차공정선택::").attr("value", "0").appendTo("select[name='lstep1']");
	$("select[name='lstep2'] option").remove();
	$("<option></option>").attr("selected", true).text("::2차공정선택::").attr("value", "0").appendTo("select[name='lstep2']");
	$("select[name='lstep3'] option").remove();
	$("<option></option>").attr("selected", true).text("::3차공정선택::").attr("value", "0").appendTo("select[name='lstep3']");
	$("select[name='pstep1'] option").remove();
	$("<option></option>").attr("selected", true).text("::1차공정선택::").attr("value", "0").appendTo("select[name='pstep1']");
	$("select[name='pstep2'] option").remove();
	$("<option></option>").attr("selected", true).text("::2차공정선택::").attr("value", "0").appendTo("select[name='pstep2']");
	$("select[name='pstep3'] option").remove();
	$("<option></option>").attr("selected", true).text("::3차공정선택::").attr("value", "0").appendTo("select[name='pstep3']");

	$("select[name='pproject'] option").remove();
	$("select[name='pzone'] option").remove();
	$("<option></option>").attr("selected", true).text("::공구선택::").attr("value", "0").appendTo("select[name='pzone']");
	$("select[name='pbuild'] option").remove();
	$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='pbuild']");

	load_listproject();
	load_liststep();
}

function load_listproject() {
	$.post( '/usedata/load_listproject', {})
		.done(function( jdata ) {
			if (jdata.status == "success") {
			//	alert('자료 ' + alert_text + '에 성공하였습니다.');
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						$("<option></option>").attr("selected", false).text(jdata.item[key].prjname).attr("value", jdata.item[key].seq).appendTo("select[name='lproject']");
						$("<option></option>").attr("selected", false).text(jdata.item[key].prjname).attr("value", jdata.item[key].seq).appendTo("select[name='pproject']");
					});

					load_listzone($('#lproject').val(), 'lzone', 'lbuild', '');
					load_listzone($('#pproject').val(), 'pzone', 'pbuild', '');
					load_changelist();
				}
			}
			else if (data.status == "fail") {
				alert('자료 초기화에 싫패하였습니다.');
			}
  		}, "json");
}

function load_listzone(vVal, vStep2, vStep3, vInit) {
	$.post( '/usedata/load_listzone', { project: vVal })
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$("select[name='" + vStep2 + "'] option").remove();
				$("<option></option>").attr("selected", true).text("::공구선택::").attr("value", "0").appendTo("select[name='" + vStep2 + "']");
				$("select[name='" + vStep3 + "'] option").remove();
				$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='" + vStep3 + "']");

				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						$("<option></option>").attr("selected", false).text(jdata.item[key].zone_name).attr("value", jdata.item[key].seq).appendTo("select[name='" + vStep2 + "']");
					});
					if (vInit != "") {
						$('#'+vStep2).val(vInit);
					}
				}

				if (vStep3 == 'lbuild') {
					load_changelist();
				}
			}
			else if (data.status == "fail") {
				alert('자료 초기화에 싫패하였습니다.');
			}
  		}, "json");
}

function load_listbuild(vVal, vStep1, vStep3, vInit) {
	if (vVal != '') {
		$.post( '/usedata/load_listbuilding', { project: vStep1, zone: vVal })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					$("select[name='" + vStep3 + "'] option").remove();
					$("<option></option>").attr("selected", true).text("::건축물선택::").attr("value", "0").appendTo("select[name='" + vStep3 + "']");

					if ( jdata.item.length > 0 ) {
						$.each(jdata.item, function(key,state){
							$("<option></option>").attr("selected", false).text(jdata.item[key].bname).attr("value", jdata.item[key].seq).appendTo("select[name='" + vStep3 + "']");
						});
						if (vInit != "") {
							$('#'+vStep3).val(vInit);
						}
					}

					if (vStep3 == 'lbuild') {
						load_changelist();
					}
				}
				else if (data.status == "fail") {
					alert('자료 초기화에 싫패하였습니다.');
				}
	  		}, "json");
	  }
}

function load_changelist() {
	list_reload(1);
	list_reload2(1);
}

function load_liststep() {
	$.post( '/process/load_liststep1', {})
		.done(function( jdata ) {
			if (jdata.status == "success") {
			//	alert('자료 ' + alert_text + '에 성공하였습니다.');
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						$("<option></option>").attr("selected", false).text(jdata.item[key].process).attr("value", jdata.item[key].seq).appendTo("select[name='lstep1']");
						$("<option></option>").attr("selected", false).text(jdata.item[key].process).attr("value", jdata.item[key].seq).appendTo("select[name='pstep1']");
					});
				}
			}
			else if (data.status == "fail") {
				alert('자료 초기화에 싫패하였습니다.');
			}
  		}, "json");
}

function load_liststep2(vVal, vStep2, vStep3, vInit) {
	if (vVal != "0") {
		$.post( '/process/load_liststep2', { step1: vVal })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					$("select[name='" + vStep2 + "'] option").remove();
					$("<option></option>").attr("selected", true).text("::2차공정선택::").attr("value", "0").appendTo("select[name='" + vStep2 + "']");
					$("select[name='" + vStep3 + "'] option").remove();
					$("<option></option>").attr("selected", true).text("::3차공정선택::").attr("value", "0").appendTo("select[name='" + vStep3 + "']");

					if ( jdata.item.length > 0 ) {
						$.each(jdata.item, function(key,state){
							$("<option></option>").attr("selected", false).text(jdata.item[key].process).attr("value", jdata.item[key].step2).appendTo("select[name='" + vStep2 + "']");
						});
						if (vInit != "") {
							$('#'+vStep2).val(vInit);
						}
					}

					if (vStep3 == 'lstep3') {
						load_changelist();
					}
				}
				else if (data.status == "fail") {
					alert('자료 초기화에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function load_liststep3(vVal, vStep1, vStep3, vInit) {
	if (vVal != "0") {
		$.post( '/process/load_liststep3', { step1: vStep1, step2: vVal })
			.done(function( jdata ) {
				if (jdata.status == "success") {
					$("select[name='" + vStep3 + "'] option").remove();
					$("<option></option>").attr("selected", true).text("::3차공정선택::").attr("value", "0").appendTo("select[name='" + vStep3 + "']");

					if ( jdata.item.length > 0 ) {
						$.each(jdata.item, function(key,state){
							$("<option></option>").attr("selected", false).text(jdata.item[key].process).attr("value", jdata.item[key].step3).appendTo("select[name='" + vStep3 + "']");
						});
						if (vInit != "") {
							$('#'+vStep3).val(vInit);
						}
					}

					if (vStep3 == 'lstep3') {
						load_changelist();
					}
				}
				else if (data.status == "fail") {
					alert('자료 초기화에 싫패하였습니다.');
				}
	  		}, "json");
	}
}

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/sdata/get_preuse_list", {
			page: vPage,
			gubun: 0,
			project: $('#lproject').val(),
			zone: $('#lzone').val(),
			build: $('#lbuild').val(),
			step1: $('#lstep1').val(),
			step2: $('#lstep2').val(),
			step3: $('#lstep3').val(),
			recycle: 'all'
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
						tr_html += '<td class="btn_diapup127"><a href="javascript:get_record(' + jdata.item[key].seq + ');">' + jdata.item[key].jname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].jstandard + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jvolume + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].weight + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].pre_use + '</td>';
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
	$.post( "/sdata/get_preuse_list", {
			page: vPage,
			gubun: 1,
			project: $('#lproject').val(),
			zone: $('#lzone').val(),
			build: $('#lbuild').val(),
			step1: $('#lstep1').val(),
			step2: $('#lstep2').val(),
			step3: $('#lstep3').val(),
			recycle: 'all'
		})
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
						tr_html += '<td class="c"><input type="checkbox" name="cbxseq2" value="' + jdata.item[key].seq + '" /></td>';
						tr_html += '<td class="c">' + line_num + '</td>';
						tr_html += '<td class="btn_diapup127"><a href="javascript:get_record(' + jdata.item[key].seq + ');">' + jdata.item[key].jname + '</a></td>';
						tr_html += '<td class="c">' + jdata.item[key].jstandard + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].jvolume + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].weight + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].pre_use + '</td>';
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

function get_record(vRecord) {
	$.post( "/sdata/get_preuse_record", { record: vRecord })
		.done(function( jdata ) {
			$( "#diapup122" ).dialog( "close" );

			$('#pjseq').val(vRecord);
			if (jdata.status == "success") {
				if (jdata.gubun == "0") {
					init_pop_infojajae(jdata.preuse, jdata.info, jdata.occurrence);
				} else if (jdata.gubun == "1") {
					init_pop_infomachine(jdata.preuse, jdata.info, jdata.occurrence);
				}
			}
			else if (jdata.status == "fail") {
				alert('Data 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function get_record_modify(vRecord) {
	$.post( "/sdata/get_preuse_record", { record: vRecord })
		.done(function( jdata ) {
			$( "#diapup122" ).dialog( "close" );
			$( "#diapup127" ).dialog( "close" );

			// $('#pjseq').val(vRecord);
			if (jdata.status == "success") {
				$('#pproject').val(jdata.preuse.project_seq);
				load_listzone(jdata.preuse.project_seq, 'pzone', 'pbuild', jdata.preuse.zone_seq);
				load_listbuild(jdata.preuse.zone_seq, $('#pproject').val(), 'pbuild', jdata.preuse.build_seq);
//				alert('zone_seq : ' + jdata.preuse.zone_seq + ', build_seq : ' + jdata.preuse.build_seq);
				$('#pstep1').val(jdata.preuse.step1);
				load_liststep2(jdata.preuse.step1, 'pstep2', 'pstep3', jdata.preuse.step2);
				load_liststep3(jdata.preuse.step2, $('#pstep1').val(), 'pstep3', jdata.preuse.step3);


				lcidb_jajae_readonly(false);

				$('#jname').val(jdata.preuse.jname);
				$('#jstandard').val(jdata.preuse.jstandard);
				$('#jvolume').val(jdata.preuse.jvolume);
				$('#junit').val(jdata.preuse.junit);
				if (jdata.preuse.juseyn == "0") {
					$('input:radio[name=juseyn]').filter('[value=N]').prop('checked', true);
				} else {
					$('input:radio[name=juseyn]').filter('[value=Y]').prop('checked', true);
				}

				change_file(false);

				if (jdata.gubun == "0") {
					 $('#lcidb_gubun').val('jajae');

					$("#diapup130").dialog({
				        title: 'Pre-Use 자재정보 수정'
				    });

				    if (jdata.preuse.jajae_seq != "0") {
						$('#jseq').val(jdata.preuse.jajae_seq);
						lcidb_jajae_readonly(true);
					}
					$( "#diapup130" ).dialog( "open" );
				} else if (jdata.gubun == "1") {
					 $('#lcidb_gubun').val('machine');

					$("#diapup130").dialog({
				        title: 'Pre-Use 장비사용정보 수정'
				    });
					$( "#diapup130" ).dialog( "open" );
				}
			}
			else if (jdata.status == "fail") {
				alert('Data 읽기에 싫패하였습니다.');
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

function checkbox_checkall2() {
	var check_status = true;
	if ($('#all_record_select2').is(':checked') == false) {
		check_status = false;
	}
	$("input[name=cbxseq2]:checkbox").each(function() {
		$(this).attr("checked", check_status);
	});
}

function init_pop_jajae() {
	$("select[name='pstep2'] option").remove();
	$("<option></option>").attr("selected", true).text("::2차공정선택::").attr("value", "0").appendTo("select[name='pstep2']");
	$("select[name='pstep3'] option").remove();
	$("<option></option>").attr("selected", true).text("::3차공정선택::").attr("value", "0").appendTo("select[name='pstep3']");

	change_file(false);

	$('#jname_area').html('재료명');
	$('#jstandard_area').html('재료규격');
	$('#jvolume_area').html('재료수량');

	$('#jname').val('');
	$('#jstandard').val('');
	$('#jvolume').val('');
	$('#junit').val('');
	$('input:radio[name=juseyn]').filter('[value=Y]').prop('checked', true);
	$("#diapup130").dialog({
        title: 'Pre-Use 자재정보 등록'
    });

    $('#lcidb_gubun').val('jajae');
}

function  init_pop_machine() {
	$("select[name='pstep2'] option").remove();
	$("<option></option>").attr("selected", true).text("::2차공정선택::").attr("value", "0").appendTo("select[name='pstep2']");
	$("select[name='pstep3'] option").remove();
	$("<option></option>").attr("selected", true).text("::3차공정선택::").attr("value", "0").appendTo("select[name='pstep3']");

	change_file(false);

	$('#jname_area').html('장비명');
	$('#jstandard_area').html('장비규격');
	$('#jvolume_area').html('사용시간');

	$('#jname').val('');
	$('#jstandard').val('');
	$('#jvolume').val('');
	$('#junit').val('');
	$('input:radio[name=juseyn]').filter('[value=Y]').prop('checked', true);
	$("#diapup130").dialog({
        title: 'Pre-Use 장비사용정보 등록'
    });

    $('#lcidb_gubun').val('machine');
}

function pop_jajae_insert() {
	init_pop_jajae();

	$( "#diapup130" ).dialog( "open" );
}

function pop_machine_insert() {
	init_pop_machine();

	$( "#diapup130" ).dialog( "open" );
}

function pop_lcidb() {
	var vGubun =  $('#lcidb_gubun').val();

	if (vGubun == 'jajae') {
		$('#stext').attr("placeholder", "LCI DB 품목명을 입력하세요.");
		$('#parea_tit1').html('재료명');
		$('#parea_tit2').html('규격');
		$('#parea_tit3').html('Co2배출량');
		$('#parea_tit4').html('무게');
	} else if (vGubun == 'machine') {
		$('#stext').attr("placeholder", "LCI DB 장비명을 입력하세요.");
		$('#parea_tit1').html('장비명');
		$('#parea_tit2').html('규격');
		$('#parea_tit3').html('Co2배출량');
		$('#parea_tit4').html('연비');
	}
	$('#stext').val('');
	$("#search_area tr:not(:first)").remove();
	$( "#diapup137" ).dialog( "open" );
}

function search_lcidb() {
	if ($('#stext').val() == "") {
		alert('검색어를 입력하여 주십시오.');
		$('#stext').focus();
		return;
	}

	var run_url = "/admin/get_lcijajae_search";
	if ($('#lcidb_gubun').val() != 'jajae') {
		run_url = "/admin/get_lcimachine_search";
	}
	$.post( run_url, { search: $('#stext').val() })
		.done(function( jdata ) {
			if (jdata.status == "success") {
			//	alert('자료 ' + alert_text + '에 성공하였습니다.');
				$("#search_area tr:not(:first)").remove();
				if ( jdata.item.length > 0 ) {
					$.each(jdata.item, function(key,state){
						var tr_html = '';
						tr_html += '<tr class="mobg">';
						tr_html += '<td class="c">' + jdata.item[key].vname + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].vstandard + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].vco2 + '</td>';
						tr_html += '<td class="c">' + jdata.item[key].vweight + '</td>';
						tr_html += '<td class="c"><button class="st1" onclick="search_select(' + jdata.item[key].seq + ',\'' + jdata.item[key].vname + '\',\'' + jdata.item[key].vstandard + '\',\'' + jdata.item[key].vunit + '\');">선택</button></td>';
						tr_html += '</tr>';

						$("#search_area").append( tr_html );
					});
				} else {
					var tr_html = '';
					tr_html += '<tr class="mobg">';
					tr_html += '<td class="c" colspan="5">데이터가 없습니다.</td>';
					tr_html += '</tr>';

					$("#search_area").append( tr_html );
				}
			}
			else if (data.status == "fail") {
				alert('자료 검색에 싫패하였습니다.');
			}
  		}, "json");
}

function search_select(vSequence, vName, vStandard, vUnit) {
	$('#jseq').val(vSequence);
	$('#jname').val(vName);
	$('#jstandard').val(vStandard);
	$('#junit').val(vUnit);

	$( "#diapup137" ).dialog( "close" );

	lcidb_jajae_readonly(true);

	$('#jvolume').focus();
}

function lcidb_jajae_readonly(vFlag) {
	if (vFlag == true) {
		$('#jname').prop('readonly', true);
		$('#jstandard').prop('readonly', true);
	} else if (vFlag == false) {
		$('#jseq').val('');
		$('#jname').val('');
		$('#jstandard').val('');
		$('#junit').val('');

		$('#jname').prop('readonly', false);
		$('#jstandard').prop('readonly', false);
	}
}

function preuse_check() {
	if ($('#pzone').val() == "0") {
		alert('공구를 선택하여 주십시오.');
		$('#pzone').focus();
		return;
	}
	if ($('#pbuild').val() == "0") {
		alert('건물을 선택하여 주십시오.');
		$('#pzone').focus();
		return;
	}
	if ($('#pstep1').val() == "0") {
		alert('1차 공정을 선택하여 주십시오.');
		$('#pstep1').focus();
		return;
	}

	var vGubun =  $('#lcidb_gubun').val();
	var action_url = '/sdata/insert_preuse';
	if (vGubun == "jajae") {
		if ($('#jname').val() == "") {
			alert('재료명을 입력하여 주십시오.');
			$('#jname').focus();
			return;
		}
		if ($('#jstandard').val() == "") {
			alert('재료규격을 입력하여 주십시오.');
			$('#jstandard').focus();
			return;
		}
		if ($('#jvolume').val() == "") {
			alert('재료수량을 입력하여 주십시오.');
			$('#jvolume').focus();
			return;
		}
		if ($('#junit').val() == "") {
			alert('단위를 입력하여 주십시오.');
			$('#junit').focus();
			return;
		}
	} else if (vGubun == "machine") {
		if ($('#jname').val() == "") {
			alert('장비명을 입력하여 주십시오.');
			$('#jname').focus();
			return;
		}
		if ($('#jstandard').val() == "") {
			alert('장비규격을 입력하여 주십시오.');
			$('#jstandard').focus();
			return;
		}
		if ($('#jvolume').val() == "") {
			alert('사용시간을 입력하여 주십시오.');
			$('#jvolume').focus();
			return;
		}
		if ($('#junit').val() == "") {
			alert('단위를 입력하여 주십시오.');
			$('#junit').focus();
			return;
		}
	}
	var juseyn = "0";
	if ($('#juseyn1').is(':checked') == true) {
		juseyn = "1";
	}
	$.post( action_url, {
			jajae: $('#jseq').val(),
			jname: $('#jname').val(),
			jstandard: $('#jstandard').val(),
			jvolume: $('#jvolume').val(),
			junit: $('#junit').val(),
			juseyn: juseyn,
			lproject: $('#pproject').val(),
			lzone: $('#pzone').val(),
			lbuild: $('#pbuild').val(),
			pstep1: $('#pstep1').val(),
			pstep2: $('#pstep2').val(),
			pstep3: $('#pstep3').val(),
			gubun: vGubun,
			pjseq: $('#pjseq').val()
		})
		.done(function( jdata ) {
			if (jdata.status == "success") {
				$( "#diapup137" ).dialog( "close" );
				$( "#diapup130" ).dialog( "close" );
				$( "#diapup127" ).dialog( "close" );

				if ($('#pjseq').val() != '') {
					list_reload($('#now_page').val());
					list_reload2($('#now_page2').val());
				} else {
					list_reload(1);
					list_reload2(1);
				}
			}
			else if (data.status == "fail") {
				alert('자료 저장에 싫패하였습니다.');
			}
  		}, "json");
}

function init_pop_infojajae(jpreuse, jinfo, joccurrence) {
	$('#info_title_area1').html('자재사용정보');
	$('#info_title_area2').html('자재기준정보');

	$('#jarea_01').html(jpreuse.jname);
	$('#jarea_02').html(jpreuse.jstandard);
	$('#jarea_03').html(jpreuse.jvolume);
	$('#jarea_04').html(jpreuse.junit);
	$('#jarea_05').html(jpreuse.weight);

	$('#jarea_07').html(jinfo.junit);
	$('#jarea_08').html(jinfo.jweight);
	$('#jarea_09').html(jinfo.jjugi);
	$('#jarea_10').html(jinfo.jsuseon);
	if (jinfo.jrecycle == "0")
		$('#jarea_11').html("폐기");
	else if (jinfo.jrecycle == "1")
		$('#jarea_11').html("재활용");
	$('#jarea_12').html(jinfo.jculceo);

	init_pop_maketable(jpreuse, jinfo, joccurrence);

	$('#info_table_j1').show();
	$('#info_table_j2').show();
	$('#info_table_m1').hide();
	$('#info_table_m2').hide();

	change_tab(1);

	$( "#diapup127" ).dialog( "open" );
}

function init_pop_infomachine(jpreuse, jinfo, joccurrence) {
	$('#info_title_area1').html('장비사용정보');
	$('#info_title_area2').html('장비기준정보');

	$('#marea_01').html(jpreuse.jname);
	$('#marea_02').html(jpreuse.jstandard);
	$('#marea_03').html(jpreuse.jvolume);
	$('#marea_04').html(jpreuse.junit);
	$('#marea_05').html(jpreuse.weight);

	$('#marea_07').html(jinfo.munit);
	$('#marea_08').html(jinfo.mileage);
	$('#marea_09').html(jinfo.mculceo);

	init_pop_maketable(jpreuse, jinfo, joccurrence);

	$('#info_table_j1').hide();
	$('#info_table_j2').hide();
	$('#info_table_m1').show();
	$('#info_table_m2').show();

	change_tab(1);

	$( "#diapup127" ).dialog( "open" );
}

function init_pop_maketable(jpreuse, jinfo, joccurrence) {

	$("#area_table1 tr:not(:first)").remove();
	var tr_html = '';
	tr_html += '<tr>';
	tr_html += '<td class="c">' + jpreuse.weight + '</td>';
	tr_html += '<td class="c">' + jpreuse.pre_use + '</td>';
	tr_html += '</tr>';
	$("#area_table1").append( tr_html );

	$("#area_table2 tr:not(:first)").remove();
	var tr_html = '';
	if ( joccurrence.length > 0 ) {
		$.each(joccurrence, function(key,state){
			if ((key + 1) < joccurrence.length) {
				tr_html += '<tr>';
				tr_html += '<td class="c">' + joccurrence[key].byear + '년 ' + joccurrence[key].bmonth + '월</td>';
				tr_html += '<td class="c">' + joccurrence[key].pre_use + '</td>';
				tr_html += '</tr>';
			}
		});
	} else {
		tr_html += '<tr>';
		tr_html += '<td class="c" colspan="2">데이터가 없습니다.</td>';
		tr_html += '</tr>';
	}
	$("#area_table2").append( tr_html );

	$("#area_table3 tr:not(:first)").remove();
	var tr_html = '';
	tr_html += '<tr>';
	tr_html += '<td class="c">' + jpreuse.post_use + '</td>';
	tr_html += '</tr>';
	$("#area_table3").append( tr_html );

}

function change_tab(vIdx) {
	$('#area_tab_all').removeClass("datalisttapmenu1");
	$('#area_tab_all').removeClass("datalisttapmenu2");
	$('#area_tab_all').removeClass("datalisttapmenu3");

	$('#area_tab1').removeClass("strong");
	$('#area_tab2').removeClass("strong");
	$('#area_tab3').removeClass("strong");

	$('#area_table1').hide();
	$('#area_table2').hide();
	$('#area_table3').hide();

	$('#area_tab_all').addClass("datalisttapmenu"+vIdx);
	$('#area_tab'+vIdx).addClass("strong");
	$('#area_table'+vIdx).show();
}

function change_file(vFlag) {
	if (vFlag == false) {
		$('#table_info').show();
		$('#table_file').hide();
		$('#btn_info').show();
		$('#btn_file').hide();

		var temp_html = '<strong>직접등록</strong> &nbsp; |  &nbsp; <a href="javascript:change_file(true);" id="obtn_diapup122">Excel등록</a>';
		$('#ipt_area0').html(temp_html);
	} else {
		$('#table_info').hide();
		$('#table_file').show();
		$('#btn_info').hide();
		$('#btn_file').show();

		var temp_html = '<a href="javascript:change_file(false);" id="obtn_diapup123">직접등록</a> &nbsp; |  &nbsp; <strong>Excel등록</strong>';
		$('#ipt_area0').html(temp_html);
	}
}

function verify_excel() {
	if ($('#pzone').val() == "0") {
		alert('공구를 선택하여 주십시오.');
		$('#pzone').focus();
		return;
	}
	if ($('#pbuild').val() == "0") {
		alert('건물을 선택하여 주십시오.');
		$('#pzone').focus();
		return;
	}
	if ($('#pstep1').val() == "0") {
		alert('1차 공정을 선택하여 주십시오.');
		$('#pstep1').focus();
		return;
	}

	var vGubun =  $('#lcidb_gubun').val();

	if ($('#excelupload').val() == '') {
		alert('엑셀 파일을 첨부하여 주세요.\n\n.xlsx 파일은 안됩니다.');
		return;
	}
	var extension = $('#excelupload').val().substring(($('#excelupload').val().lastIndexOf('.') + 1), $('#excelupload').val().length);
	extension = extension.toLowerCase();
	if (extension != "xls") {
		alert('xls 파일만 업로드 가능합니다.');
		return;
	}
	$('#fgubun').val($('#lcidb_gubun').val());
	$('#fproject').val($('#pproject').val());
	$('#fzone').val($('#pzone').val());
	$('#fbuild').val($('#pbuild').val());
	$('#fstep1').val($('#pstep1').val());
	$('#fstep2').val($('#pstep2').val());
	$('#fstep3').val($('#pstep3').val());

	$("#excelform").submit();
}
-->

$('#dbtn_diapup131').click(function(){
		console.log($([name=cbxseq]));
})
</script>

<!-- contents -->
<div id="containerwrap">
	<div id="container">

		<h2 class="titlebox"><span class="title_2_3"><img src="/app/views/images//common/img_subtitle2_3.gif" alt="USE DATA" /></span></h2>

        <div class="path"><p><a href="#;"><img src="/app/views/images//common/icon_loc.gif" alt="HOME" /></a> <span>&gt;</span> <a href="#;">USE DATA</a> <span>&gt;</span> <strong>자료관리</strong></p></div>

        <!-- 본문 -->
        <div class="contentswrap">
        	<p><select id="lproject" name="lproject" onchange="load_listzone(this.value, 'lzone', 'lbuild', '');">
            	<option>프로젝트 선택</option>
            </select>

            <select id="lzone" name="lzone" onchange="load_listbuild(this.value, $('#lproject').val(), 'lbuild', '');">
            	<option>공구 선택</option>
            </select>
            <select id="lbuild" name="lbuild" onchange="load_changelist();">
            	<option>건축물 선택</option>
            </select></p>

            <dl id="datalisttapmenu" class="datalisttapmenu1">
            	<dd class="menu1"><a href="/sdata/preuse_list" class="strong">Pre-use</a></dd>
                <dd class="menu2"><a href="/sdata/use_list">Use</a></dd>
                <dd class="menu3"><a href="/sdata/postuse_list">Post-use</a></dd>
            </dl>

            <p class="bbstitle1">자재정보</p>
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage">1</strong>/<span id="area_totalpage">1</span></dd>
                <dd class="bbsselectbox1"><input type="hidden" id="now_page" value="1" />
                <form name="" method="" action="">
                    <fieldset>
                    <legend>공정선택</legend>
                    <select name="lstep1" id="lstep1" onchange="load_liststep2(this.value, 'lstep2', 'lstep3', '');">
                    	<option selected="selected">1차 공정선택</option>
                    </select>
                    <select name="lstep2" id="lstep2" onchange="load_liststep3(this.value, $('#lstep1').val(), 'lstep3', '');">
                    	<option selected="selected">2차 공정선택</option>
                    </select>
                    <select name="lstep3" id="lstep3" onchange="load_changelist();">
                    	<option selected="selected">3차 공정선택</option>
                    </select>
                    </fieldset>
                </form>
                </dd>
                <dd class="bbslistbox1"><input type="hidden" id="pjseq">
                    <table id="area_item" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="7%" />
                        <col width="*" />
                        <col width="250" />
                        <col width="100" />
                        <col width="100" />
                        <col width="100" />
                        </colgroup>
                        <tr>
                            <th class="tl"><input type="checkbox" id="all_record_select" onclick="checkbox_checkall();" /></th>
                            <th>No.</th>
                            <th>자재명</th>
                            <th>자재규격</th>
                            <th>수량</th>
                            <th>무게(Kg)</th>
                            <th class="tr">Co2 배출량(Kgco2)</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" id="dbtn_diapup131" class="st5">삭제</button></div>
                    <div class="btntype2"><button type="button" id="btn_diapup130" class="st3" onclick="pop_jajae_insert();">등록</button></div>
                    <div class="pageing" id="area_paging"></div>
                </dd>
            </dl>


            <p class="bbstitle2">장비사용정보</p>
            <dl class="tablebox1">
            	<dd class="bbslisttotal">Total : <strong id="area_nowpage2">1</strong>/<span id="area_totalpage2">1</span></dd>
                <dd><input type="hidden" id="now_page2" value="1" />
                    <table id="area_item2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                        <colgroup>
                        <col width="7%" />
                        <col width="7%" />
                        <col width="*" />
                        <col width="100" />
                        <col width="100" />
                        <col width="150" />
                        <col width="100" />
                        </colgroup>
                        <tr>
                            <th class="tl"><input type="checkbox" id="all_record_select2" onclick="checkbox_checkall2();" /></th>
                            <th>No.</th>
                            <th>장비명</th>
                            <th>장비규격</th>
                            <th>사용시간(H)</th>
                            <th>Energy 사용량</th>
                            <th class="tr">Co2 배출량(KgCO2)</th>
                        </tr>
                    </table>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype1"><button type="button" id="dbtn_diapup133" class="st5">삭제</button></div>
                    <div class="btntype2"><button type="button" id="btn_diapup128" class="st3" onclick="pop_machine_insert();">등록</button></div>
                    <div class="pageing" id="area_paging2"></div>
                </dd>
                <dd class="bbsbtnbox1">
                    <div class="btntype3"><button class="st2">사용계산</button> <button class="st1">Co2 계산</button></div>
                </dd>
            </dl>

        </div>
        <!--//본문 -->

    </div>
</div>

<div id="diapup127">
<div class="contentswrap">
    <dl class="tablebox1">
        <dd>
	        <p id="info_title_area1" class="bbstitle1">자재사용정보</p>
            <table id="info_table_j1" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="20%" />
                <col width="35%" />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
                </colgroup>
                <tr>
                    <th class="tl">자재명</th>
                    <th>자재규격</th>
                    <th>수량</th>
                    <th>단위</th>
                    <th class="tr">무게(Kg)</th>
                </tr>
                <tr class="mobg">
                	<td class="c"><span id="jarea_01"></span></td>
                	<td class="c"><span id="jarea_02"></span></td>
                	<td class="c"><span id="jarea_03"></span></td>
                	<td class="c"><span id="jarea_04"></span></td>
                	<td class="c"><span id="jarea_05"></span></td>
                </tr>
            </table>
            <table id="info_table_m1" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="20%" />
                <col width="35%" />
                <col width="15%" />
                <col width="15%" />
                <col width="15%" />
                </colgroup>
                <tr>
                    <th class="tl">장비명</th>
                    <th>장비규격</th>
                    <th>사용시간(H)</th>
                    <th>단위</th>
                    <th class="tr">Energy 사용량</th>
                </tr>
                <tr class="mobg">
                	<td class="c"><span id="marea_01"></span></td>
                	<td class="c"><span id="marea_02"></span></td>
                	<td class="c"><span id="marea_03"></span></td>
                	<td class="c"><span id="marea_04"></span></td>
                	<td class="c"><span id="marea_05"></span></td>
                </tr>
            </table>
            <br />
            <p id="info_title_area2" class="bbstitle1">자재기준정보</p>
            <table id="info_table_j2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">배출기준</th>
                    <th>기준단위</th>
                    <th>무게기준(Kg)</th>
                    <th>수선주기(년)</th>
                    <th>수선율(%)</th>
                    <th>폐기/재활용</th>
                    <th class="tr">출처</th>
                </tr>
                <tr class="mobg">
                	<td class="c"><span id="jarea_06"></span></td>
                	<td class="c"><span id="jarea_07"></span></td>
                	<td class="c"><span id="jarea_08"></span></td>
                	<td class="c"><span id="jarea_09"></span></td>
                	<td class="c"><span id="jarea_10"></span></td>
                	<td class="c"><span id="jarea_11"></span></td>
                	<td class="c"><span id="jarea_12"></span></td>
                </tr>
            </table>
            <table id="info_table_m2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">배출기준</th>
                    <th>기준단위</th>
                    <th>연비</th>
                    <th class="tr">출처</th>
                </tr>
                <tr class="mobg">
                	<td class="c"><span id="marea_06"></span></td>
                	<td class="c"><span id="marea_07"></span></td>
                	<td class="c"><span id="marea_08"></span></td>
                	<td class="c"><span id="marea_09"></span></td>
                </tr>
            </table>
        </dd>
        <dd>
            <p class="bbstitle1">배출량정보</p>
            <dl id="area_tab_all" class="datalisttapmenu1">
                <dd class="menu1"><a href="#;" id="area_tab1" class="strong" onfocus="this.blur()" onclick="change_tab(1);">Pre-use</a></dd>
                <dd class="menu2"><a href="#;" id="area_tab2" onclick="change_tab(2);">유지보수</a></dd>
                <dd class="menu3"><a href="#;" id="area_tab3" onclick="change_tab(3);">Post-use</a></dd>
            </dl>
            <table id="area_table1" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">무게(Kg)</th>
                    <th class="tr">Co2배출량(KgCO2)</th>
                </tr>
                <tr>
                	<td class="c">1</td>
                	<td class="c">1000</td>
                </tr>
            </table>
            <table id="area_table2" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">예상시기</th>
                    <th class="tr">예상배출량</th>
                </tr>
                <tr>
                	<td class="c">2104-08-11</td>
                	<td class="c">1000</td>
                </tr>
            </table>
            <table id="area_table3" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>배출량</th>
                </tr>
                <tr>
                	<td class="c">999</td>
                </tr>
            </table>

        </dd>

        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" id="cbtn_diapup127">닫기</button> <button type="button" id="mbtn_diapup127" class="st3" onclick="get_record_modify($('#pjseq').val());">수정</button> <button type="button" class="st1" id="rbtn_diapup127">재계산</button> <button type="button" class="st5" id="dbtn_diapup127">삭제</button></div>
        </dd>
    </dl>
</div>
</div>

<div id="diapup130">
	<p class="p1"><select id="pproject" name="pproject" onchange="load_listzone(this.value, 'pzone', 'pbuild', '');">
		<option>경기도 시립박물관</option>
	</select> <select id="pzone" name="pzone" onchange="load_listbuild(this.value, $('#pproject').val(), 'pbuild', '');">
		<option>제1공구</option>
	</select> <select id="pbuild" name="pbuild">
		<option>보관함1</option>
	</select></p>
	<p class="p1"><select name="pstep1" id="pstep1" onchange="load_liststep2(this.value, 'pstep2', 'pstep3', '');">
		<option>1차 공정선택</option>
	</select> <select name="pstep2" id="pstep2" onchange="load_liststep3(this.value, $('#pstep1').val(), 'pstep3', '');">
		<option>2차 공정선택</option>
	</select> <select name="pstep3" id="pstep3">
		<option>3차 공정선택</option>
	</select></p>
	<p id="ipt_area0"><strong>직접등록</strong> &nbsp; |  &nbsp; <a href="javascript:change_file(true);" id="obtn_diapup123">Excel등록</a></p>
    <dl class="tablebox4_1">
        <dd>
            <table id="table_info" class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th><span id="jname_area">재료명</span></th>
                    <td><input class="inp2" name="jname" id="jname" maxlength="50" value="" /> <button type="button" id="pbtn_diapup130" class="stzoom2" onclick="pop_lcidb();">LCI DB 검색</button> <a href="javascript:lcidb_jajae_readonly(false);" class="del">X</a></td>
                </tr><input type="hidden" id="jseq">
                <tr>
                    <th><span id="jstandard_area">재료명</span></th>
                    <td><input class="inp1" name="jstandard" id="jstandard" maxlength="50" value="" /></td>
                </tr>
                <tr>
                    <th><span id="jvolume_area">재료명</span></th>
                    <td><input class="inp1" numberdecimalOnly="true" name="jvolume" id="jvolume" maxlength="15" value="" /></td>
                </tr>
                <tr>
                    <th>단위</th>
                    <td><input class="inp1" name="junit" id="junit" maxlength="10" value="" /></td>
                </tr>
                <tr>
                    <th>적용여부</th>
                    <td><label><input type="radio" id="juseyn1" name="juseyn" value="Y" checked>적용</label> <label><input type="radio" id="juseyn2" name="juseyn" value="N">미적용</label></td>
                </tr>
            </table>
            <table id="table_file" class="tablewritetype4" border="0" cellpadding="0" cellspacing="0" style="display:none;">
                <form id="excelform" name="excelform" method="post" enctype="multipart/form-data">
                <tr>
                    <th>파일첨부</th>
                    <td><input type="file" class="file1" name="excelupload" id="excelupload" /></td>
                </tr>
                <input type="hidden" id="fgubun" name="fgubun" value="">
                <input type="hidden" id="fproject" name="fproject" value=""><input type="hidden" id="fzone" name="fzone" value=""><input type="hidden" id="fbuild" name="fbuild" value="">
                <input type="hidden" id="fstep1" name="fstep1" value=""><input type="hidden" id="fstep2" name="fstep2" value=""><input type="hidden" id="fstep3" name="fstep3" value="">
                </form>
            </table>
        </dd>
        <dd class="bbsbtnbox1" id="btn_info">
            <div class="btntype3"><button type="button" class="st3" id="mbtn_diapup130" onclick="preuse_check();">저장</button> <button type="button" id="cbtn_diapup130" class="">취소</button></div>
        </dd>
        <dd class="bbsbtnbox1" id="btn_file" style="display:none;">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup157" onclick="verify_excel();">등록</button> <button type="button" id="cbtn_diapup130_1" class="">취소</button></div>
        </dd>
    </dl>
</div>

<div id="diapup137">
	<p><input class="inp1" id="stext" name="stext" placeholder="LCI DB 품목명을 입력하세요." /> <button class="stzoom2" onclick="search_lcidb();">검색</button></p>
    <dl class="tablebox1">
        <dd>
            <table id="search_area" class="tablelisttype1" border="0" cellpadding="0" cellspacing="0">
                <tr><input type="hidden" id="lcidb_gubun">
                    <th class="tl"><span id="parea_tit1">재료명</span></th>
                    <th><span id="parea_tit2">규격</span></th>
                    <th><span id="parea_tit3">Co2배출량(KgCO2)</span></th>
                    <th><span id="parea_tit4">무게(Kg)</span></th>
                    <th class="tr">선택</th>
                </tr>
                <tr class="mobg">
                    <td class="c">콘크리트 <%=i%></td>
                    <td class="c">m</td>
                    <td class="c">55<%=i%></td>
                    <td class="c">1<%=i%></td>
                    <td class="c"><button class="st1">선택</button></td>
                </tr>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" id="cbtn_diapup137" class="">닫기</button></div>
        </dd>
    </dl>
</div>

<!-- //contents -->

<!-- footer -->
<?php
include_once(APPPATH.'/views/bottom.php');
?>
