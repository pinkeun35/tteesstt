<?php
function utf8_strcut($str, $chars, $tail = '...') {  
	if (utf8_length($str) <= $chars)	//전체 길이를 불러올 수 있으면 tail을 제거한다.
		$tail = '';
	else
		$chars -= utf8_length($tail);//글자가 잘리게 생겼다면 tail 문자열의 길이만큼 본문을 빼준다.
		
	$len = strlen($str);
	
	for ($i = $adapted = 0; $i < $len; $adapted = $i) {
		$high = ord($str{$i});
		if ($high < 0x80)
			$i += 1;
		else if ($high < 0xE0)
			$i += 2;
		else if ($high < 0xF0)
			$i += 3;
		else
			$i += 4;
		if (--$chars < 0)
			break;
	}
	
	return trim(substr($str, 0, $adapted)) . $tail;
}

function utf8_length($str) {
	$len = strlen($str);
	for ($i = $length = 0; $i < $len; $length++) {
		$high = ord($str{$i});
		if ($high < 0x80)//0<= code <128 범위의 문자(ASCII 문자)는 인덱스 1칸이동
			$i += 1;
		else if ($high < 0xE0)//128 <= code < 224 범위의 문자(확장 ASCII 문자)는 인덱스 2칸이동
			$i += 2;
		else if ($high < 0xF0)//224 <= code < 240 범위의 문자(유니코드 확장문자)는 인덱스 3칸이동 
			$i += 3;
		else//그외 4칸이동 (미래에 나올문자)
			$i += 4;
	}
	
	return $length;
}

function date_diffs($d1, $d2){
	$d1 = (is_string($d1) ? strtotime($d1) : $d1);
	$d2 = (is_string($d2) ? strtotime($d2) : $d2);  
	$diff_secs = abs($d1 - $d2);
	
	return floor($diff_secs / (3600 * 24));
}
?>