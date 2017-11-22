<?php


$string = "Dear NTLAPU ANNA MAKHETHA Hopola ho lefa Ground Rent ea setša sa nomoro:18333-087(e kolotang: M 2 794.42 ), ho qoba likotlo.Bakeng sa litlhakisetso letsetsa 22214100";
if (isUtf8($string)){
    //echo " Yes true is UTF8".PHP_EOL;
    
    $out_msg = mb_convert_encoding($string, "UTF-8");

# Don't forget to URLEncode or else you might get unwanted string termination
   $out_msg =  urlencode($out_msg);
   echo '    '.$out_msg;
}
echo "  Is UTF8 ---- ".utf8($string)."  is ".  isUtf8($string);

function isUtf8($string) {
    if (function_exists("mb_check_encoding") && is_callable("mb_check_encoding")) {
        return mb_check_encoding($string, 'UTF8');
    }

    return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);

} 


function utf8($utf8){   
if(mb_detect_encoding($utf8,'UTF-8',true) =='UTF-8'); 
//return $utf8; 
else
    
$utf8=iconv("windows-1256","utf-8",$utf8);
return $utf8;
  }
  
  ?>