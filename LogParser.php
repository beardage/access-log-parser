<?php

class LogParser {
    private $ips;
    private $datetimes;
    private $methods;
    private $requests;
    private $protocols;
    private $statuses;
    private $sizes;
    private $referrers;
    private $userAgents;
    private $matchArray;

    function __construct($file) {
        // grab log and parse each line into groups
        $pattern = '~^(\S+) (\S+) (\S+) \[([\w:/]+\s[+\-]\d{4})\] \"(\S+) (\S+) (\S+)\" (\d+) (\S+) \"(\S+)\" \"(\S+.*)\" (\S+) (\S+)$~m';
        if ( preg_match_all($pattern, $file, $match, PREG_PATTERN_ORDER) ) {
            $this->matchArray = $match;
            $this->ips = $match[1];
            $this->datetimes = $match[4];
            $this->methods = $match[5];
            $this->requests = $match[6];
            $this->protocols = $match[7];
            $this->statuses = $match[8];
            $this->sizes = $match[9];
            $this->referrers = $match[10];
            $this->userAgents = $match[11];
        }

    }

    function getNumberOfEntries () {
        return count($this->matchArray[0]);
    }

    function getNumberOfErrors () {
        $errors = 0;
        foreach ( $this->statuses as $entry ) {
            if ( $entry >= 400 ) {
                $errors += 1;
            }
        }
        return $errors;
    }

    function getNumberOfSuccesses () {
        $successes = 0;
        foreach ( $this->statuses as $entry ) {
            if ( $entry < 400 ) {
                $successes += 1;
            }
        }
        return $successes;
    }

    function getFileData () {
        // remove params from requests
        $files = [];
        foreach ( $this->requests as $entry ) {
            $pos = strpos($entry, "?");
            if ( $pos ) {
                $files[] = substr($entry, 0, $pos);
            } else {
                $files[] = $entry;
            }
        }
        $fileData = [];
        $fileData['files'] = array_values(array_unique($files));
        $fileData['counts'] = array_count_values($files);
        arsort($fileData['counts']);
        return $fileData;
    }


    function getReferrerData () {
        $referrerData = [];
        $referrerData['referrers'] = array_values(array_unique($this->referrers));
        $referrerData['counts'] = array_count_values($this->referrers);
        arsort($referrerData['counts']);
        return $referrerData;
    }
}






