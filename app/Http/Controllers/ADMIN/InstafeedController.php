<?php

namespace App\Http\Controllers\ADMIN;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstaFeeds;
use Illuminate\Support\Facades\Redirect;
class InstafeedController extends Controller
{
    
    public function index_instafeed()
    { 
         $template['page_title'] = 'Add InstaFeed';
        $breadcrumb = [
            0=>[ 'title'=>'InstaFeeds',
             'link'=>route('show_instafeed') ]
         ];
         $template['breadcrumb'] = $breadcrumb;
    
        return view("admin.insta.add",$template);
    }

    public function store_instafeed(Request $request)
    {   
        
        $post= new InstaFeeds;
        $post->title=$request->get('title');
        $post->description=$request->get('description');
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/insta'), $imageName);
        $post->image = $imageName;
        $post->hyperlink=$request->get('hyperlink');
        $post->status=$request->get('status');
        $post->position=$request->get('position');
       
        $post->save();

        return Redirect::route('show_instafeed')->with('success','Created Successfully');
    }
   
    public function show_instafeed(InstaFeeds $post)
    {
        $post=InstaFeeds::all();
        return view('admin.insta.insta',['post'=>$post,'page_title'=>'Instafeeds']);
    }
    public function edit_instafeed(InstaFeeds $post,$id)
    {  
        $post=InstaFeeds::find($id);
        return view('admin.insta.edit',['post'=>$post,'page_title'=>'Edit Instafeeds']);
    }

    
    public function update_instafeed(Request $request, InstaFeeds $post,$id)
    {
        $post=InstaFeeds::find($id);
        $post->title=$request->get('title');
        $post->description=$request->get('description');
        if($_FILES['image']['size']>0){
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/insta'), $imageName);
        $post->image = $imageName;
        }
        $post->hyperlink=$request->get('hyperlink');
        $post->status=$request->get('status');
        $post->position=$request->get('position');

        $post->save();
        return Redirect::route('show_instafeed')->with('success','Updated Successfully');

    }

    public function destroy_instafeed(InstaFeeds $post,$id)
    {
        $post=InstaFeeds::find($id);
        $post->delete();
        return Redirect::route('show_instafeed')->with('success','Deleted Successfully');

    }
   
}
