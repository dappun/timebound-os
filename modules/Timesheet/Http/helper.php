<?php
if (!function_exists('computeDuration')) {
    
function computeDuration($start, $end, $format = 'string') {
    $duration = 0;

    $start_date = new \DateTime($start);
    $since_start = $start_date->diff(new \DateTime($end));
    
    $diff = $since_start->days * 24 * 60;
    $diff += $since_start->h * 60 * 60;
    $diff += $since_start->i * 60;
    $diff += $since_start->s;

    if ($format == 'string') {
        return $diff;    
    } else {
        return [
            'h' => $since_start->h,
            'm' => $since_start->i,
            's' => $since_start->h
        ];
    }
}

function timeElapsedString($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    if ($diff->d == 0) {
        return 'Today';
    } else if ($diff->d == 1) {
        return 'Yesterday';
    }

    return date('M d, Y', strtotime($datetime));
}

function gmhours($seconds, $format = 'full') {
    $hours = floor($seconds / 3600);
    $minutes = floor($seconds / 60 % 60);
    $secs = floor($seconds % 60);

    if ($format == 'short') {
        return str_pad($hours, 2, '0', STR_PAD_LEFT)
             . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
    } else if ($format == 'number') {
        return $hours + ($minutes / 60);
    } else {
        return str_pad($hours, 2, '0', STR_PAD_LEFT)
             . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT)
              . ':' . str_pad($secs, 2, '0', STR_PAD_LEFT);
    }
}



function convertDate($value, $type = 'db-to-user')
{
    $dbtz = \Config::get('core.timezone.db');
    if (\Auth::check()) {
        $ustz = \Auth::user()->getTimezone();
    } else {
        $ustz = \Config::get('core.timezone.display');
    }
    
    if ($type == 'db-to-user') {
        $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, $dbtz);
        $date->setTimezone($ustz);
    } else {
        $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, $ustz);
        $date->setTimezone($dbtz);
    }

    return $date;
}

function calculateDateRanges($date, $interval)
{
    $today = new DateTime();
    $results = [];

    $results = [];

    if ($interval == 'week') {
        $d = new DateTime($date['from']);
        $d->modify('monday this week');
        
        $daterange = new DatePeriod(
            $d,
            new DateInterval('P7D'),
            new DateTime($date['to'])
        );

        foreach($daterange as $date){
            if ($date > $today) break;

            $to = clone $date;
            $to = $to->modify('+6 day');
            if ($to > $today) {
                $to = $today;
            }

            $results[] = [
                'from' => $date->format("Y-m-d"),
                'to' => $to->format("Y-m-d")
            ];
        }

    } else if ($interval == 'month') {
        $d = new DateTime($date['from']);
        $start = $d->modify('first day of this month')->format("Y-m-d");

        $daterange = new DatePeriod(
             new DateTime($start),
             new DateInterval('P1M'),
             new DateTime($date['to'])
        );

        foreach($daterange as $date){
            if ($date > $today) break;

            $to = clone $date;
            $to = $to->modify('last day of this month');
            if ($to > $today) {
                $to = $today;
            }

            $results[] = [
                'from' => $date->format("Y-m-d"),
                'to' => $to->format("Y-m-d")
            ];
        }
    } else if ($interval == 'day') {
        $fr = new DateTime($date['from']);
        $to = new DateTime($date['to']);
        
        if ($fr->format("Y-m-d") == $to->format("Y-m-d")) {
            $results[] = [
                'from' => $fr->format("Y-m-d"),
                'to' => $fr->format("Y-m-d")
            ];
        } else {
            $daterange = new DatePeriod($fr, new DateInterval('P1D'), $to);
            
            foreach($daterange as $date){
                if ($date > $today) break;

                $results[] = [
                    'from' => $date->format("Y-m-d"),
                    'to' => $date->format("Y-m-d")
                ];
            }

            if ($to <= $today) {
                $results[] = [
                    'from' => $to->format("Y-m-d"),
                    'to' => $to->format("Y-m-d")
                ];
            }
        }
            
    } else {
        $fr = new DateTime($date['from']);
        $to = new DateTime($date['to']);

        $results[] = [
            'from' => $fr->format("Y-m-d"),
            'to' => $to->format("Y-m-d")
        ];
    }

    return $results;
}

}