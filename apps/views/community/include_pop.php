<div id="write_bbs_pop">
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="100" />
                </colgroup>
                <form id="dataform" name="dataform" method="post" enctype="multipart/form-data">
                <tr><input type="hidden" name="bbs" id="bbs" value="<?php echo $table_name;?>"><input type="hidden" name="bseq" id="bseq" value=""><input type="hidden" name="bjob" id="bjob" value="">
                    <th>제목</th>
                    <td colspan="3"><input class="inp1" id="btitle" name="btitle" value="" /></td>
                </tr>
                <tr>
                    <th>작성자</th>
                    <td><span id="warea_01"></span></td>
                </tr>
                <tr>
                    <th>작성일</th>
                    <td><span id="warea_02"></span></td>
                </tr>
                <tr>
                    <th>첨부파일</th>
                    <td><input type="file" class="file1" name="fupload" id="fupload" /><span id="warea_03"></span></td>
                </tr>
                <tr>
                    <td colspan="2"><textarea class="ckeditor" id="contents1" name="contents1" cols="100%" rows="8"></textarea></td>
                </tr>
                </form>
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><button type="button" class="st2" id="abtn_diapup176" onclick="check_write();">저장</button> <button type="button" class="st4">다시쓰기</button> <button type="button" id="cbtn_diapup176" class="">취소</button></div>
        </dd><input type="hidden" name="nwriter" id="nwriter" value="<?php echo $session_id; ?>"><input type="hidden" name="ndate" id="ndate" value="<?php echo date('Y-m-d');?>"><input type="hidden" name="nfile" id="nfile" value="<?php echo $need_file;?>">
    </dl>
</div>

<div id="view_bbs_pop">
    <dl class="tablebox4_1">
        <dd>
            <table class="tablewritetype4" border="0" cellpadding="0" cellspacing="0">
                <colgroup>
                <col width="100" />
                </colgroup>
                <tr>
                    <th>제목</th>
                    <td colspan="3"><span id="area_01"></span></td>
                </tr>
                <tr>
                    <th>작성자</th>
                    <td><span id="area_02"></span></td>
                </tr>
                <tr>
                    <th>작성일</th>
                    <td><span id="area_03"></span></td>
                </tr>
                <tr>
                    <th>첨부파일</th>
                    <td><span id="area_04"></span></td>
                </tr>
                <tr>
                    <td colspan="2"><p style="text-align:left;" id="area_05"></p></td>
                </tr><input type="hidden" id="jdata" name="jdata" />
            </table>
        </dd>
        <dd class="bbsbtnbox1">
            <div class="btntype3"><span id="area_a01"></span><?php if ($bbs_reply && $session_id != "") {?> <button type="button" class="st4" onclick="go_modify('reply');">답변</button><?php }?><span id="area_a02"></span> <button type="button" id="cbtn_diapup177" class="" onclick="close_view()">창 닫기</button></div>
        </dd>
    </dl>
</div>
