<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class CommonController extends Controller
{

    public function settings_page()
    {

        $app_settings = Setting::first();
        if($app_settings){
            $template['app_settings'] = $app_settings;
        }
        $template['page_title'] = 'Settings';
        return view('admin.settings.index',$template);
    }

    public function settings_update(Request $request)
    {
        $request->validate([
            'app_name' => 'required',
            'logo' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
            'favicon' => 'mimes:jpeg,png,jpg,gif,svg|max:8000',
            'support_email' => 'required',
            'support_phone'=>'required'
        ]);
        $input = $request->all();


        if($_FILES['logo']['size']>0){
            $logoName = time().'.'.$request->logo->extension();
            $request->logo->move(public_path('uploads/settings/'), $logoName);
            $input['logo'] = $logoName;
        }

        if($_FILES['favicon']['size']>0){
            $faviconName = time().'.'.$request->favicon->extension();
            $request->favicon->move(public_path('uploads/settings/'), $faviconName);
            $input['favicon'] = $faviconName;
        }
        if($_FILES['about_us_video']['size']>0){
            $videoName = time().'.'.$request->about_us_video->extension();
            $request->about_us_video->move(public_path('uploads/settings/'), $videoName);
            $input['about_us_video'] = public_path('uploads/settings/').$videoName;
        }

        $app_settings = Setting::first();
        if($app_settings){
            unset($input['_token']);
            $update = Setting::where('id',$app_settings->id)->update($input);
            if($update){
                return redirect()->back()->with('success', 'Successfully Saved!');

            }
            return redirect()->back()->with('danger', 'Settings not saved! Something went wrong! Please try again!');
        }else{
            $update = Setting::create($input);
            if($update){
                return redirect()->back()->with('success', 'Successfully Saved!');

            }
            return redirect()->back()->with('danger', 'Settings not saved! Something went wrong! Please try again!');
        }
    }

}
