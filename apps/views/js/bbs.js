function check_search() {
	$('#spart').val($('#sgubun').val());
	$('#skeyword').val($('#stext').val());
	
	list_reload(1);
}

function list_reload(vPage) {
	$('#now_page').val(vPage);
	$.post( "/community/get_bbs_list", { bbs: $('#bbs').val(), page: vPage, spart: $('#spart').val(), skeyword: $('#skeyword').val() })
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

function check_write() {
	if ($('#btitle').val() == '') {
		alert('제목을 입력하여 주십시오.');
		$('#btitle').val();
		return;
	}
	if ($('#nfile').val() == "1" && $('#bjob').val() == "write") {
		if ($('#fupload').val() == "") {
			alert("글 작성시 첨부할 파일을 선택하여 주십시오.");
			return;
		}
	}
	if ($('#fupload').val() != '') {
		var extension = $('#fupload').val().substring(($('#fupload').val().lastIndexOf('.') + 1), $('#fupload').val().length);
		extension = extension.toLowerCase();
		
		if (regext.test(extension) == false) {
			alert('첨부 가능한 파일이 아닙니다.\n\n다시 확인하여 주십시오.');
			return;
		}
	}
	editor[0].updateElement();
	if ($('#contents1').val() == "") {
		alert('내용을 입력하여 주십시오.');
		return;
	}
	
	$("#dataform").submit();
}

function get_record(vSeq) {
	$.post( '/community/get_bbs_record', { bbs: $('#bbs').val(), record: vSeq })
		.done(function( data ) {
			if (data.status == "success") {
				$('#bseq').val(vSeq);
				$('#jdata').val(JSON.stringify(data));
				print_view(data.item, data.edit);
			} else if (data.status == "fail") {
				alert('자료 읽기에 싫패하였습니다.');
			}
  		}, "json");
}

function close_write() {
	$( "#write_bbs_pop" ).dialog( "close" );
}

function print_view(info, editable) {
	$('#area_01').html(info.title);
	$('#area_02').html(info.uid);
	$('#area_03').html(info.wdate.substring(0,10));
	$('#area_05').html(info.contents);
	
	if (editable) {
		var btn_modify_text = '<button type="button" class="st3" id="mbtn_diapup177" onclick="go_modify(\'edit\');">수정</button>';
		var btn_delete_text = '<button type="button" class="st3" id="mbtn_diapup177" onclick="go_delete(' + info.seq + ');">삭제</button>';
		$('#area_a01').html(btn_modify_text);
		$('#area_a02').html(btn_delete_text);
	} else {
		$('#area_a01').html('');
		$('#area_a02').html('');
	}
	
	print_view_file(info.bfile, 'view', editable);
		
	$( "#view_bbs_pop" ).dialog( "open" );
}

function close_view() {
	$( "#view_bbs_pop" ).dialog( "close" );
}

function print_view_file(vFile, vGubun, vEdit) {
	if (vFile != "") {
		file_arr = vFile.split(",");
		var temp_html = "";
		for (var i=0; i<file_arr.length; i++) {
			fitem = file_arr[i].split("^");
			temp_html += '<a href="/uploads/'+$('#bbs').val()+'/'+fitem[1]+'" target="lcco2file">'+fitem[1]+'</a>';
			if (vGubun == "modify" && vEdit) {
				temp_html += ' <a href="javascript:delete_file(' + fitem[0] + ',\'' + fitem[1] + '\');"><img src="/app/views/images/common/icon_del1.gif" alt="삭제"/></a>';
			}
			temp_html += '<br />';
		}
		
		if (vGubun == "view") {
			$('#area_04').html(temp_html);
		} else if (vGubun == "modify") {
			$('#warea_03').html('<br /><br />'+temp_html);
		}
	} else {
		$('#area_04').html('');
		$('#warea_03').html('');
	}
}

function go_write() {
	$('#bseq').val('');
	$('#jdata').val('');
	$('#bjob').val('write');
	$('#btitle').val('');
	$('#fupload').val('');
	$('#contents1').val('');
	editor[0].setData($('#contents1').val());
	editor[0].updateElement();
	$('#warea_01').html($('#nwriter').val());
	$('#warea_02').html($('#ndate').val());
	$('#warea_03').html('');

	$("#write_bbs_pop").dialog({
        title: '게시글 등록'
    });
	$( "#write_bbs_pop" ).dialog( "open" );
}

function go_modify(vJob) {
	var jdata = jQuery.parseJSON($('#jdata').val());
	
	$('#bjob').val(vJob);
	if (vJob == "edit") {
		$('#btitle').val(jdata.item.title);
		$('#fupload').val('');
		$('#contents1').val(jdata.item.contents);
		editor[0].setData($('#contents1').val());
		editor[0].updateElement();
		$('#warea_01').html(jdata.item.uid);
		$('#warea_02').html(jdata.item.wdate.substring(0,10));
		
		print_view_file(jdata.item.bfile, 'modify', jdata.edit);

		$("#write_bbs_pop").dialog({
	        title: '게시글 수정'
	    });
	} else if (vJob == "reply") {
		$('#btitle').val('Re : '+jdata.item.title);
		$('#fupload').val('');
		$('#contents1').val('');
		editor[0].setData($('#contents1').val());
		editor[0].updateElement();
		$('#warea_01').html($('#nwriter').val());
		$('#warea_02').html($('#ndate').val());
		$('#warea_03').html('');
		
		$("#write_bbs_pop").dialog({
	        title: '게시글 답변쓰기'
	    });
	}
	
	close_view();

	$( "#write_bbs_pop" ).dialog( "open" );
}

function delete_file(vIdx, vFname) {
	var jdata = jQuery.parseJSON($('#jdata').val());
	if (jdata.edit) {
		if (confirm('선택하신 파일을 정말로 삭제하시겠습니까?\n\n삭제하신 내역은 다시 복구할 수 없습니다.')) {
			$.post( '/community/delete_bbs_file', { bbs: $('#bbs').val(), record: $('#bseq').val(), frecord: vIdx, fname: vFname })
				.done(function( data ) {
					if (data.status == "success") {
						$('#jdata').val(JSON.stringify(data));
						
						print_view_file(data.item.bfile, 'modify', data.edit);
					} else if (data.status == "fail") {
						alert('자료 처리에 싫패하였습니다.');
					}
		  		}, "json");
		}
	}
}

function go_delete(vSeq) {
	if (confirm('선택하신 내역을 정말로 삭제하시겠습니까?\n\n삭제하신 내역은 다시 복구할 수 없습니다.')) {
			$.post( '/community/delete_bbs', { bbs: $('#bbs').val(), record: $('#bseq').val() })
				.done(function( data ) {
					if (data.status == "success") {
						close_view();
						list_reload($('#now_page').val());
					} else if (data.status == "alert") {
						alert(data.message);
					} else if (data.status == "fail") {
						alert('자료 처리에 싫패하였습니다.');
					}
		  		}, "json");
	}
}
