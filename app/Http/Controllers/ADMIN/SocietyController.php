<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Society;
use App\Traits\GLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class SocietyController extends Controller
{
    //

    use GLocation;
    public function fetch_societys(Request $request)
    {

        $d['page_title'] = 'Societies';
        $page_limit = 10;

        if (isset($_GET['get_export_data'])) {
            // print_r($_GET); die;
             $data[] = array("Society","location","Pincode","Status","Position","Added");

               
             if(isset($_GET['q']) && !empty($_GET['q'])){
                    $q = trim($_GET['q']);
                    $fetch = Society::where('status','!=',2)
                    ->where(function($query)  use ($q) {
                        $query->where('name','LIKE', '%' . $q . '%')
                        ->orWhere('pincode','LIKE', '%' . ($q) . '%');
                        // ->orWhere('society','LIKE', '%' . ($q) . '%');
                    })
                    ->orderBy('id','desc')
                    ->paginate();
                    $fetch->appends (array ('q' => $q));
                }else{
                    $fetch = Society::where('status','!=',2)
                    ->orderBy('id','desc')
                    ->paginate();
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
                      
                   "name"=>$user->name,
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
                header("Content-Disposition: attachment; filename=\"fetch_societys" . $string_file . ".csv");
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
            $fetch = Society::where('status','!=',2)
            ->where(function($query)  use ($q) {
                $query->where('name','LIKE', '%' . $q . '%')
                ->orWhere('pincode','LIKE', '%' . ($q) . '%');
                // ->orWhere('society','LIKE', '%' . ($q) . '%');
            })
            ->orderBy('id','desc')
            ->paginate($page_limit);
            $fetch->appends (array ('q' => $q));
        }else{
            $fetch = Society::where('status','!=',2)
            ->orderBy('id','desc')
            ->paginate($page_limit);
        }
        $d['fetchdata'] = $fetch;
        return view('admin.society.societys',$d);
    }

    public function save_society(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'location_id'=>'required',
            'location'=>'required',
            'lat'=>'required',
            'lon'=>'required'
        ]);
        $input = $request->all();
        $lat=trim($input['lat']);
        $lon=trim($input['lon']);
        $input['name'] = $input['location'];
        $lc=$this->get_pincode_and_address($lat,$lon);
        if(!empty($lc)){
            $input['pincode']=$lc['pincode'];
        }

        $create = Society::create($input);
        if($create->id){
            return Redirect::route('update_society',['id'=>$create->id])->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'society not saved! Something went wrong! Please try again!');
    }

    public function edit_society(Request $request,$id)
    {
        $request->validate([
            'name'=>'required',
            'location_id'=>'required',
            'location'=>'required',
            'lat'=>'required',
            'lon'=>'required'
        ]);

        $input = $request->all();
        $lat=trim($input['lat']);
        $lon=trim($input['lon']);
        $lc=$this->get_pincode_and_address($lat,$lon);
        if(!empty($lc)){
            $input['pincode']=$lc['pincode'];
        }

        unset($input['_token']);
        $input['name'] = $input['location'];

        $create = Society::where('id',$id)->update($input);
        if($create){
            return redirect()->back()->with('success', 'Successfully Saved!');

        }
        return redirect()->back()->with('danger', 'society not saved! Something went wrong! Please try again!');

    }

    public function load_society_data($id)
    {
        $data['society']= Society::where('id',$id)->first();
        return View::make('admin.society.society-form',$data)->render();
    }

    public function delete_society($id)
    {
        Society::where('id',$id)->update(['status'=>2]);
        return redirect()->back()->with('warning','Deleted successfully!');
    }

    public function add_society()
    {
        $template['page_title'] = 'Add society';
        $template['locations']=Location::active()->orderBy('location','ASC')->get();

        $breadcrumb = [
            0=>[ 'title'=>'Societys',
             'link'=>route('fetch_societys')]
         ];
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.society.addedit',$template);
    }

    public function update_society($id)
    {
        $template['page_title'] = 'Edit Society';
        $template['locations']=Location::active()->orderBy('location','ASC')->get();
        $breadcrumb = [
            0=>[ 'title'=>'societys',
             'link'=>route('fetch_societys')]
        ];
        $template['society']= Society::where('id',$id)->first();
        $template['breadcrumb'] = $breadcrumb;
        return view('admin.society.addedit',$template);
    }


}
