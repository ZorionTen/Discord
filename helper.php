<?php

function zlog(string $text){
    file_put_contents(ROOT."/LOGFILE","");
    file_put_contents(ROOT."/LOGFILE",$text);
}