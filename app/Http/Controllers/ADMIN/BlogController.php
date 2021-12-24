<?php

namespace App\Http\Controllers\ADMIN;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Redirect;
class BlogController extends Controller
{
    
    public function index()
    { $cat=Category::all();
        return view("admin.blog.add",['cat'=>$cat]);
    }

    public function store(Request $request)
    {
        $post=new Blog;
        $post->title=$request->get('title');
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/blogs'), $imageName);
        $post->image = $imageName;
        $post->description=$request->get('description');
        $post->catagory_id=$request->get('catagory_id');
        $post->status=$request->get('status');
       
        $post->save();

        return Redirect::route('show')->with('success','created successfully');
    }

    
    public function show(Blog $post)
    {
        $post=Blog::all();
        return view('admin.blog.blog',['post'=>$post]);
    }

    
    public function edit(Blog $post,$id)
    {
        $post=Blog::find($id);
        return view('admin.blog.edit',['post'=>$post]);
    }

    
    public function update(Request $request, Blog $post,$id)
    {
        $post=Blog::find($id);
        $post->title=$request->get('title');
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/blogs'), $imageName);
        $post->image = $imageName;
        $post->description=$request->get('description');
        $post->difficulty=$request->get('catagory_id');
        $post->status=$request->get('status');
        $post->save();
        return Redirect::route('show')->with('success','Updated Successfully');

    }

    
    public function destroy(Blog $post,$id)
    {
        $post=Blog::find($id);
        $post->delete();
        return Redirect::route('show')->with('success','Deleted Successfully');

    }
}
