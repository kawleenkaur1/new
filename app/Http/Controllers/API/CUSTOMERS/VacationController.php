<?php

namespace App\Http\Controllers\API\CUSTOMERS;

use App\Http\Controllers\Controller;
use App\Models\OrderHistory;
use App\Models\Vacation;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class VacationController extends Controller
{

    public function add_vacations(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'start_date'=>'required',
            'end_date'=>'required'
        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $time=strtotime(date('Y-m-d'));


        $user_id=rz_user_id($input);
        $check=Vacation::where('user_id',$user_id)->first();
        $no_of_deliveries_done_arr=[];
        $start_date_bool=false;
        $arr=[
            'user_id'=>$user_id,
            'start_date'=>date('Y-m-d H:i:s',strtotime($input['start_date'])),
            'end_date'=>date('Y-m-d H:i:s',strtotime($input['end_date']))
        ];

        $create=Vacation::create($arr);
        if($create){

            /**user vacation start date and end date */
            $start_date=date('Y-m-d',strtotime($input['start_date']));
            $end_date=date('Y-m-d',strtotime($input['end_date']));

            /**get array of days between start day and end day */
            $number_of_days_between=$this->getDatesFromRange($start_date,$end_date);
            if(count($number_of_days_between)){
                $vacationsdates_arr=$number_of_days_between;
            }else{
                $vacationsdates_arr=[$start_date];
            }

            /**fetch all subscriptions */
            $subscriptions=OrderHistory::subscriptions()->where('user_id',$user_id)->whereIn('status',[0,1])->get();

            if(count($subscriptions)){

                foreach($subscriptions as $s){

                    /**skip days for subscriptions like: alternate days,month wise subscription */
                    $skip_days=$s->skip_days;

                    /**total deliveries */
                    $deliveries=$s->deliveries;

                    /**subscription start date */
                    $subscrip_start_date=$s->start_date;

                    /**subscription end date */
                    $subscrip_end_date=$s->end_date;


                    $sdate = strtotime($start_date); // or your date as well
                    $edate = strtotime($end_date);

                    if($time < strtotime($subscrip_end_date)){
                        $skip_days=$s->skip_days;
                        $deliveries=$s->deliveries;
                        if($skip_days != 0){
                            $additional_days=round($deliveries/$skip_days);
                        }else{
                            $additional_days=0;
                        }
                        $total_days=$additional_days+$deliveries-1;
                        if($total_days<0){
                            $total_days=0;
                        }
                        $end_date=date('Y-m-d H:i:s', strtotime($s->start_date. ' + '.intval($total_days).' days'));

                        $subscription_start_end_dates=$this->getDatesFromRange($subscrip_start_date,$end_date);
                        $counter=0;
                        $iterations=[];

                        foreach($vacationsdates_arr as $key=>$vc){
                            if (in_array($vc, $subscription_start_end_dates)){
                                $counter += 1;
                                $iterations[]=$key;
                            }
                        }
                        if($iter_count=count($iterations)){
                            $last_itr=$iterations[$iter_count-1];
                            if(count($vacationsdates_arr) > $last_itr){
                                foreach($vacationsdates_arr as $key=>$vc){
                                    if($key>$last_itr){
                                        $counter += 1;
                                    }
                                }
                            }
                        }

                        $end_date=date('Y-m-d', strtotime($end_date. ' + '.($counter).' days'));

                        /**skip non skip days */
                        $dates_arr2=rz_getDatesFromRange($s->start_date,$end_date);
                        if($skip_days != 0){
                            $skip_dates_arr=[];
                            $non_skip_dates_arr=[];
                            $i = 0;
                            foreach($dates_arr2 as $value) {
                                if ($i++ % ($skip_days+1) == 0) {
                                    $non_skip_dates_arr[] = $value;
                                }else{
                                    $skip_dates_arr[]=$value;
                                }
                            }
                            $s->skip_dates=$skip_dates=implode('|',$skip_dates_arr);
                            $non_skip_dates=implode('|',$non_skip_dates_arr);

                        }else{
                            $non_skip_dates_arr=$dates_arr2;
                            $non_skip_dates=implode('|',$dates_arr2);
                        }

                        $filter_non_skip_dates=array_diff($non_skip_dates_arr,$vacationsdates_arr);
                        if(!empty($filter_non_skip_dates)){
                            $new_non_skip=array_slice($filter_non_skip_dates, 0, $deliveries);
                        }else{
                            $new_non_skip=[];
                        }


                        $s->non_skip_dates=implode('|',$new_non_skip);

                        $s->vacations=implode('|',$vacationsdates_arr);
                        $s->end_date=$end_date;

                        if($check){
                            if(!(($time>strtotime($check->start_date) && $time < strtotime($check->end_date)) || ($time>=strtotime($check->end_date)))){
                                $s->save();
                            }
                        }else{
                            $s->save();
                        }




                    }


                }
            }
            Vacation::whereNotIn('id',[$create->id])->where('user_id',$user_id)->delete();
            return yt_api_response([
                'status'=>true,
                'message'=>'Added ;)',
                'data'=>$create
            ]);
        }else{
            return yt_api_response([
                'status'=>false,
                'message'=>'something went wrong ;(',
                'data'=>$create
            ]);
        }
    }


    // public function add_vacations(Request $request)
    // {
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'start_date'=>'required',
    //         'end_date'=>'required'
    //     ]);
    //     if ($validator->fails()) {
    //         $message = yt_validator_error_messages($validator);
    //         return yt_api_response(['status' => false,'message'=>$message]);
    //     }
    //     $time=strtotime(date('Y-m-d'));


    //     $user_id=rz_user_id($input);
    //     $no_of_deliveries_done_arr=[];
    //     $start_date_bool=false;
    //     $arr=[
    //         'user_id'=>$user_id,
    //         'start_date'=>date('Y-m-d H:i:s',strtotime($input['start_date'])),
    //         'end_date'=>date('Y-m-d H:i:s',strtotime($input['end_date']))
    //     ];

    //     $create=Vacation::create($arr);
    //     if($create){

    //         /**user vacation start date and end date */
    //         $start_date=date('Y-m-d',strtotime($input['start_date']));
    //         $end_date=date('Y-m-d',strtotime($input['end_date']));

    //         /**get array of days between start day and end day */
    //         $number_of_days_between=$this->getDatesFromRange($start_date,$end_date);
    //         if(count($number_of_days_between)){
    //             $vacationsdates_arr=$number_of_days_between;
    //         }else{
    //             $vacationsdates_arr=[$start_date];
    //         }

    //         /**fetch all subscriptions */
    //         $subscriptions=OrderHistory::subscriptions()->where('user_id',$user_id)->whereIn('status',[0,1])->get();

    //         if(count($subscriptions)){

    //             foreach($subscriptions as $s){

    //                 /**skip days for subscriptions like: alternate days,month wise subscription */
    //                 $skip_days=$s->skip_days;

    //                 /**total deliveries */
    //                 $deliveries=$s->deliveries;

    //                 /**subscription start date */
    //                 $subscrip_start_date=$s->start_date;

    //                 /**subscription end date */
    //                 $subscrip_end_date=$s->end_date;


    //                 $sdate = strtotime($start_date); // or your date as well
    //                 $edate = strtotime($end_date);

    //                 if($sdate>strtotime($subscrip_start_date) && $sdate<strtotime($subscrip_end_date)){
    //                     /**count deliveries done days */
    //                     $dates_arr=$this->getDatesFromRange($subscrip_start_date,$sdate);
    //                     $count_dates_arr=count($dates_arr);
    //                     for ($i = 0; $i < $count_dates_arr; ($i++)+$skip_days) {
    //                         $no_of_deliveries_done_arr[]=$dates_arr[$i];
    //                         // $i++;
    //                     }
    //                     // if(!count($no_of_deliveries_done_arr)){
    //                     //     $s->start_date=date($start_date_bool. ' + '.intval($total_days).' days'));
    //                     // }
    //                 }elseif($edate==strtotime($subscrip_end_date)){
    //                     $v_count=count($vacationsdates_arr);
    //                     $start_date=date('Y-m-d', strtotime($vacationsdates_arr[$v_count-1]. ' + 1 days'));
    //                     $s->start_date=$start_date_bool=$start_date;
    //                 }
    //                 $more_days=count($vacationsdates_arr);
    //                 $deliveries_done=count($no_of_deliveries_done_arr);
    //                 $deliveries_left=$deliveries-$deliveries_done;

    //                 if($skip_days != 0){
    //                     $additional_days=round($deliveries_left/$skip_days);
    //                 }else{
    //                     $additional_days=0;
    //                 }
    //                 $total_days=$additional_days+$deliveries_left+$more_days;
    //                 if(($start_date_bool)){
    //                     $total_days-=1;
    //                     $end_date=date('Y-m-d', strtotime($start_date_bool. ' + '.intval($total_days).' days'));
    //                 }else{
    //                     $end_date=date('Y-m-d', strtotime("+".intval($total_days)." days"));
    //                 }
    //                 // $end_date=date('Y-m-d H:i:s', strtotime(date('Y-m-d'). ' + '.intval($total_days).' days'));
    //                 // $end_date=date('Y-m-d H:i:s', strtotime("+".intval($total_days)." days"));

                    // $s->vacations=implode('|',$vacationsdates_arr);
                    // $s->end_date=$end_date;
                    // $s->save();



    //                 /**check if vacation start date is less than subscription end date */
    //                 if($time < strtotime($s->end_date)){

    //                     /**if current date is less than subscription start date */
    //                     if($time < strtotime($subscrip_start_date)){

    //                         /**if vacation date is greater than subscription start date */
    //                         if($sdate>strtotime($subscrip_start_date) && $sdate<strtotime($subscrip_end_date)){
    //                             /**count deliveries done days */
    //                             $dates_arr=$this->getDatesFromRange($sdate,$subscrip_start_date);
    //                             $count_dates_arr=count($dates_arr);
    //                             for ($i = 0; $i < $count_dates_arr; ($i++)+$skip_days) {
    //                                 $no_of_deliveries_done_arr[]=$dates_arr[$i];
    //                                 // $i++;
    //                             }
    //                         }elseif($sdate<strtotime($subscrip_start_date) && $edate ){
    //                             $v_count=count($vacationsdates_arr);
    //                             $start_date=date('Y-m-d', strtotime($vacationsdates_arr[$v_count-1]. ' + 1 days'));
    //                             $s->start_date=$start_date_bool=$start_date;
    //                             // $more_days+=$v_count;
    //                             // date('Y-m-d', strtotime("+30 days"))
    //                         }

    //                     }

    //                     /**if user vacation in between subscription start date */
    //                     if($sdate>strtotime($subscrip_start_date)){


    //                         $dates_arr=$this->getDatesFromRange($subscrip_start_date,$start_date);
    //                         $count_dates_arr=count($dates_arr);
    //                         for ($i = 0; $i < $count_dates_arr; ($i++)+$skip_days) {
    //                             $no_of_deliveries_done_arr[]=$dates_arr[$i];
    //                             // $i++;
    //                         }

    //                     }
    //                     elseif($sdate<strtotime($subscrip_start_date)){
    //                         $v_count=count($vacationsdates_arr);
    //                         $start_date=date('Y-m-d', strtotime($vacationsdates_arr[$v_count-1]. ' + 1 days'));
    //                         $s->start_date=$start_date_bool=$start_date;
    //                         // $more_days+=$v_count;
    //                         // date('Y-m-d', strtotime("+30 days"))
    //                     }

    //                     $deliveries_done=count($no_of_deliveries_done_arr);
    //                     $deliveries_left=$deliveries-$deliveries_done;

    //                     if($skip_days != 0){
    //                         $additional_days=round($deliveries_left/$skip_days);
    //                     }else{
    //                         $additional_days=0;
    //                     }
    //                     $total_days=$additional_days+$deliveries_left+$more_days;
    //                     if(($start_date_bool)){
    //                         $total_days-=1;
    //                         $end_date=date('Y-m-d', strtotime($start_date_bool. ' + '.intval($total_days).' days'));
    //                     }else{
    //                         $end_date=date('Y-m-d', strtotime("+".intval($total_days)." days"));
    //                     }
    //                     // $end_date=date('Y-m-d H:i:s', strtotime(date('Y-m-d'). ' + '.intval($total_days).' days'));
    //                     // $end_date=date('Y-m-d H:i:s', strtotime("+".intval($total_days)." days"));

    //                     $s->vacations=implode('|',$vacationsdates_arr);
    //                     $s->end_date=$end_date;
    //                     $s->save();
    //                 }

    //             }
    //         }
    //         Vacation::whereNotIn('id',[$create->id])->where('user_id',$user_id)->delete();
    //         return yt_api_response([
    //             'status'=>true,
    //             'message'=>'Added ;)',
    //             'data'=>$create
    //         ]);
    //     }else{
    //         return yt_api_response([
    //             'status'=>false,
    //             'message'=>'something went wrong ;(',
    //             'data'=>$create
    //         ]);
    //     }
    // }



    // Function to get all the dates in given range
    function getDatesFromRange($start, $end, $format = 'Y-m-d') {

        // Declare an empty array
        $array = array();

        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        // Use loop to store date into array
        foreach($period as $date) {
            $array[] = $date->format($format);
        }

        // Return the array elements
        return $array;
    }


    public function get_number_of_days_between_dates($date1,$date2)
    {
        $now = strtotime($date1); // or your date as well
        $your_date = strtotime($date2);
        $datediff = $now - $your_date;

        return round($datediff / (60 * 60 * 24));
    }

    // public function add_vacations(Request $request)
    // {
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'start_date'=>'required',
    //         'end_date'=>'required'
    //     ]);
    //     if ($validator->fails()) {
    //         $message = yt_validator_error_messages($validator);
    //         return yt_api_response(['status' => false,'message'=>$message]);
    //     }
    //     $time=time();
    //     $user_id=rz_user_id($input);
    //     $arr=[
    //         'user_id'=>$user_id,
    //         'start_date'=>date('Y-m-d H:i:s',strtotime($input['start_date'])),
    //         'end_date'=>date('Y-m-d H:i:s',strtotime($input['end_date']))
    //     ];
    //     $check=Vacation::where('user_id',$user_id)->first();
    //     if($check){
    //         $last_set_start_date=strtotime($check->start_date);
    //         $last_set_end_date=strtotime($check->end_date);
    //         $check->start_date=$start_date=date('Y-m-d H:i:s',strtotime($input['start_date']));
    //         $check->end_date=$end_date=date('Y-m-d H:i:s',strtotime($input['end_date']));
    //         $check->save();

    //         $subscriptions=OrderHistory::subscriptions()->where('user_id',$user_id)->whereIn('status',[0,1])->get();
    //         if(count($subscriptions)){
    //             foreach($subscriptions as $s){
    //                 if($time >= strtotime($s->start_date) && $time < strtotime($s->end_date)){
    //                     // $total_deliveries=$s->deliveries;
    //                     // $deliveries_done=$s->deliveries_done;
    //                     $more_days=0;
    //                     $sdate = strtotime($start_date); // or your date as well
    //                     $edate = strtotime($end_date);
    //                     // if($time < $sdate){
    //                         // if($sdate >= $last_set_start_date && $sdate <= $last_set_end_date){
    //                         //     $datediff1 = $sdate - $last_set_end_date;
    //                         //     $more_days+= round($datediff1 / (60 * 60 * 24));
    //                         //     if($edate > $last_set_end_date){
    //                         //         $datediff2 = $edate - $last_set_end_date;
    //                         //         $more_days+= round($datediff2 / (60 * 60 * 24));
    //                         //     }
    //                         // }


    //                         $datediff = $sdate - $edate;
    //                         $more_days+= round($datediff / (60 * 60 * 24));
    //                         $skip_days=$s->skip_days;
    //                         $deliveries=$s->deliveries;
    //                         if($skip_days != 0){
    //                             $additional_days=round($deliveries/$skip_days);
    //                         }else{
    //                             $additional_days=0;
    //                         }
    //                         $total_days=$additional_days+$deliveries+$more_days;
    //                         $end_date=date('Y-m-d H:i:s', strtotime($s->start_date. ' + '.intval($total_days).' days'));
    //                         $s->end_date=$end_date;
    //                         $s->save();
    //                     // }
    //                 }

    //             }
    //         }
    //         return yt_api_response([
    //             'status'=>true,
    //             'message'=>'Added ;)',
    //             'data'=>$check
    //         ]);
    //     }else{
    //         $create=Vacation::create($arr);
    //         $start_date=date('Y-m-d H:i:s',strtotime($input['start_date']));
    //         $end_date=date('Y-m-d H:i:s',strtotime($input['end_date']));

    //         $subscriptions=OrderHistory::subscriptions()->where('user_id',$user_id)->whereIn('status',[0,1])->get();
    //         if(count($subscriptions)){
    //             foreach($subscriptions as $s){
    //                 if($time >= strtotime($s->start_date) && $time < strtotime($s->end_date)){
    //                     // $total_deliveries=$s->deliveries;
    //                     // $deliveries_done=$s->deliveries_done;

    //                     $sdate = strtotime($start_date); // or your date as well
    //                     $edate = strtotime($end_date);
    //                     $datediff = $sdate - $edate;

    //                     $more_days= round($datediff / (60 * 60 * 24));

    //                     $skip_days=$s->skip_days;
    //                     $deliveries=$s->deliveries;
    //                     if($skip_days != 0){
    //                         $additional_days=round($deliveries/$skip_days);
    //                     }else{
    //                         $additional_days=0;
    //                     }
    //                     $total_days=$additional_days+$deliveries+$more_days;
    //                     $end_date=date('Y-m-d H:i:s', strtotime($s->start_date. ' + '.intval($total_days).' days'));
    //                     $s->end_date=$end_date;
    //                     $s->save();
    //                 }

    //             }
    //         }
    //         return yt_api_response([
    //             'status'=>true,
    //             'message'=>'Added ;)',
    //             'data'=>$create
    //         ]);
    //     }
    // }


    public function get_vacation(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [

        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id=rz_user_id($input);

        $check=Vacation::where('user_id',$user_id)->first();
        return yt_api_response([
            'status'=>true,
            'message'=>'Added ;)',
            'data'=>$check
        ]);
    }


    public function remove_vacations(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [

        ]);
        if ($validator->fails()) {
            $message = yt_validator_error_messages($validator);
            return yt_api_response(['status' => false,'message'=>$message]);
        }
        $user_id=rz_user_id($input);
        $check=Vacation::where('user_id',$user_id)->first();
        $time=strtotime(date('Y-m-d'));
        // $start_date=date('Y-m-d H:i:s',strtotime($check->start_date));
        // $end_date=date('Y-m-d H:i:s',strtotime($check->end_date));


        /**fetch all subscriptions */
        $subscriptions=OrderHistory::subscriptions()->where('user_id',$user_id)->whereIn('status',[0,1])->get();
        $vacationsdates_arr=[];

        if(count($subscriptions)){

            foreach($subscriptions as $s){

                /**skip days for subscriptions like: alternate days,month wise subscription */
                $skip_days=$s->skip_days;

                /**total deliveries */
                $deliveries=$s->deliveries;

                /**subscription start date */
                $subscrip_start_date=$s->start_date;

                /**subscription end date */
                $subscrip_end_date=$s->end_date;


                $sdate = strtotime($check->start_date); // or your date as well
                $edate = strtotime($check->end_date);

                if($time < strtotime($subscrip_end_date)){
                    $skip_days=$s->skip_days;
                    $deliveries=$s->deliveries;
                    if($skip_days != 0){
                        $additional_days=round($deliveries/$skip_days);
                    }else{
                        $additional_days=0;
                    }
                    $total_days=$additional_days+$deliveries-1;
                    if($total_days<0){
                        $total_days=0;
                    }
                    $end_date=date('Y-m-d H:i:s', strtotime($s->start_date. ' + '.intval($total_days).' days'));

                    $subscription_start_end_dates=$this->getDatesFromRange($subscrip_start_date,$end_date);
                    $counter=0;
                    $iterations=[];

                    foreach($vacationsdates_arr as $key=>$vc){
                        if (in_array($vc, $subscription_start_end_dates)){
                            $counter += 1;
                            $iterations[]=$key;
                        }
                    }
                    if($iter_count=count($iterations)){
                        $last_itr=$iterations[$iter_count-1];
                        if(count($vacationsdates_arr) > $last_itr){
                            foreach($vacationsdates_arr as $key=>$vc){
                                if($key>$last_itr){
                                    $counter += 1;
                                }
                            }
                        }
                    }

                    $end_date=date('Y-m-d', strtotime($end_date. ' + '.($counter).' days'));

                    $dates_arr2=rz_getDatesFromRange($s->start_date,$end_date);
                    if($skip_days != 0){
                        $skip_dates_arr=[];
                        $non_skip_dates_arr=[];
                        $i = 0;
                        foreach($dates_arr2 as $value) {
                            if ($i++ % ($skip_days+1) == 0) {
                                $non_skip_dates_arr[] = $value;
                            }else{
                                $skip_dates_arr[]=$value;
                            }
                        }
                        $s->skip_dates=$skip_dates=implode('|',$skip_dates_arr);
                        $non_skip_dates=implode('|',$non_skip_dates_arr);

                    }else{
                        $non_skip_dates_arr=$dates_arr2;
                        $non_skip_dates=implode('|',$dates_arr2);
                    }

                    $filter_non_skip_dates=array_diff($non_skip_dates_arr,$vacationsdates_arr);
                    if(!empty($filter_non_skip_dates)){
                        $new_non_skip=array_slice($filter_non_skip_dates, 0, $deliveries);
                    }else{
                        $new_non_skip=[];
                    }


                    $s->non_skip_dates=implode('|',$new_non_skip);

                    $s->vacations=implode('|',$vacationsdates_arr);
                    $s->end_date=$end_date;
                    // $s->save();
                    if($check){
                        if(!(($time>strtotime($check->start_date) && $time < strtotime($check->end_date)) || ($time>=strtotime($check->end_date)))){
                            $s->save();
                        }
                    }else{
                        $s->save();
                    }
                }




            }
        }
        $delete=Vacation::where('user_id',$user_id)->delete();
        return yt_api_response([
            'status'=>true,
            'message'=>'removed ;)',
            'data'=>$delete
        ]);
    }
}
