<?php
// grab log and parse each line into groups
$log = file_get_contents('accesslog_kylebeard.com_9_1_2019');
$pattern = '~^(\S+) (\S+) (\S+) \[([\w:/]+\s[+\-]\d{4})\] \"(\S+) (\S+) (\S+)\" (\d+) (\S+) \"(\S+)\" \"(\S+.*)\" (\S+) (\S+)$~m';
if(preg_match_all($pattern, $log, $match, PREG_PATTERN_ORDER)) {
    $ips = $match[1];
    $datetimes = $match[4];
    $methods = $match[5];
    $requests = $match[6];
    $protocols = $match[7];
    $statuses = $match[8];
    $sizes = $match[9];
    $referrers = $match[10];
    $userAgents = $match[11];
}

function getNumberOfErrors($statuses) {
    $errors = 0;
    foreach ($statuses as $entry) {
        if($entry >= 400) {
            $errors += 1;
        }
    }
    return $errors;
}

function getNumberOfSuccesses($statuses) {
    $successes = 0;
    foreach ($statuses as $entry) {
        if($entry < 400) {
            $successes += 1;
        }
    }
    return $successes;
}

function getMostFrequentFiles($requests) {
    // remove params from requests
    $requestsSansParams = [];
    foreach($requests as $entry) {
        $pos = strpos($entry, "?");
        if($pos) {
            $requestsSansParams[] = substr($entry, 0, $pos);
        } else {
            $requestsSansParams[] = $entry;
        }
    }

    // sort and quantify
    $requestsSansParams = array_count_values($requestsSansParams);
    arsort($requestsSansParams);
    $frequent = array_slice(array_keys($requestsSansParams), 0, 3, true);
    return $frequent;
}

function getFilePercentages($requests) {
    // remove params from requests
    $requestsSansParams = [];
    foreach($requests as $entry) {
        $pos = strpos($entry, "?");
        if($pos) {
            $requestsSansParams[] = substr($entry, 0, $pos);
        } else {
            $requestsSansParams[] = $entry;
        }
    }

    $total = count($requestsSansParams);
    $requestsCount = array_count_values($requestsSansParams);
    arsort($requestsCount);
    $percentages = [];
    foreach ($requestsCount as $request) {
        $percentages[] = round((intval($request) / $total) * 100);
    }
    return $percentages;
}

function getMostPopularReferrers($referrers) {
    // sort and quantify
    $referrers = array_count_values($referrers);
    arsort($referrers);
    $popular = array_slice(array_keys($referrers), 0, 3, true);
    return $popular;
}

function getReferrerPercentage ($referrers) {
    $total = count($referrers);
    $referrersCount = array_count_values($referrers);
    arsort($referrersCount);
    $percentages = [];
    foreach ($referrersCount as $referrer) {
        $percentages[] = round((intval($referrer) / $total) * 100);
    }
    return $percentages;
}

?>

<h1>Log Parser</h1>
<table>
    <tbody>
        <tr>
            <td>Total Entries</td>
            <td><?= count($match[0]) ?></td>
        </tr>
        <tr>
            <td>Errors (> 400)</td>
            <td><?= getNumberOfErrors($statuses) ?></td>
        </tr>
        <tr>
            <td>Successes (< 400)</td>
            <td><?= getNumberOfSuccesses($statuses) ?></td>
        </tr>
        <tr>
            <td>Most Frequent Files</td>
            <td>
                <?php
                $mostFrequentFiles = getMostFrequentFiles($requests);
                $filePercentages = getFilePercentages($requests);
                ?>
                <?= $mostFrequentFiles[0] ?> <span><?= $filePercentages[0] ?>%</span><br>
                <?= $mostFrequentFiles[1] ?> <span><?= $filePercentages[1] ?>%</span><br>
                <?= $mostFrequentFiles[2] ?> <span><?= $filePercentages[2] ?>%</span>
            </td>
        </tr>
        <tr>
            <td>Most Popular Referrers</td>
            <td>
                <?php
                $mostFrequentReferrers = getMostPopularReferrers($referrers);
                $referrerPercentages = getReferrerPercentage($referrers);
                ?>
                <?= $mostFrequentReferrers[0] ?> <span><?= $referrerPercentages[0] ?>%</span><br>
                <?= $mostFrequentReferrers[1] ?> <span><?= $referrerPercentages[1] ?>%</span><br>
                <?= $mostFrequentReferrers[2] ?> <span><?= $referrerPercentages[2] ?>%</span>
            </td>
        </tr>
    </tbody>
</table>
<style>
    body {
        font-family: sans-serif;
    }
    table {
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid black;
    }
    table td {
        padding: 5px;
    }
    table td span {
        color: seagreen;

        padding-left: 10px;
    }
</style>
