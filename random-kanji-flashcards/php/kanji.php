<?php

// load js/jlpt4.js

$file2load 	= '../js/' . $_GET['file'];

$kanji 		= file_get_contents($file2load);

/*
$kanji =<<< EOK
var KanjiArray = [{
    "name": "日",
    "kana": "ニチ ・ ジツ ・ ひ ・ -び ・ -か",
    "english": "day; sun; Japan; counter for days",
    "words": ""
}, {
    "name": "一",
    "kana": "イチ ・ イツ ・ ひと- ・ ひと.つ",
    "english": "one; one radical (no.1)",
    "words": "一番; "
}];
EOK;
*/

echo $kanji;

exit(0);

