<?php
namespace App\Http\Controllers;


use App\Designation;
use Illuminate\Http\Request;
use DateTime;

class TestController extends Controller
{
    public function test()
    {
        return view('prr_supply.test');
        //FOR TOMORROW SET LAST HOUR IN 8
        $start_date = "2018-11-14 12:30:00";
        $end_date = "2018-11-14 13:30:00";
        //$end_date = date("Y-m-d H:i:s");

        $start_checker = date("Y-m-d",strtotime($start_date));
        $end_checker = date("Y-m-d",strtotime($end_date));
        $fhour_checker = date("H",strtotime($start_date));
        $lhour_checker = date("H",strtotime($end_date));
        $minutesTemp = 0;


        if($start_checker != $end_checker) return "not ok";

        if($fhour_checker <= 7 && $lhour_checker >= 8){
            $fhour_checker = 8;
            $start_date = $start_checker.' '.'08:00:00';
        }
        if($fhour_checker == 11){
            $start_date = new DateTime($start_date);
            $end_date = $start_date->diff(new DateTime($start_checker." 12:00:00"));

            $minutes = $end_date->days * 24 * 60;
            $minutes += $end_date->h * 60;
            $minutes += $end_date->i;

            $start_date = $start_checker.' '.'13:00:00';
            $minutesTemp = $minutes;
            $end_date = "2018-11-14 13:30:00";
        }
        elseif($fhour_checker == 12 && $lhour_checker >= 13){
            $fhour_checker = 13;
            $start_date = $start_checker.' '.'13:00:00';
        }

        if(
            ($fhour_checker >= 8 && $fhour_checker < 12)
            || ($fhour_checker >= 13 && $fhour_checker < 17)

            && ($lhour_checker >= 8 && $lhour_checker < 12)
            || ($lhour_checker >= 13 && $lhour_checker < 17)
        )
        {
            $start_date = new DateTime($start_date);
            $end_date = $start_date->diff(new DateTime($end_date));

            $minutes = $end_date->days * 24 * 60;
            $minutes += $end_date->h * 60;
            $minutes += $end_date->i;

            if($minutesTemp){
                $minutes += $minutesTemp;
            }
            return $minutes;
        }
        return "not ok";

    }

}
?>