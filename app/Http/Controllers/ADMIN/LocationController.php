<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Traits\GLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class LocationController extends Controller
{
    //

    use GLocation;
    public function fetch_locations(Request $request)
    {

        $d['page_title'] = 'cities & Zones';
        $page_limit = 10;


        if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("Location","Pincode","Status","Position","Added");

               
                if(isset($_GET['q']) && !empty($_GET['q'])){
                    $q = trim($_GET['q']);
                    $fetch = Location::where('status','!=',2)
                    ->where(function($query)  use ($q) {
                        $query->where('name','LIKE', '%' . $q . '%')
                        ->orWhere('pincode','LIKE', '%' . ($q) . '%')
                        ->orWhere('location','LIKE', '%' . ($q) . '%');
                    })
                    ->orderBy('id','desc')
                    ->paginate();
                    $fetch->appends (array ('q' => $q));
                }else{
                    $fetch = Location::where('status','!=',2)->orderBy('id','desc')->paginate();
                }
           
                $i = 1;
                foreach ($fetch as $user) {
                    $st="";
                       if ($user->status == 1)
                       {
                        $st ="Active";
                        }
                         else
                       {
                            if ($user->status == 2) {
                                $st = "Deleted";
                            }
                            else{
                                $st = "Disable";
                            }
                       
                        }
                       
                    $data[] = array(
                      
                   "location"=>$user->location,
                   "pincode"=>$user->pincode,
                   "status"=>$st,
                   "position"=>$user->position,
                         
             
                   "added"=>date('d M y g:i A',strtotime($user->created_at)),
                  
                    );
                    $i++;
                }
            
                    $string_file = date("d-m-Y h:i:s A");
               
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"fetch_locations" . $string_file . ".csv");
                header("Pragma: no-cache");
                header("Expires: 0");

                $handle = fopen('php://output', 'w');

                foreach ($data as $data) {
                    fputcsv($handle, $data);
                }
                fclose($handle);
                exit;
    }


        if(isset($_GET['q']) && !empty($_GET['q'])){
            $q = trim($_GET['q']);
            $fetch = Location::where('status','!=',2)
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('pincode','LIKE', '%' . ($q) . '%')
                ->orWhere('location','LIKE', '%' . ($q) . '%');
            })
            ->orderBy('id','desc')
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Location::where('status','!=',2)->orderBy('id','desc')->paginate($page_limit);
        }
        $d['fetchdata'] = $fetch;
        return view('admin.location.locations',$d);
    }

    public function save_location(Request $request)
    {
        $request->validate([
            'location'=>'required',
            //'pincode'=>'required',
            'lat'=>'required',
            'lon'=>'required'
        ]);
        $input = $request->all();
        $input['name']=$input['location'];
        $lat=trim($input['lat']);
        $lon=trim($input['lon']);
        $lc=$this->get_pincode_and_address($lat,$lon);
        if(!empty($lc)){
            $input['pincode']=$lc['pincode'];
        }
        $create = Location::create($input);
        if($create->id){
            return Redirect::route('update_location',['id'=>$create->id])->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Location not saved! Something went wrong! Please try again!');
    }

    public function edit_location(Request $request,$id)
    {
        $request->validate([
            'location'=>'required',
            'pincode'=>'required'
        ]);

        $input = $request->all();
        $lat=trim($input['lat']);
        $lon=trim($input['lon']);
        $input['name']=$input['location'];

        $lc=$this->get_pincode_and_address($lat,$lon);
        if(!empty($lc)){
            $input['pincode']=$lc['pincode'];
            // $input['location']=$lc['address'];
        }
        // $location['results'][0]['formatted_address'],
        unset($input['_token']);
        $create = Location::where('id',$id)->update($input);
        if($create){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'Location not saved! Something went wrong! Please try again!');

    }

    public function load_location_data($id)
    {
        $data['location']= Location::where('id',$id)->first();
        return View::make('admin.location.location-form',$data)->render();
    }

    public function delete_location($id)
    {
        Location::where('id',$id)->update(['status'=>2]);
        return redirect()->back()->with('warning','Deleted successfully!');
    }

    public function add_location()
    {
        $template['page_title'] = 'Add Zone';

        $breadcrumb = [
            0=>[ 'title'=>'Locations',
             'link'=>route('fetch_locations')]
         ];
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.location.addedit',$template);
    }

    public function update_location($id)
    {
        $template['page_title'] = 'Edit Zone';

        $breadcrumb = [
            0=>[ 'title'=>'Locations',
             'link'=>route('fetch_locations')]
        ];
        $template['location']= Location::where('id',$id)->first();
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.location.addedit',$template);
    }


}
