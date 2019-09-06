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
                $fileData = $parser->getFileData();
                foreach ($fileData['counts'] as $key => $value) {
                    $percentage = round( ( (intval($value) / $parser->getNumberOfEntries() ) * 100) );
                    ?>
                    <p><?= $key ?> <span>(<?= $percentage ?>%)</span></p>
                    <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Most Popular Referrers</td>
            <td>
                <?php
                $referrerData = $parser->getReferrerData();
                foreach ($referrerData['counts'] as $key => $value) {
                    $percentage = round( ( (intval($value) / $parser->getNumberOfEntries() ) * 100) );
                    ?>
                    <p><?= $key ?> <span>(<?= $percentage ?>%)</span></p>
                    <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Most Popular User Agents</td>
            <td>
                <?php
                $userAgentData = $parser->getUserAgentData();
                foreach ($userAgentData['counts'] as $key => $value) {
                    $percentage = round( ( (intval($value) / $parser->getNumberOfEntries() ) * 100) );
                    ?>
                    <p><?= $key ?> <span>(<?= $percentage ?>%)</span></p>
                    <?php
                }
                ?>
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
