<?php
include 'LogParser.php';
$parser = new LogParser(file_get_contents('accesslog_kylebeard.com_9_1_2019'));
?>


<h1>Log Parser</h1>
<table>
    <tbody>
        <tr>
            <td>Total Entries</td>
            <td><?= $parser->getNumberOfEntries(); ?></td>
        </tr>
        <tr>
            <td>Errors (> 400)</td>
            <td><?= $parser->getNumberOfErrors() ?></td>
        </tr>
        <tr>
            <td>Successes (< 400)</td>
            <td><?= $parser->getNumberOfSuccesses() ?></td>
        </tr>
        <tr>
            <td>Most Frequent Files</td>
            <td>
                <?php
                $mostFrequentFiles = $parser->getMostFrequentFiles();
                $filePercentages = $parser->getFilePercentages();
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
                $mostFrequentReferrers = $parser->getMostPopularReferrers();
                $referrerPercentages = $parser->getReferrerPercentage();
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
