<?php

function view($filename, $title = ''){
     require_once __DIR__. '/../inc/'.$filename;
}

function getAgoDate($date){
     date_default_timezone_set('Asia/Manila');
     $current_date = 'few seconds ago';
     $seconds = strtotime(date('m/d/Y h:i:s')) - strtotime($date);
     $seconds_to_minute = 60;
     $seconds_to_hours = 3600;
     $seconds_to_day = 86400;
     $seconds_to_week = 604800;
     $number_of_months = floor(floor($seconds / $seconds_to_week) / 4);
     if ($seconds >= $seconds_to_minute && $seconds <= $seconds_to_hours) {
           $number_of_minutes = floor($seconds / $seconds_to_minute);
           $current_date = "$number_of_minutes minutes ago";
           if ($number_of_minutes == 1) {
             $current_date = "1 minute ago";
           }
     }
     if ($seconds >= $seconds_to_hours && $seconds <= $seconds_to_day) {
           $number_of_hours = floor($seconds / $seconds_to_hours);
           $current_date = "$number_of_hours hours ago";
           if ($number_of_hours == 1) {
             $current_date = "1 hour ago";
           }
     }
     if ($seconds >= $seconds_to_day && $seconds <= $seconds_to_week) {
           $number_of_days = floor($seconds / $seconds_to_day);
           $current_date = "$number_of_days days ago";
           if ($number_of_days == 1) {
             $current_date = "1 day ago";
           }
     }

     if ($seconds >= $seconds_to_week){
          $number_of_weeks = floor($seconds / $seconds_to_week);
          $current_date = "$number_of_weeks weeks ago";
          if ($number_of_weeks == 1) {
            $current_date = "1 week ago";
          }
     }
      
     if ($number_of_months >= 1) {
         $current_date = "$number_of_months months ago";
         if ($number_of_months == 1) {
           $current_date = "1 month ago";
         }
     }
     return $current_date;
 }