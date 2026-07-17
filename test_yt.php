<?php
$q = ['hospital+bag+checklist+pregnancy', 'breastfeeding+basics'];
foreach($q as $t) {
    if ($h = @file_get_contents('https://www.youtube.com/results?search_query='.$t)) {
        if (preg_match('/"videoId":"([a-zA-Z0-9_-]{11})"/', $h, $m)) {
            echo $t.': ' . $m[1] . "\n";
        } else {
            echo $t.": No match found\n";
        }
    } else {
        echo $t.": Failed to fetch\n";
    }
}
?>
