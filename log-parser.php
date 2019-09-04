<?php
$log = file_get_contents('accesslog_kylebeard.com_9_1_2019');
$pattern = '~^(\S+) (\S+) (\S+) \[([\w:/]+\s[+\-]\d{4})\] \"(\S+) (\S+) (\S+)\" (\d+) (\S+) \"(\S+)\" \"(\S+.*)\" (\S+) (\S+)$~m';
if(preg_match_all($pattern, $log, $match, PREG_PATTERN_ORDER)) {
    $ip = $match[1];
    $datetime = $match[4];
    $method = $match[5];
    $request = $match[6];
    $protocol = $match[7];
    $status = $match[8];
    $size = $match[9];
    $referrer = $match[10];
    $userAgent = $match[11];
}

function getNumberOfErrors($status) {
    $errors = 0;
    foreach ($status as $entry) {
        if($entry >= 400) {
            $errors += 1;
        }
    }
    return $errors;
}

function getNumberOfSuccesses($status) {
    $successes = 0;
    foreach ($status as $entry) {
        if($entry < 400) {
            $successes += 1;
        }
    }
    return $successes;
}

function getMostFrequentFiles() {

}

function getMostPopularReferrers() {

}

?>

<h1>Log Parser</h1>
<dl>
    <dt>Total Number Of Entries</dt>
    <dd><?= count($match[0]) ?></dd>
</dl>
<dl>
    <dt>Errors</dt>
    <dd><?= getNumberOfErrors($status) ?></dd>
</dl>
<dl>
    <dt>Successes</dt>
    <dd><?= getNumberOfSuccesses($status) ?></dd>
</dl>
<dl>
    <dt>Most Frequent Files</dt>
    <dd>
        <ul>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </dd>
</dl>
<dl>
    <dt>Most Popular Referrers</dt>
    <dd></dd>
</dl>
