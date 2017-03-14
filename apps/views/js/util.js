function isValidEmail(emailText) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailText);
};

function check_pw() {
	var cpw1 = $('#upw1').val();
	var cpw2 = $('#upw2').val();
	
	if (cpw1 == "") {
		alert("비밀번호를 입력하여 주세요.");
		return "1";
	}
	if (cpw2 == "") {
		alert("비밀번호 확인를 입력하여 주세요.");
		return "2";
	}
	if (cpw1.length < 6) {
		alert("비밀번호는 6~12자로 구성됩니다.");
		return "3";
	}
	if (cpw1 == cpw2) {
		return "0";
	} else {
		alert("비밀번호가 다릅니다. 확인 후 다시 입력해 주세요.");
		return "4";
	}
}

function check_tel(vObj, vText) {
	if (vObj.val() == "") {
		alert(vText + "를 입력하여 주세요.");
		return "1";
	}
	if (vObj.val().length < 7) {
		alert(vText + "는 최소 7자 이상 입력하여 주세요.");
		return "2";
	}
	
	return "0";
}

function page_navigation(vNowPage, vPageTotal, vPageSize) {
	var navi_html = '';
	var blockPage = parseInt((vNowPage - 1) / vPageSize) * vPageSize + 1;

	if (vNowPage > 1)
		navi_html += '<a href="javascript:list_reload(1);" class="pageimg"><img src="/app/views/images/common/icon_page1.gif" alt="처음" /></a> ';
	if (blockPage != 1)
		navi_html += '<a href="javascript:list_reload(' + (blockPage - vPageSize) + ');" class="pageimg"><img src="/app/views/images/common/icon_page2.gif" alt="이전" /></a> ';
	
	var i = 1;
	
	while (i <= vPageSize && blockPage <= vPageTotal) {
	    if (blockPage == vNowPage)
	    	navi_html += ' <a href="javascript:list_reload(' + blockPage + ');" class="strong">' + blockPage + '</a>';
	    else
	    	navi_html += ' <a href="javascript:list_reload(' + blockPage + ');">' + blockPage + '</a>';
	    
	    blockPage++;
	    i++;
	}
	
	if (blockPage <= vPageTotal)
		navi_html += ' <a href="javascript:list_reload(' + blockPage + ');" class="pageimg"><img src="/app/views/images/common/icon_page3.gif" alt="다음" /></a>';
	if (vNowPage != vPageTotal)
		navi_html += ' <a href="javascript:list_reload(' + vPageTotal + ');" class="pageimg"><img src="/app/views/images/common/icon_page4.gif" alt="마지막" /></a>';
	
	$('#area_paging').html(navi_html);
}

function page_navigation2(vNowPage, vPageTotal, vPageSize) {
	var navi_html = '';
	var blockPage = parseInt((vNowPage - 1) / vPageSize) * vPageSize + 1;

	if (vNowPage > 1)
		navi_html += '<a href="javascript:list_reload2(1);" class="pageimg"><img src="/app/views/images/common/icon_page1.gif" alt="처음" /></a> ';
	if (blockPage != 1)
		navi_html += '<a href="javascript:list_reload2(' + (blockPage - vPageSize) + ');" class="pageimg"><img src="/app/views/images/common/icon_page2.gif" alt="이전" /></a> ';
	
	var i = 1;
	
	while (i <= vPageSize && blockPage <= vPageTotal) {
	    if (blockPage == vNowPage)
	    	navi_html += ' <a href="javascript:list_reload2(' + blockPage + ');" class="strong">' + blockPage + '</a>';
	    else
	    	navi_html += ' <a href="javascript:list_reload2(' + blockPage + ');">' + blockPage + '</a>';
	    
	    blockPage++;
	    i++;
	}
	
	if (blockPage <= vPageTotal)
		navi_html += ' <a href="javascript:list_reload2(' + blockPage + ');" class="pageimg"><img src="/app/views/images/common/icon_page3.gif" alt="다음" /></a>';
	if (vNowPage != vPageTotal)
		navi_html += ' <a href="javascript:list_reload2(' + vPageTotal + ');" class="pageimg"><img src="/app/views/images/common/icon_page4.gif" alt="마지막" /></a>';
	
	$('#area_paging').html(navi_html);
}

function reset_adminexcel_form() {
	$('#excelupload').val('');
}

function calendarclick_event(vId) {
	$('#'+vId).focus();
}

