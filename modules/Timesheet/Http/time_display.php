<?php
Form::macro('timeNoResult', function()
{
	return "<div class=\"well\">Time to track your first work.</div>";
});

Form::macro('groupByDay', function($entries, $filter, $format = "Y-m-d")
{
	if ($filter && isset($filter['start'])) {
        $begin = new \DateTime($filter['start']);
        $end = new \DateTime($filter['end']);
        $end->modify('+1 day');
    } else {
        $begin = new \DateTime($this->getDay('monday', $format));
        $end = new \DateTime($this->getDay('sunday', $format));
        $end->modify('+1 day');
    }

    $allTotal = 0;

    $interval = \DateInterval::createFromDateString('1 day');
    $period = new \DatePeriod($begin, $interval, $end);

    $summary = [];
    foreach ( $period as $dt ) {
        $summary[$dt->format($format)] = ['entry' => null, 'total' => 0];
    }

    foreach($entries as $timeEntry) {
        $date = date('Y-m-d', strtotime($timeEntry->start));
        $total = 0;
        if ($timeEntry->end) {
            $total = strtotime($timeEntry->end) - strtotime($timeEntry->start);    
        }
        
        if (!isset($summary[$date]) || is_null($summary[$date]['entry'])) {
            $summary[$date]['entry'] = $timeEntry;
            $summary[$date]['total'] = $total;
        } else {
            $summary[$date]['total'] += $total;
        }

        $allTotal += $total;
    }

    return [
    	'list' => $summary,
    	'total' => $allTotal
    ];
});