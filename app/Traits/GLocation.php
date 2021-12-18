<?php
namespace App\Traits;


trait GLocation{

    public function get_pincode_and_address($lat,$lon)
    {
        $location=rz_get_location_data_by_lat_long($lat,$lon);
        $results=$location['results'][0]['address_components'];
        $count_result=count($results);
        $pincode=$results[$count_result-1]['long_name'];
        return ['pincode'=>$pincode,
        'address'=>$location['results'][0]['formatted_address'],
    ];
    }
}
