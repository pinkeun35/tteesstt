<?php
	include_once(APPPATH.'/views/header.php');
	include_once(APPPATH.'/views/community/bbs_function.php');
	
	$bbs_newicon = ' <img src="/app/views/images/common/icon_new1.gif" alt="새글" />';
?>
<!-- //header -->

<script type="text/javascript" charset="utf-8" src="/app/views/js/util.js"></script>
<script type="text/javascript">
<!--
<?php
	if (!empty($popup)) {
?>
$(function() {
	go_popup();
});

function go_popup() {
	$("#write_bbs_pop").dialog({
        title: '<?php echo $popup->title; ?>'
    });
	$( "#write_bbs_pop" ).dialog( "open" );
}
<?php
	}
?>
-->
</script>


<!-- contents -->
<div id="mainwrap">
	<div class="mainbox">
    	<div class="mainvisual"><img src="/app/views/images/temp/visual2.png" /><!--script>ShowFlash("/app/views/images/portal/swf/gnb.swf", "1020", "425", "false", "transparent", "always", "", "swfgnb");</script--></div>
        
        <ul class="mainbbswrap">
            
            <li class="mainbbsbox1">
                <h2><a href="/community/free_list">자유게시판</a></h2>
<?php
	foreach($free as $key => $val) {
		$w_due = date_diffs(substr($free[$key]->wdate, 0, 10), "".date("Y-m-d"));
?>
            	<dl class="bbslist" onclick="javascript:location.href='/community/free_list/view/<?php echo $free[$key]->seq;?>'">
                	<dt><?php echo utf8_strcut($free[$key]->title, 20);?></dt>
		            <dd>
	                    <p class="cont1"><?php echo utf8_strcut($free[$key]->contents, 100);?> <?php if ($w_due < 2) { echo $bbs_newicon; }?></p>
	                    <p class="cont2"><?php echo substr($free[$key]->wdate, 0, 10);?></p>
	                    <p class="cont3"><img src="/app/views/images/temp/noimage_100.gif" alt="이미지" /></p>
                    </dd>
                </dl>
<?php
	}
?>
            </li>
            
            <li class="mainbbsbox2">
                <h2><a href="/lcidb/materials_list">LCI DB</a></h2>
            	<dl class="bbslist">
<?php
	foreach($lci as $key => $val) {
		$css_text = "cont2";
		if (($key % 2) == 0)
			$css_text = "cont1";
		
		echo '					<dd class="'.$css_text.'"><strong>'.$lci[$key]->uname.'</strong> <span>|</span> '.$lci[$key]->ustandard.' <span>|</span> '.$lci[$key]->uculceo.'</dd>'."\n";
	}
?>
                </dl>
            </li>

            <li id="mainbbsbox3a" class="mainbbsbox3">
                <h3>공지사항</h3>
            	<dl class="bbslist">
                	<dt><a href="javascript:aler('공지사항목록');"><strong>공지사항</strong></a> | <a href="javascript:aler('자료실목록');" onmouseover="MM_showHideLayers('mainbbsbox3a','','hide','mainbbsbox3b','','show')">자료실</a></dt>
<?php
	foreach($notice as $key => $val) {
		$w_due = date_diffs(substr($notice[$key]->wdate, 0, 10), "".date("Y-m-d"));
		echo '					<dd><a href="/community/notice_list/view/'.$notice[$key]->seq.'">'.utf8_strcut($notice[$key]->title, 11).'</a> ';
		if ($w_due < 2) {
			echo $bbs_newicon;
		}
		echo '</dd>'."\n";
	}
?>
		            <dd class="more"><a href="/community/notice_list"><span>&gt;</span> 더보기</a></dd>
                </dl>
            </li>

            <li id="mainbbsbox3b" class="mainbbsbox3">
                <h3>자료실</h3>
            	<dl class="bbslist">
                	<dt><a href="javascript:aler('공지사항목록');" onmouseover="MM_showHideLayers('mainbbsbox3a','','show','mainbbsbox3b','','hide')">공지사항</a> | <a href="javascript:aler('자료실목록');"><strong>자료실</strong></a></dt>
<?php
	foreach($datas as $key => $val) {
		$w_due = date_diffs(substr($datas[$key]->wdate, 0, 10), "".date("Y-m-d"));
		echo '					<dd><a href="/community/data_list/view/'.$datas[$key]->seq.'">'.utf8_strcut($datas[$key]->title, 11).'</a> ';
		if ($w_due < 2) {
			echo $bbs_newicon;
		}
		echo '</dd>'."\n";
	}
?>
		            <dd class="more"><a href="/community/data_list"><span>&gt;</span> 더보기</a></dd>
                </dl>
            </li>

        </ul>

    </div>
</div>

<?php
	if (!empty($popup)) {
?>
<div id="write_bbs_pop">
    <dl class="tablebox4_1">
        <dd>
            <?php echo $popup->contents;?>
        </dd>
    </dl>
</div>
<?php
		echo '<script type="text/javascript">'."\n";
		echo '	$("#write_bbs_pop").dialog({'."\n";
		echo '		title: "'.$popup->title.'"'."\n";
		echo '	});'."\n";
		echo '	$( "#write_bbs_pop" ).dialog( "open" );'."\n";
		echo '</script>'."\n";
	}
?>
<!-- //contents -->

<!-- footer -->
<?php
	include_once(APPPATH.'/views/bottom.php');
?>