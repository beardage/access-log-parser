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
        return count($this->matchArray);
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

    function getMostFrequentFiles () {
        // remove params from requests
        $requestsSansParams = [];
        foreach ( $this->requests as $entry ) {
            $pos = strpos($entry, "?");
            if ( $pos ) {
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

    function getFilePercentages () {
        // remove params from requests
        $requestsSansParams = [];
        foreach ( $this->requests as $entry ) {
            $pos = strpos($entry, "?");
            if ( $pos ) {
                $requestsSansParams[] = substr($entry, 0, $pos);
            } else {
                $requestsSansParams[] = $entry;
            }
        }

        $total = count($requestsSansParams);
        $requestsCount = array_count_values($requestsSansParams);
        arsort($requestsCount);
        $percentages = [];
        foreach ( $requestsCount as $request ) {
            $percentages[] = round((intval($request) / $total) * 100);
        }
        return $percentages;
    }

    function getMostPopularReferrers () {
        // sort and quantify
        $referrers = array_count_values($this->referrers);
        arsort($referrers);
        $popular = array_slice(array_keys($referrers), 0, 3, true);
        return $popular;
    }

    function getReferrerPercentage () {
        $total = count($this->referrers);
        $referrersCount = array_count_values($this->referrers);
        arsort($referrersCount);
        $percentages = [];
        foreach ( $referrersCount as $referrer ) {
            $percentages[] = round((intval($referrer) / $total) * 100);
        }
        return $percentages;
    }
}






