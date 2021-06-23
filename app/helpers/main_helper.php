<?php 

//////////////////////////////////////////////////////////////////////
//PARA: Date Should In YYYY-MM-DD Format
//RESULT FORMAT:
// '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
// '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
// '%m Month %d Day'                                            =>  3 Month 14 Day
// '%d Day %h Hours'                                            =>  14 Day 11 Hours
// '%d Day'                                                        =>  14 Days
// '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
// '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
// '%h Hours                                                    =>  11 Hours
// '%a Days                                                        =>  468 Days
//////////////////////////////////////////////////////////////////////
function dateDifference($date1, $date2, $differenceFormat = '%a'){
    $datetime1 = date_create($date1);
    $datetime2 = date_create($date2);
   
    $interval = date_diff($datetime1, $datetime2);
   
    return $interval->format($differenceFormat);
}

function timeDisplay($reqDate) {

    // if date is older than a year
    if (dateDifference(date('Y-m-d H:i:s', strtotime($reqDate)), date('Y-m-d H:i:s'), '%y') > 0) {
        // return date with year
        return date('M j, Y', strtotime($reqDate));
    } else {
        // if older than a day
        if ((dateDifference(date('Y-m-d H:i:s', strtotime($reqDate)), date('Y-m-d H:i:s'), '%a') > 0)) {
            // else return date with no year
            return date('M j', strtotime($reqDate));
        } else {
            if (dateDifference(date('Y-m-d H:i:s', strtotime($reqDate)), date('Y-m-d H:i:s'), '%h') < 1) {
                // if less than a minute old
                if (dateDifference(date('Y-m-d H:i:s', strtotime($reqDate)), date('Y-m-d H:i:s'), '%i') < 1) {
                    // return time in seconds
                    return dateDifference(date('Y-m-d H:i:s', strtotime($reqDate)), date('Y-m-d H:i:s'), '%ss');
                } else {
                    // return time in mintutes
                    return dateDifference(date('Y-m-d H:i:s', strtotime($reqDate)), date('Y-m-d H:i:s'), '%im');
                }
            }else{
                // return time in hours
                return dateDifference(date('Y-m-d H:i:s', strtotime($reqDate)), date('Y-m-d H:i:s'), '%hh');
            }
        }
    }
}