<?php
function e($str,$charset = 'UTF-8') {
    return htmlspecialchars($str,ENT_QUOTES|ENT_HTML5,$charset,false);
}


?>