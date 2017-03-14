// JavaScript Document
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

/* Modal */

var publickey = "c064dbc0f0f94e3d9e3294afd96f98380fde3a877f01e2b87a07618dbadb22536ba4ecd983fbf1022b481fae7d90767e69863bcee3196c52520f4def8991609ff727defc16f785e0c58efa9038d95bb724580dcb1296c653f620b6edb5c2469fa4978ce33d16063979a4e7fb271282113ee4b2add9e6d3376a28735f088f70a5";

function check_glogin() {
 	var rsakey = new RSAKey();
   	rsakey.setPublic(publickey, "10001");
   	
   	var send_check = $('#gid').val() + "";
	send_check += "|"+$('#gpw').val();
	var enc = rsakey.encrypt(send_check);

	$.post( "/member/global_login", { encrypted: enc })
		.done(function( data ) {
			if (data.status == "success") {
				document.location.replace('/');
			}
			else if (data.status == "fail") {
				alert('아이디 혹은 비밀번호가 일치하지 않습니다.\n\n다시 확인하여 주십시오.');
			}
  		}, "json");
}

$(function() {

	// 회원인증
	$('#diapup101').dialog({
		autoOpen: false,
		resizable: false, // 리사이징 가능 여부.
		width: 500, // 창의 넓이 설정. 기본 auto.
		height: 250, // 창의 높이 설정. 기본 auto.
		modal: true, // 모달창으로서 사용할지의 여부 설정. 마스크레이어가 자동 설정된다.
		title: "인증번호받기", // 다이얼로그의 타이틀 지정. html 코드도 올 수 있다. 다이얼로그로 지정된 html태그에 타이틀 속성으로도 지정할수있다.
		closeOnEscape: true, // ESC키를 눌렀을때 다이얼로그 박스를 닫을것인지의 설정. 설정하지않으면 기본 true로써 닫히게된다
		dialogClass: "alert", // ??????????????????????????????
		draggable: false // 드래그를 가능하게할지 여부. 기본 true로써 드래그가 가능하다.

	});
	//$(".ui-dialog-titlebar").hide();	
	// $( "#btn_diapup101" ).click(function() {
		// $( "#diapup101" ).dialog( "open" );
	// });	
	$( "#cbtn_diapup101" ).click(function() {
		$( "#diapup101" ).dialog( "close" );
	});
	
	// 로그인
	$('#diapup102').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 270,
		modal: true,
		title: "로그인",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup102" ).click(function() {
		$('#gid').val('');
		$('#gpw').val('');
		$( "#diapup102" ).dialog( "open" );
	});	
	$( "#abtn_diapup102" ).click(function() {
		location.href="/member/join";
	});
	$( "#cbtn_diapup102" ).click(function() {
		$('#gid').val('');
		$('#gpw').val('');
		$( "#diapup102" ).dialog( "close" );
	});
	
	// 비밀번호변경
	$('#diapup103').dialog({
		autoOpen: false,
		resizable: false,
		width: 600,
		height: 320,
		modal: true,
		title: "비밀번호변경",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup103" ).click(function() {
		$('#opw').val('');
		$('#upw1').val('');
		$('#upw2').val('');
		$( "#diapup103" ).dialog( "open" );
	});	
	$( "#cbtn_diapup103" ).click(function() {
		$('#opw').val('');
		$('#upw1').val('');
		$('#upw2').val('');
		$( "#diapup103" ).dialog( "close" );
	});
	
	
	// 회원탈퇴
	$('#diapup104').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 270,
		modal: true,
		title: "회원탈퇴",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup104" ).click(function() {
		$('#cpw').val('');
		$('#cwhy').val('');
		$( "#diapup104" ).dialog( "open" );
	});	
	$( "#cbtn_diapup104" ).click(function() {
		$('#cpw').val('');
		$('#cwhy').val('');
		$( "#diapup104" ).dialog( "close" );
	});
	
	// 담당프로젝트 목록
	$('#diapup105').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 520,
		modal: true,
		title: "홍길순님의 프로젝트 목록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( ".btn_diapup105" ).click(function() {
		$( "#diapup105" ).dialog( "open" );
	});	
	$( "#cbtn_diapup105" ).click(function() {
		$( "#diapup105" ).dialog( "close" );
	});
	
	// 가입자검색
	$('#diapup106').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 620,
		modal: true,
		title: "가입자검색",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup106" ).click(function() {
		$( "#diapup106" ).dialog( "open" );
	});	
	$( "#cbtn_diapup106" ).click(function() {
		$( "#diapup106" ).dialog( "close" );
	});
	
	// 담당프로젝트 목록
	$('#diapup107').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 520,
		modal: true,
		title: "담당자 목록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup107" ).click(function() {
		form_reset();
		$( "#diapup107" ).dialog( "open" );
	});	
	// $( "#cbtn_diapup107" ).click(function() {
		// $( "#diapup107" ).dialog( "close" );
	// });

	
	// 프로젝트 삭제
	$('#diapup108').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 220,
		modal: true,
		title: "프로젝트 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup108" ).click(function() {
		$( "#diapup108" ).dialog( "open" );
	});	
	$( "#cbtn_diapup108" ).click(function() {
		$( "#diapup108" ).dialog( "close" );
	});
	// $( "#dbtn_diapup108" ).click(function() {
		// $( "#diapup108" ).dialog( "close" );
		// $( "#diapup109" ).dialog( "open" );
	// });

	$('#diapup109').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 220,
		modal: true,
		title: "프로젝트 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	// $( "#cbtn_diapup109" ).click(function() {
		// $( "#diapup109" ).dialog( "close" );
	// });

	// 공구추가
	$('#diapup110').dialog({
		autoOpen: false,
		resizable: false,
		width: 700,
		height: 520,
		modal: true,
		title: "공구추가",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup110" ).click(function() {
		form_reset_gonggu_writeform();
		$( "#diapup110" ).dialog( "open" );
	});	
	// $( "#abtn_diapup110" ).click(function() {
		// $( "#diapup110" ).dialog( "close" );
		// $( "#diapup111" ).dialog( "open" );
	// });
	$( "#cbtn_diapup110" ).click(function() {
		$( "#diapup110" ).dialog( "close" );
	});

	// 공구상세정보
	$('#diapup111').dialog({
		autoOpen: false,
		resizable: false,
		width: 700,
		height: 520,
		modal: true,
		title: "공구상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup111" ).click(function() {
		$( "#diapup111" ).dialog( "open" );
	});	
	// $( "#cbtn_diapup111" ).click(function() {
		// $( "#diapup111" ).dialog( "close" );
	// });
	// $( "#dbtn_diapup111" ).click(function() {
		// $( "#diapup111" ).dialog( "close" );
		// $( "#diapup113" ).dialog( "open" );
	// });
	// $( ".mbtn_diapup111" ).click(function() {
		// $( "#diapup111" ).dialog( "close" );
		// $( "#diapup112" ).dialog( "open" );
	// });

	// // 공구상세정보변경
	// $('#diapup112').dialog({
		// autoOpen: false,
		// resizable: false,
		// width: 700,
		// height: 520,
		// modal: true,
		// title: "공구상세정보 변경",
		// closeOnEscape: true,
		// dialogClass: "alert",
		// draggable: false
// 
	// });
	// //$(".ui-dialog-titlebar").hide();	
	// $( "#btn_diapup112" ).click(function() {
		// $( "#diapup112" ).dialog( "open" );
	// });	
	// $( "#cbtn_diapup112" ).click(function() {
		// $( "#diapup112" ).dialog( "close" );
		// $( "#diapup111" ).dialog( "open" );
	// });
	// $( "#dbtn_diapup112" ).click(function() {
		// $( "#diapup112" ).dialog( "close" );
		// $( "#diapup113" ).dialog( "open" );
	// });
	// $( "#mbtn_diapup112" ).click(function() {
		// $( "#diapup112" ).dialog( "close" );
		// $( "#diapup111" ).dialog( "open" );
	// });
	
	// 공구 삭제
	$('#diapup113').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 220,
		modal: true,
		title: "공구 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false
	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup113" ).click(function() {
		$( "#diapup113" ).dialog( "open" );
	});	
	$( "#cbtn_diapup113" ).click(function() {
		$( "#diapup113" ).dialog( "close" );
		$( "#diapup111" ).dialog( "open" );
	});
	$( "#abtn_diapup113" ).click(function() {
		$( "#diapup113" ).dialog( "close" );
		$( "#diapup114" ).dialog( "open" );
	});

	$('#diapup114').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 220,
		modal: true,
		title: "공구 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup114" ).click(function() {
		$( "#diapup114" ).dialog( "close" );
	});

	// 건축물정보등록
	$('#diapup115').dialog({
		autoOpen: false,
		resizable: false,
		width: 600,
		height: 670,
		modal: true,
		title: "건축물정보등록(개별등록)",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup115" ).click(function() {
		$( "#diapup115" ).dialog( "close" );
	});
	// $( "#abtn_diapup115" ).click(function() {
		// $( "#diapup115" ).dialog( "close" );
		// $( "#diapup117" ).dialog( "open" );
	// });
	$( "#obtn_diapup115" ).click(function() {
		$( "#diapup115" ).dialog( "close" );
		$( "#diapup116" ).dialog( "open" );
	});
	$( ".btn_diapup115" ).click(function() {
		$( "#diapup115" ).dialog( "open" );
	});

	// 건축물정보등록(Excel등록)
	$('#diapup116').dialog({
		autoOpen: false,
		resizable: false,
		width: 600,
		height: 620,
		modal: true,
		title: "건축물정보등록(Excel등록)",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup116" ).click(function() {
		$( "#diapup116" ).dialog( "close" );
	});
	$( "#abtn_diapup116" ).click(function() {
		$( "#diapup116" ).dialog( "close" );
		$( "#diapup117" ).dialog( "open" );
	});
	$( "#obtn_diapup116" ).click(function() {
		$( "#diapup116" ).dialog( "close" );
		$( "#diapup115" ).dialog( "open" );
	});
	$( "#btn_diapup116" ).click(function() {
		$( "#diapup116" ).dialog( "open" );
	});

	// 건축물상세정보
	$('#diapup117').dialog({
		autoOpen: false,
		resizable: false,
		width: 600,
		height: 520,
		modal: true,
		title: "건축물상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	// $( "#dbtn_diapup117" ).click(function() {
		// $( "#diapup117" ).dialog( "close" );
		// $( "#diapup119" ).dialog( "open" );
	// });
	$( "#gbtn_diapup117" ).click(function() {
		$( "#diapup117" ).dialog( "close" );
	});
	$( "#abtn_diapup117" ).click(function() {
		$( "#diapup117" ).dialog( "close" );
	});
	// $( "#mbtn_diapup117" ).click(function() {
		// $( "#diapup117" ).dialog( "close" );
		// $( "#diapup118" ).dialog( "open" );
	// });
	$( "#btn_diapup117" ).click(function() {
		$( "#diapup117" ).dialog( "open" );
	});

	// 건축물정보변경
	$('#diapup118').dialog({
		autoOpen: false,
		resizable: false,
		width: 600,
		height: 520,
		modal: true,
		title: "건축물정보변경",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup118" ).click(function() {
		$( "#diapup118" ).dialog( "close" );
		$( "#diapup117" ).dialog( "open" );
	});
	$( "#abtn_diapup118" ).click(function() {
		$( "#diapup118" ).dialog( "close" );
		$( "#diapup117" ).dialog( "open" );
	});
	$( "#gbtn_diapup118" ).click(function() {
		$( "#diapup118" ).dialog( "close" );
		$( "#diapup119" ).dialog( "open" );
	});
	$( "#btn_diapup118" ).click(function() {
		$( "#diapup118" ).dialog( "open" );
	});

	// 건축물정보 삭제
	$('#diapup119').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 220,
		modal: true,
		title: "건축물정보 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false
	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup119" ).click(function() {
		$( "#diapup119" ).dialog( "open" );
	});	
	$( "#cbtn_diapup119" ).click(function() {
		$( "#diapup119" ).dialog( "close" );
		$( "#diapup117" ).dialog( "open" );
	});
	$( "#abtn_diapup119" ).click(function() {
		$( "#diapup119" ).dialog( "close" );
		$( "#diapup120" ).dialog( "open" );
	});

	$('#diapup120').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 220,
		modal: true,
		title: "건축물정보 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup120" ).click(function() {
		$( "#diapup120" ).dialog( "close" );
	});

	// 공정상세정보
	$('#diapup121').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 420,
		modal: true,
		title: "공정상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	// $( "#cbtn_diapup121" ).click(function() {
		// $( "#diapup121" ).dialog( "close" );
	// });
	// $( "#mbtn_diapup121" ).click(function() {
		// $( "#diapup121" ).dialog( "close" );
		// $( "#diapup124" ).dialog( "open" );
	// });
	// $( "#dbtn_diapup121" ).click(function() {
		// $( "#diapup121" ).dialog( "close" );
		// $( "#diapup125" ).dialog( "open" );
	// });
	$( ".btn_diapup121" ).click(function() {
		$( "#diapup121" ).dialog( "open" );
	});
	$( "#btn_diapup121" ).click(function() {
		$( "#diapup121" ).dialog( "open" );
	});

	// 공정등록
	$('#diapup122').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 420,
		modal: true,
		title: "공정등록(직접등록)",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup122" ).click(function() {
		$( "#diapup122" ).dialog( "close" );
	});
	// $( "#abtn_diapup122" ).click(function() {
		// $( "#diapup122" ).dialog( "close" );
		// $( "#diapup121" ).dialog( "open" );
	// });
	// $( "#obtn_diapup122" ).click(function() {
		// $( "#diapup122" ).dialog( "close" );
		// $( "#diapup123" ).dialog( "open" );
	// });
	// $( "#btn_diapup122" ).click(function() {
		// $( "#diapup122" ).dialog( "open" );
	// });

	// 공정등록
	$('#diapup123').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 420,
		modal: true,
		title: "공정등록(Excel등록)",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup123" ).click(function() {
		$( "#diapup123" ).dialog( "close" );
	});
	$( "#abtn_diapup123" ).click(function() {
		$( "#diapup123" ).dialog( "close" );
		$( "#diapup121" ).dialog( "open" );
	});
	$( "#obtn_diapup123" ).click(function() {
		$( "#diapup123" ).dialog( "close" );
		$( "#diapup122" ).dialog( "open" );
	});
	$( "#btn_diapup123" ).click(function() {
		$( "#diapup123" ).dialog( "open" );
	});

	// 공정정보 삭제
	$('#diapup125').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 220,
		modal: true,
		title: "공정정보 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false
	});
	//$(".ui-dialog-titlebar").hide();	
	// $( "#btn_diapup125" ).click(function() {
		// $( "#diapup125" ).dialog( "open" );
	// });	
	$( "#cbtn_diapup125" ).click(function() {
		$( "#diapup125" ).dialog( "close" );
		$( "#diapup121" ).dialog( "open" );
	});
	$( "#abtn_diapup125" ).click(function() {
		$( "#diapup125" ).dialog( "close" );
		$( "#diapup126" ).dialog( "open" );
	});

	$('#diapup126').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 220,
		modal: true,
		title: "공정정보 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup126" ).click(function() {
		$( "#diapup126" ).dialog( "close" );
	});
	

	// 자재정보 상세정보
	$('#diapup127').dialog({
		autoOpen: false,
		resizable: false,
		width: 800,
		height: 720,
		modal: true,
		title: "Pre-Use 상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup127" ).click(function() {
		$( "#diapup127" ).dialog( "close" );
	});
	$( "#o1btn_diapup127" ).click(function() {
	});
	$( "#o2btn_diapup127" ).click(function() {
		$( "#diapup127" ).dialog( "close" );
		$( "#diapup138" ).dialog( "open" );
	});
	$( "#o3btn_diapup127" ).click(function() {
		$( "#diapup127" ).dialog( "close" );
		$( "#diapup139" ).dialog( "open" );
	});
	$( "#btn_diapup127" ).click(function() {
		$( "#diapup127" ).dialog( "open" );
	});
	$( ".btn_diapup127" ).click(function() {
		$( "#diapup127" ).dialog( "open" );
	});
	$( "#mbtn_diapup127" ).click(function() {
		$( "#diapup127" ).dialog( "open" );
	});
	$( "#rbtn_diapup127" ).click(function() {
		$( "#diapup127" ).dialog( "open" );
	});
	$( "#dbtn_diapup127" ).click(function() {
		$( "#diapup127" ).dialog( "close" );
		$( "#diapup135" ).dialog( "open" );
	});

	// 자재정보 상세정보
	$('#diapup138').dialog({
		autoOpen: false,
		resizable: false,
		width: 800,
		height: 720,
		modal: true,
		title: "유지보수 상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup138" ).click(function() {
		$( "#diapup138" ).dialog( "close" );
	});
	$( "#o1btn_diapup138" ).click(function() {
		$( "#diapup138" ).dialog( "close" );
		$( "#diapup127" ).dialog( "open" );
	});
	$( "#o2btn_diapup138" ).click(function() {
	});
	$( "#o3btn_diapup138" ).click(function() {
		$( "#diapup138" ).dialog( "close" );
		$( "#diapup139" ).dialog( "open" );
	});
	$( "#btn_diapup138" ).click(function() {
		$( "#diapup138" ).dialog( "open" );
	});
	$( "#mbtn_diapup138" ).click(function() {
		$( "#diapup138" ).dialog( "open" );
	});
	$( "#rbtn_diapup138" ).click(function() {
		$( "#diapup138" ).dialog( "open" );
	});
	$( "#dbtn_diapup138" ).click(function() {
		$( "#diapup138" ).dialog( "close" );
		$( "#diapup135" ).dialog( "open" );
	});
	
	// 자재정보 상세정보
	$('#diapup139').dialog({
		autoOpen: false,
		resizable: false,
		width: 800,
		height: 720,
		modal: true,
		title: "Post-Use 상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup139" ).click(function() {
		$( "#diapup139" ).dialog( "close" );
	});
	$( "#o1btn_diapup139" ).click(function() {
		$( "#diapup139" ).dialog( "close" );
		$( "#diapup127" ).dialog( "open" );
	});
	$( "#o2btn_diapup139" ).click(function() {
		$( "#diapup139" ).dialog( "close" );
		$( "#diapup138" ).dialog( "open" );
	});
	$( "#o3btn_diapup139" ).click(function() {
	});
	$( "#mbtn_diapup139" ).click(function() {
		$( "#diapup139" ).dialog( "open" );
	});
	$( "#btn_diapup139" ).click(function() {
		$( "#diapup139" ).dialog( "open" );
	});
	$( ".btn_diapup139" ).click(function() {
		$( "#diapup139" ).dialog( "open" );
	});
	$( "#rbtn_diapup139" ).click(function() {
		$( "#diapup139" ).dialog( "open" );
	});
	$( "#dbtn_diapup139" ).click(function() {
		$( "#diapup139" ).dialog( "close" );
		$( "#diapup135" ).dialog( "open" );
	});
	
	// pre-use 자재정보입력
	$('#diapup128').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 390,
		modal: true,
		title: "Pre-Use 자재정보 직접입력",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup128" ).click(function() {
		$( "#diapup128" ).dialog( "close" );
	});
	// $( "#btn_diapup128" ).click(function() {
		// $( "#diapup128" ).dialog( "open" );
	// });
	$( "#abtn_diapup128" ).click(function() {
		$( "#diapup128" ).dialog( "close" );
	});
	$( "#pbtn_diapup128" ).click(function() {
		$( "#diapup137" ).dialog( "open" );
	});
	$( "#obtn_diapup128" ).click(function() {
		$( "#diapup128" ).dialog( "close" );
		$( "#diapup129" ).dialog( "open" );
	});

	// pre-use 자재정보입력
	$('#diapup129').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 390,
		modal: true,
		title: "Pre-Use 자재정보 Excel입력",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup129" ).click(function() {
		$( "#diapup129" ).dialog( "close" );
	});
	$( "#btn_diapup129" ).click(function() {
		$( "#diapup129" ).dialog( "open" );
	});
	$( "#abtn_diapup129" ).click(function() {
		$( "#diapup129" ).dialog( "close" );
	});
	$( "#obtn_diapup129" ).click(function() {
		$( "#diapup129" ).dialog( "close" );
		$( "#diapup128" ).dialog( "open" );
	});

	// pre-use 자재정보수정
	$('#diapup130').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 400,
		modal: true,
		title: "Pre-Use 자재정보수정",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup130" ).click(function() {
		$( "#diapup130" ).dialog( "close" );
	});
	$( "#cbtn_diapup130_1" ).click(function() {
		$( "#diapup130" ).dialog( "close" );
	});
	// $( "#btn_diapup130" ).click(function() {
		// $( "#diapup130" ).dialog( "open" );
	// });
	// $( "#mbtn_diapup130" ).click(function() {
		// $( "#diapup130" ).dialog( "close" );
	// });
	// $( "#pbtn_diapup130" ).click(function() {
		// $( "#diapup137" ).dialog( "open" );
	// });

	// pre-use 자재정보삭제
	$('#diapup131').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "Pre-Use 자재정보삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup131" ).click(function() {
		$( "#diapup131" ).dialog( "close" );
	});
	$( "#btn_diapup131" ).click(function() {
		$( "#diapup131" ).dialog( "open" );
	});
	$( "#dbtn_diapup131" ).click(function() {
		$( "#diapup131" ).dialog( "open" );
	});
	$( "#abtn_diapup131" ).click(function() {
		$( "#diapup131" ).dialog( "close" );
		$( "#diapup132" ).dialog( "open" );
	});

	// pre-use 자재정보수정
	$('#diapup132').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "Pre-Use 자재정보삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#btn_diapup132" ).click(function() {
		$( "#diapup132" ).dialog( "open" );
	});
	$( "#cbtn_diapup132" ).click(function() {
		$( "#diapup132" ).dialog( "close" );
	});

	// 장비사용정보삭제
	$('#diapup133').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "장비사용정보삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup133" ).click(function() {
		$( "#diapup133" ).dialog( "close" );
	});
	$( "#btn_diapup133" ).click(function() {
		$( "#diapup133" ).dialog( "open" );
	});
	$( "#dbtn_diapup133" ).click(function() {
		$( "#diapup133" ).dialog( "open" );
	});
	$( "#abtn_diapup133" ).click(function() {
		$( "#diapup133" ).dialog( "close" );
		$( "#diapup134" ).dialog( "open" );
	});
	
	// 장비사용정보삭제
	$('#diapup134').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "장비사용정보삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup134" ).click(function() {
		$( "#diapup134" ).dialog( "close" );
	});
	$( "#btn_diapup134" ).click(function() {
		$( "#diapup134" ).dialog( "open" );
	});

	// 상세정보삭제
	$('#diapup135').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "상세정보삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup135" ).click(function() {
		$( "#diapup135" ).dialog( "close" );
		$( "#diapup127" ).dialog( "open" );
	});
	$( "#btn_diapup135" ).click(function() {
		$( "#diapup135" ).dialog( "open" );
	});
	$( "#abtn_diapup135" ).click(function() {
		$( "#diapup135" ).dialog( "close" );
		$( "#diapup136" ).dialog( "open" );
	});

	// 상세정보삭제
	$('#diapup136').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "상세정보삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup136" ).click(function() {
		$( "#diapup136" ).dialog( "close" );
	});
	$( "#btn_diapup136" ).click(function() {
		$( "#diapup136" ).dialog( "open" );
	});

	// LCI DB 검색
	$('#diapup137').dialog({
		autoOpen: false,
		resizable: false,
		width: 420,
		height: 520,
		modal: true,
		title: "LCI DB 검색",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup137" ).click(function() {
		$( "#diapup137" ).dialog( "close" );
	});
	$( "#btn_diapup137" ).click(function() {
		$( "#diapup137" ).dialog( "open" );
	});

	// Use Energy 사용 예상 정보 수정하기
	$('#diapup140').dialog({
		autoOpen: false,
		resizable: false,
		width: 420,
		height: 320,
		modal: true,
		title: "Use Energy 사용 예상 정보 수정",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup140" ).click(function() {
		$( "#diapup140" ).dialog( "close" );
	});
	// $( "#btn_diapup140" ).click(function() {
		// $( "#diapup140" ).dialog( "open" );
	// });
	// $( "#mbtn_diapup140" ).click(function() {
		// $( "#diapup140" ).dialog( "close" );
	// });

	// Use Energy 사용정보 직접 입력하기
	$('#diapup141').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 470,
		modal: true,
		title: "Use Energy 사용정보 직접입력",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup141" ).click(function() {
		$( "#diapup141" ).dialog( "close" );
	});
	$( "#cbtn_diapup141_1" ).click(function() {
		$( "#diapup141" ).dialog( "close" );
	});
	// $( "#btn_diapup141" ).click(function() {
		// $( "#diapup141" ).dialog( "open" );
	// });
	// $( "#abtn_diapup141" ).click(function() {
		// $( "#diapup141" ).dialog( "close" );
	// });
	$( "#obtn_diapup141" ).click(function() {
		$( "#diapup141" ).dialog( "close" );
		$( "#diapup142" ).dialog( "open" );
	});

	// Use Energy 사용정보 Excel 입력하기
	$('#diapup142').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "Use Energy 사용정보 Excel입력",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup142" ).click(function() {
		$( "#diapup142" ).dialog( "close" );
	});
	$( "#btn_diapup142" ).click(function() {
		$( "#diapup142" ).dialog( "open" );
	});
	$( "#abtn_diapup142" ).click(function() {
		$( "#diapup142" ).dialog( "close" );
	});
	$( "#obtn_diapup142" ).click(function() {
		$( "#diapup142" ).dialog( "close" );
		$( "#diapup141" ).dialog( "open" );
	});


	// 실 사용량 정보 삭제
	$('#diapup143').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "실 사용량정보 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup143" ).click(function() {
		$( "#diapup143" ).dialog( "close" );
	});
	$( "#btn_diapup143" ).click(function() {
		$( "#diapup143" ).dialog( "open" );
	});
	$( "#abtn_diapup143" ).click(function() {
		$( "#diapup143" ).dialog( "close" );
		$( "#diapup144" ).dialog( "open" );
	});

	// 실 사용량 정보 삭제
	$('#diapup144').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "실 사용량정보 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup144" ).click(function() {
		$( "#diapup144" ).dialog( "close" );
	});
	$( "#btn_diapup144" ).click(function() {
		$( "#diapup144" ).dialog( "open" );
	});

	// 건설자재기준 삭제
	$('#diapup145').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "건설자재기준 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup145" ).click(function() {
		$( "#diapup145" ).dialog( "close" );
	});
	$( "#btn_diapup145" ).click(function() {
		$( "#diapup145" ).dialog( "open" );
	});
	$( "#abtn_diapup145" ).click(function() {
		$( "#diapup145" ).dialog( "close" );
		$( "#diapup146" ).dialog( "open" );
	});

	// 건설자재기준 삭제
	$('#diapup146').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "건설자재기준 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup146" ).click(function() {
		$( "#diapup146" ).dialog( "close" );
	});
	$( "#btn_diapup146" ).click(function() {
		$( "#diapup146" ).dialog( "open" );
	});

	// ENERGY기준 삭제
	$('#diapup147').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "ENERGY기준 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup147" ).click(function() {
		$( "#diapup147" ).dialog( "close" );
	});
	$( "#btn_diapup147" ).click(function() {
		$( "#diapup147" ).dialog( "open" );
	});
	$( "#abtn_diapup147" ).click(function() {
		$( "#diapup147" ).dialog( "close" );
		$( "#diapup148" ).dialog( "open" );
	});

	// ENERGY기준 삭제
	$('#diapup148').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "ENERGY기준 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup148" ).click(function() {
		$( "#diapup148" ).dialog( "close" );
	});
	$( "#btn_diapup148" ).click(function() {
		$( "#diapup148" ).dialog( "open" );
	});

	// 건설장비기준 삭제
	$('#diapup149').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "건설장비기준 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup149" ).click(function() {
		$( "#diapup149" ).dialog( "close" );
	});
	$( "#btn_diapup149" ).click(function() {
		$( "#diapup149" ).dialog( "open" );
	});
	$( "#abtn_diapup149" ).click(function() {
		$( "#diapup149" ).dialog( "close" );
		$( "#diapup150" ).dialog( "open" );
	});

	// 건설장비기준 삭제
	$('#diapup150').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "건설장비기준 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup150" ).click(function() {
		$( "#diapup150" ).dialog( "close" );
	});
	$( "#btn_diapup150" ).click(function() {
		$( "#diapup150" ).dialog( "open" );
	});

	// 회원관리 상세정보
	$('#diapup151').dialog({
		autoOpen: false,
		resizable: false,
		width: 620,
		height: 680,
		modal: true,
		title: "회원상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup151" ).click(function() {
		$( "#diapup151" ).dialog( "close" );
	});
	$( "#obtn_diapup151" ).click(function() {
		$( "#diapup151" ).dialog( "close" );
		$( "#diapup152" ).dialog( "open" );
	});
	$( ".btn_diapup151" ).click(function() {
		$( "#diapup151" ).dialog( "open" );
	});
	$( "#abtn_diapup151" ).click(function() {
		$( "#diapup151" ).dialog( "close" );
	});
	$( "#dbtn_diapup151" ).click(function() {
		$( "#diapup151" ).dialog( "close" );
	});

	// 회원관리 상세정보
	$('#diapup152').dialog({
		autoOpen: false,
		resizable: false,
		width: 620,
		height: 680,
		modal: true,
		title: "회원상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup152" ).click(function() {
		$( "#diapup152" ).dialog( "close" );
	});
	$( "#obtn_diapup152" ).click(function() {
		$( "#diapup152" ).dialog( "close" );
		$( "#diapup151" ).dialog( "open" );
	});
	$( "#btn_diapup152" ).click(function() {
		$( "#diapup152" ).dialog( "open" );
	});
	$( "#abtn_diapup152" ).click(function() {
		$( "#diapup152" ).dialog( "close" );
	});
	$( "#dbtn_diapup152" ).click(function() {
		$( "#diapup152" ).dialog( "close" );
	});

	// 팝업상세
	$('#diapup153').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 470,
		modal: true,
		title: "팝업상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	// $( "#cbtn_diapup153" ).click(function() {
		// $( "#diapup153" ).dialog( "close" );
	// });
	$( ".btn_diapup153" ).click(function() {
		$( "#diapup153" ).dialog( "open" );
	});
	$( "#abtn_diapup153" ).click(function() {
		$( "#diapup153" ).dialog( "close" );
	});
	$( "#mbtn_diapup153" ).click(function() {
		$( "#diapup153" ).dialog( "close" );
		$( "#diapup154" ).dialog( "open" );
	});

	// 팝업수정
	$('#diapup154').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 470,
		modal: true,
		title: "팝업수정",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup154" ).click(function() {
		$( "#diapup154" ).dialog( "close" );
		$( "#diapup153" ).dialog( "open" );
	});
	$( "#btn_diapup154" ).click(function() {
		$( "#diapup154" ).dialog( "open" );
	});
	$( "#mbtn_diapup154" ).click(function() {
		$( "#diapup154" ).dialog( "close" );
		$( "#diapup153" ).dialog( "open" );
	});

	// 팝업등록
	$('#diapup155').dialog({
		autoOpen: false,
		resizable: false,
		width: 720,
		height: 600,
		modal: true,
		title: "팝업등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup155" ).click(function() {
		$( "#diapup155" ).dialog( "close" );
	});
	// $( "#btn_diapup155" ).click(function() {
		// $( "#diapup155" ).dialog( "open" );
	// });
	// $( "#abtn_diapup155" ).click(function() {
		// $( "#diapup155" ).dialog( "close" );
		// $( "#diapup153" ).dialog( "open" );
	// });

	// 자재기초자료 직접등록
	$('#diapup156').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 620,
		modal: true,
		title: "자재 기초자료 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup156" ).click(function() {
		$( "#diapup156" ).dialog( "close" );
	});
	$( "#btn_diapup156" ).click(function() {
		form_reset();
		$( "#diapup156" ).dialog( "open" );
	});
	$( "#obtn_diapup156" ).click(function() {
		$( "#diapup156" ).dialog( "close" );
		reset_adminexcel_form();
		$( "#diapup157" ).dialog( "open" );
	});

	// 자재기초자료 Excel등록
	$('#diapup157').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "자재 기초자료 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup157" ).click(function() {
		$( "#diapup157" ).dialog( "close" );
	});
	// $( "#abtn_diapup157" ).click(function() {
		// $( "#diapup157" ).dialog( "close" );
	// });
	$( "#obtn_diapup157" ).click(function() {
		$( "#diapup157" ).dialog( "close" );
		$( "#diapup156" ).dialog( "open" );
	});

	// 자재기초자료 상세보기
	$('#diapup158').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 570,
		modal: true,
		title: "자재 기초자료 상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup158" ).click(function() {
		$( "#diapup158" ).dialog( "close" );
	});
	$( ".btn_diapup158" ).click(function() {
		$( "#diapup158" ).dialog( "open" );
	});
	$( "#mbtn_diapup158" ).click(function() {
		$( "#diapup158" ).dialog( "close" );
	});

	// Energy기초자료 직접등록
	$('#diapup159').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 480,
		modal: true,
		title: "Energy 기초자료 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup159" ).click(function() {
		$( "#diapup159" ).dialog( "close" );
	});
	$( "#btn_diapup159" ).click(function() {
		form_reset();
		$( "#diapup159" ).dialog( "open" );
	});
	// $( "#abtn_diapup159" ).click(function() {
		// $( "#diapup159" ).dialog( "close" );
	// });
	$( "#obtn_diapup159" ).click(function() {
		$( "#diapup159" ).dialog( "close" );
		reset_adminexcel_form()
		$( "#diapup160" ).dialog( "open" );
	});

	// Energy기초자료 Excel등록
	$('#diapup160').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "Energy 기초자료 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup160" ).click(function() {
		$( "#diapup160" ).dialog( "close" );
	});
	// $( "#abtn_diapup160" ).click(function() {
		// $( "#diapup160" ).dialog( "close" );
	// });
	$( "#obtn_diapup160" ).click(function() {
		$( "#diapup160" ).dialog( "close" );
		$( "#diapup159" ).dialog( "open" );
	});

	// Energy기초자료 상세보기
	$('#diapup161').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 450,
		modal: true,
		title: "Energy 기초자료 상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup161" ).click(function() {
		$( "#diapup161" ).dialog( "close" );
	});
	$( ".btn_diapup161" ).click(function() {
		$( "#diapup161" ).dialog( "open" );
	});
	$( "#mbtn_diapup161" ).click(function() {
		$( "#diapup161" ).dialog( "close" );
	});

	// 장비기초자료 직접등록
	$('#diapup162').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 520,
		modal: true,
		title: "장비 기초자료 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup162" ).click(function() {
		$( "#diapup162" ).dialog( "close" );
	});
	$( "#btn_diapup162" ).click(function() {
		form_reset();
		$( "#diapup162" ).dialog( "open" );
	});
	// $( "#abtn_diapup162" ).click(function() {
		// $( "#diapup162" ).dialog( "close" );
	// });
	$( "#obtn_diapup162" ).click(function() {
		$( "#diapup162" ).dialog( "close" );
		reset_adminexcel_form();
		$( "#diapup163" ).dialog( "open" );
	});

	// 장비기초자료 Excel등록
	$('#diapup163').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "장비 기초자료 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup163" ).click(function() {
		$( "#diapup163" ).dialog( "close" );
	});
	// $( "#abtn_diapup163" ).click(function() {
		// $( "#diapup163" ).dialog( "close" );
	// });
	$( "#obtn_diapup163" ).click(function() {
		$( "#diapup163" ).dialog( "close" );
		$( "#diapup162" ).dialog( "open" );
	});

	// 장비 기초자료 상세보기
	$('#diapup164').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 470,
		modal: true,
		title: "장비 기초자료 상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup164" ).click(function() {
		$( "#diapup164" ).dialog( "close" );
	});
	$( ".btn_diapup164" ).click(function() {
		$( "#diapup164" ).dialog( "open" );
	});
	$( "#mbtn_diapup164" ).click(function() {
		$( "#diapup164" ).dialog( "close" );
	});


	// 유사용어 직접등록
	$('#diapup165').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 320,
		modal: true,
		title: "유사용어 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup165" ).click(function() {
		$( "#diapup165" ).dialog( "close" );
	});
	$( "#btn_diapup165" ).click(function() {
		form_reset();
		$( "#diapup165" ).dialog( "open" );
	});
	// $( "#abtn_diapup165" ).click(function() {
		// $( "#diapup165" ).dialog( "close" );
	// });
	$( "#obtn_diapup165" ).click(function() {
		$( "#diapup165" ).dialog( "close" );
		reset_adminexcel_form();
		$( "#diapup166" ).dialog( "open" );
	});

	// 유사용어 Excel등록
	$('#diapup166').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "유사용어 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup166" ).click(function() {
		$( "#diapup166" ).dialog( "close" );
	});
	// $( "#abtn_diapup166" ).click(function() {
		// $( "#diapup166" ).dialog( "close" );
	// });
	$( "#obtn_diapup166" ).click(function() {
		$( "#diapup166" ).dialog( "close" );
		$( "#diapup165" ).dialog( "open" );
	});

	// 유사용어 상세보기
	$('#diapup167').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 320,
		modal: true,
		title: "유사용어 상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup167" ).click(function() {
		$( "#diapup167" ).dialog( "close" );
	});
	$( ".btn_diapup167" ).click(function() {
		$( "#diapup167" ).dialog( "open" );
	});
	$( "#mbtn_diapup167" ).click(function() {
		$( "#diapup167" ).dialog( "close" );
	});


	// LCI DB 검색
	$('#diapup168').dialog({
		autoOpen: false,
		resizable: false,
		width: 420,
		height: 520,
		modal: true,
		title: "LCI DB 검색",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup168" ).click(function() {
		$( "#diapup168" ).dialog( "close" );
	});
	$( ".btn_diapup168" ).click(function() {
		search_reset();
		$( "#diapup168" ).dialog( "open" );
	});

	// 치환단위 직접등록
	$('#diapup169').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 420,
		modal: true,
		title: "치환단위 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup169" ).click(function() {
		$( "#diapup169" ).dialog( "close" );
	});
	$( "#btn_diapup169" ).click(function() {
		form_reset();
		$( "#diapup169" ).dialog( "open" );
	});
	// $( "#abtn_diapup169" ).click(function() {
		// $( "#diapup169" ).dialog( "close" );
	// });
	$( "#obtn_diapup169" ).click(function() {
		$( "#diapup169" ).dialog( "close" );
		reset_adminexcel_form();
		$( "#diapup170" ).dialog( "open" );
	});

	// 치환단위 Excel등록
	$('#diapup170').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "치환단위 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup170" ).click(function() {
		$( "#diapup170" ).dialog( "close" );
	});
	// $( "#abtn_diapup170" ).click(function() {
		// $( "#diapup170" ).dialog( "close" );
	// });
	$( "#obtn_diapup170" ).click(function() {
		$( "#diapup170" ).dialog( "close" );
		$( "#diapup169" ).dialog( "open" );
	});

	// 치환단위 상세보기
	$('#diapup171').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 380,
		modal: true,
		title: "치환단위 상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup171" ).click(function() {
		$( "#diapup171" ).dialog( "close" );
	});
	$( ".btn_diapup171" ).click(function() {
		$( "#diapup171" ).dialog( "open" );
	});
	$( "#mbtn_diapup171" ).click(function() {
		$( "#diapup171" ).dialog( "close" );
	});


	// LCI DB 검색
	$('#diapup172').dialog({
		autoOpen: false,
		resizable: false,
		width: 420,
		height: 520,
		modal: true,
		title: "LCI DB 검색",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup172" ).click(function() {
		$( "#diapup172" ).dialog( "close" );
	});
	$( ".btn_diapup172" ).click(function() {
		search_reset();
		$( "#diapup172" ).dialog( "open" );
	});


	// 보정단위 직접등록
	$('#diapup173').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 350,
		modal: true,
		title: "보정단위 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup173" ).click(function() {
		$( "#diapup173" ).dialog( "close" );
	});
	$( "#btn_diapup173" ).click(function() {
		form_reset();
		$( "#diapup173" ).dialog( "open" );
	});
	// $( "#abtn_diapup173" ).click(function() {
		// $( "#diapup173" ).dialog( "close" );
	// });
	$( "#obtn_diapup173" ).click(function() {
		$( "#diapup173" ).dialog( "close" );
		reset_adminexcel_form();
		$( "#diapup174" ).dialog( "open" );
	});

	// 보정단위 Excel등록
	$('#diapup174').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "보정단위 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup174" ).click(function() {
		$( "#diapup174" ).dialog( "close" );
	});
	// $( "#abtn_diapup174" ).click(function() {
		// $( "#diapup174" ).dialog( "close" );
	// });
	$( "#obtn_diapup174" ).click(function() {
		$( "#diapup174" ).dialog( "close" );
		$( "#diapup173" ).dialog( "open" );
	});

	// 보정단위 상세보기
	$('#diapup175').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 340,
		modal: true,
		title: "보정단위 상세정보",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup175" ).click(function() {
		$( "#diapup175" ).dialog( "close" );
	});
	$( ".btn_diapup175" ).click(function() {
		$( "#diapup175" ).dialog( "open" );
	});
	$( "#mbtn_diapup175" ).click(function() {
		$( "#diapup175" ).dialog( "close" );
	});

	// 게시글 등록
	$('#write_bbs_pop').dialog({
		autoOpen: false,
		resizable: true,
		width: 720,
		height: 590,
		modal: true,
		title: "게시글 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: true

	});
	// 게시글 상세
	$('#view_bbs_pop').dialog({
		autoOpen: false,
		resizable: true,
		width: 720,
		height: 590,
		modal: true,
		title: "게시글 상세보기",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: true

	});

	// 공지사항 등록
	$('#diapup176').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 470,
		modal: true,
		title: "공지사항 등록",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup176" ).click(function() {
		$( "#diapup176" ).dialog( "close" );
	});
	$( "#btn_diapup176" ).click(function() {
		$( "#diapup176" ).dialog( "open" );
	});
	$( "#abtn_diapup176" ).click(function() {
		$( "#diapup176" ).dialog( "close" );
	});

	// 공지사항 수정
	$('#diapup177').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 470,
		modal: true,
		title: "공지사항 수정",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup177" ).click(function() {
		$( "#diapup177" ).dialog( "close" );
	});
	$( ".btn_diapup177" ).click(function() {
		$( "#diapup177" ).dialog( "open" );
	});
	$( "#mbtn_diapup177" ).click(function() {
		$( "#diapup177" ).dialog( "close" );
	});

	// 질문게시판 답변
	$('#diapup178').dialog({
		autoOpen: false,
		resizable: false,
		width: 600,
		height: 620,
		modal: true,
		title: "질문에 답변하기",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup178" ).click(function() {
		$( "#diapup178" ).dialog( "close" );
	});
	$( ".btn_diapup178" ).click(function() {
		$( "#diapup178" ).dialog( "open" );
	});
	$( "#dbtn_diapup178" ).click(function() {
		$( "#diapup178" ).dialog( "close" );
	});
	$( "#rbtn_diapup178" ).click(function() {
		$( "#diapup178" ).dialog( "close" );
	});

	// 자유게시판 상세보기
	$('#diapup179').dialog({
		autoOpen: false,
		resizable: false,
		width: 600,
		height: 620,
		modal: true,
		title: "자유게시판 상세보기",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup179" ).click(function() {
		$( "#diapup179" ).dialog( "close" );
	});
	$( ".btn_diapup179" ).click(function() {
		$( "#diapup179" ).dialog( "open" );
	});
	$( "#dbtn_diapup179" ).click(function() {
		$( "#diapup179" ).dialog( "close" );
	});
	$( "#rbtn_diapup179" ).click(function() {
		$( "#diapup179" ).dialog( "close" );
	});

	// 갤러리 상세보기
	$('#diapup180').dialog({
		autoOpen: false,
		resizable: false,
		width: 600,
		height: 620,
		modal: true,
		title: "갤러리 상세보기",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup180" ).click(function() {
		$( "#diapup180" ).dialog( "close" );
	});
	$( ".btn_diapup180" ).click(function() {
		$( "#diapup180" ).dialog( "open" );
	});
	$( "#dbtn_diapup180" ).click(function() {
		$( "#diapup180" ).dialog( "close" );
	});
	$( "#rbtn_diapup180" ).click(function() {
		$( "#diapup180" ).dialog( "close" );
	});
	
	// 유지보수 수선정보 삭제
	$('#diapup181').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "유지보수 수선정보 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup181" ).click(function() {
		$( "#diapup181" ).dialog( "close" );
	});
	$( "#btn_diapup181" ).click(function() {
		$( "#diapup181" ).dialog( "open" );
	});
	$( "#abtn_diapup181" ).click(function() {
		$( "#diapup181" ).dialog( "close" );
		$( "#diapup182" ).dialog( "open" );
	});

	// 유지보수 수선정보 삭제
	$('#diapup182').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "유지보수 수선정보 삭제",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	$( "#cbtn_diapup182" ).click(function() {
		$( "#diapup182" ).dialog( "close" );
	});
	
	// 유지보수 수선 정보 직접 입력하기
	$('#diapup183').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 440,
		modal: true,
		title: "유지보수 수선정보 직접입력",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup183" ).click(function() {
		$( "#diapup183" ).dialog( "close" );
	});
	// $( "#btn_diapup183" ).click(function() {
		// $( "#diapup183" ).dialog( "open" );
	// });
	// $( "#abtn_diapup183" ).click(function() {
		// $( "#diapup183" ).dialog( "close" );
	// });
	$( "#obtn_diapup183" ).click(function() {
		$( "#diapup183" ).dialog( "close" );
		$( "#diapup184" ).dialog( "open" );
	});
	$( "#pbtn_diapup183" ).click(function() {
		$( "#diapup185" ).dialog( "open" );
	});
	
	// 유지보수 수선 정보 Excel 입력하기
	$('#diapup184').dialog({
		autoOpen: false,
		resizable: false,
		width: 520,
		height: 220,
		modal: true,
		title: "유지보수 수선정보 Excel입력",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup184" ).click(function() {
		$( "#diapup184" ).dialog( "close" );
	});
	$( "#btn_diapup184" ).click(function() {
		$( "#diapup184" ).dialog( "open" );
	});
	$( "#abtn_diapup184" ).click(function() {
		$( "#diapup184" ).dialog( "close" );
	});
	$( "#obtn_diapup184" ).click(function() {
		$( "#diapup184" ).dialog( "close" );
		$( "#diapup183" ).dialog( "open" );
	});
	
	// LCI DB 검색
	$('#diapup185').dialog({
		autoOpen: false,
		resizable: false,
		width: 420,
		height: 520,
		modal: true,
		title: "LCI DB 검색",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#cbtn_diapup185" ).click(function() {
		$( "#diapup185" ).dialog( "close" );
	});
	$( "#btn_diapup185" ).click(function() {
		$( "#diapup185" ).dialog( "open" );
	});

	// 아이디찾기
	$('#diapup186').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 250,
		modal: true,
		title: "아이디 찾기",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	// $( "#sbtn_diapup186" ).click(function() {
		// $( "#diapup186" ).dialog( "close" );
		// $( "#diapup188" ).dialog( "open" );
	// });
	$( "#btn_diapup186" ).click(function() {
		$( "#diapup186" ).dialog( "open" );
	});
	
	// 비밀번호찾기
	$('#diapup187').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 250,
		modal: true,
		title: "비밀번호 찾기",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	// $( "#sbtn_diapup187" ).click(function() {
		// $( "#diapup187" ).dialog( "close" );
		// $( "#diapup189" ).dialog( "open" );
	// });
	$( "#btn_diapup187" ).click(function() {
		$( "#diapup187" ).dialog( "open" );
	});
	
	
	// 아이디찾기2
	$('#diapup188').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 250,
		modal: true,
		title: "아이디 찾기",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#rbtn_diapup188" ).click(function() {
		$( "#diapup188" ).dialog( "close" );
		$( "#diapup186" ).dialog( "open" );
	});
	$( "#cbtn_diapup188" ).click(function() {
		$( "#diapup188" ).dialog( "close" );
	});
	
	
	// 비밀번호찾기2
	$('#diapup189').dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		height: 250,
		modal: true,
		title: "비밀번호 찾기",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	//$(".ui-dialog-titlebar").hide();	
	$( "#rbtn_diapup189" ).click(function() {
		$( "#diapup189" ).dialog( "close" );
		$( "#diapup187" ).dialog( "open" );
	});
	$( "#obtn_diapup189" ).click(function() {
		$( "#diapup189" ).dialog( "close" );
		$( "#diapup186" ).dialog( "open" );
	});
	$( "#cbtn_diapup189" ).click(function() {
		$( "#diapup189" ).dialog( "close" );
	});

	// LCA보고서
	$('#diapup_LCASTATIS').dialog({
		autoOpen: false,
		resizable: false,
		width: 1000,
		height: 700,
		modal: true,
		title: "LCA보고서",
		closeOnEscape: true,
		dialogClass: "alert",
		draggable: false

	});
	$( "#btn_LCASTATIS" ).click(function() {
		$("#diapup_LCASTATIS" ).dialog( "open" );
	});
	

});

