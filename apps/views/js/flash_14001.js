// JavaScript Document

function ShowFlash(url, width, height, showmenu, mode, allowScriptAccess, values, swfid){
	document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="' + width + '" height="' + height + '" id="' + swfid + '" align="middle">');
	document.write('<param name="movie" value="' + url + '">');
	document.write('<param name="wmode" value="' + mode + '">');
	document.write('<param name="menu" value="' + showmenu + '">');
	document.write('<param name="allowScriptAccess" value="' + allowScriptAccess + '">');
	document.write('<param name="flashvars" value="' + values + '">');
	document.write('<param name="quality" value="high">');
	document.write('<param name="play" value="true">');
	document.write('<param name="scale" value="showall">');
	document.write('<param name="devicefont" value="false">');
	document.write('<param name="salign" value="">');
	
	// if !IE
	document.write('<object type="application/x-shockwave-flash" data="' + url + '" width="' + width + '" height="' + height + '">');
	document.write('<param name="movie" value="' + url + '">');
	document.write('<param name="wmode" value="' + mode + '">');
	document.write('<param name="menu" value="' + showmenu + '">');
	document.write('<param name="allowScriptAccess" value="' + allowScriptAccess + '">');
	document.write('<param name="flashvars" value="' + values + '">');
	document.write('<param name="quality" value="high">');
	document.write('<param name="bgcolor" value="">');
	document.write('<param name="play" value="true">');
	document.write('<param name="loop" value="false">');
	document.write('<param name="scale" value="showall">');
	document.write('<param name="devicefont" value="false">');
	document.write('<param name="salign" value="">');
	document.write('</object>');
	// !endif
	document.write('</object>');
}

function swfCall(msg) { // swf link
	switch (msg) {
	
		case "btn0" :
			location.href = "main.html";
		break;
		 
		case "btn1" :
			location.href = "main.html";
		break;
		 
		case "btn2" :
			location.href = "main.html";
		break;
		 
		case "btn3" :
			location.href = "main.html";
		break;
		 
		case "btn4" :
			location.href = "main.html";
		break;
		 
		case "btn5" :
			location.href = "main.html";
		break;
		 

	}
}
