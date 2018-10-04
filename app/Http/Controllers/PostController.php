<?php

namespace App\Http\Controllers;

use File;
use App\Authorizable;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Common\FileUploadComponent;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Post::where('is_delete','1')
            ->orderby('id','desc')
            ->with('user')
            ->paginate();
            // dd($result);
            // exit;
        return view('post.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    
    public function store(Request $request)
    {
        $todate = date("Y-m-d-H-i-s");
        $this->validate($request, [
            'title' => 'required|min:10',
            'body' => 'required|min:20',
            'image_name' => 'required',
        ]);
        
        $file_name = $request->file('image_name');
        $name = time().'.'.$file_name->getClientOriginalExtension();
        //file uploads
        
        $post_data = [
            'title'=>$request->title,
            'body'=>$request->body,
            'image_name'=>$name,
        ];
        
        $post = $request->user()->posts()->create($post_data);
        if(!empty($post)){
            $artical = Post::findOrFail($post->id);
            $file = $request->file('image_name');
            //Local File Upload
            $url_local = 'storage/posts/';
            $name = time().'.'.$file->getClientOriginalExtension();
            $filePath = 'posts/'.$post->id .'/'.$name;
            $path_local = $url_local.$filePath;

            // AWS File Upload
            // $url = 'https://'.env('AWS_BUCKET').'.s3.' .env('AWS_REGION'). '.amazonaws.com/';
            // $uploadPath = 'development/laravel/posts/'.$post->id .'/'.$name;
            // $path = $url.$uploadPath;

            Storage::disk('public')->put($filePath, file_get_contents($file));
             // Storage::disk('s3')->put($uploadPath.$name, file_get_contents($file),'public');
            Post::where('id', $post->id)->update([
                  'image_path'=>$path_local,
                ]);
         }
        flash('Post has been added');
        return redirect()->back();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function print(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $post = Post::findOrFail($post->id);

        return view('post.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $todate = date("Y-m-d-H-i-s");
        $this->validate($request, [
            'title' => 'required|min:10',
            'body' => 'required|min:20'
        ]);

        $me = $request->user();
        if( $me->hasRole('Admin') ) {
            $post = Post::findOrFail($post->id);
        } else {
            $post = $me->posts()->findOrFail($post->id);
        }

        $check_has = $request->hasFile('image_name');
        $file = $request->file('image_name');
        if(!empty($file)){
            $name = time().'.'.$file->getClientOriginalExtension();
            //Local File Upload
            $url_local = 'storage/posts/';
            $filePath = 'posts/'.$post->id .'/'.$name;
            $path_local = $url_local.$filePath;
            // AWS file upload
            // $url = 'https://'.env('AWS_BUCKET').'.s3.' .env('AWS_REGION'). '.amazonaws.com/';
            // $uploadPath = 'development/laravel/posts/'.$post->id .'/'.$name;
            // $path = $url.$uploadPath;
            Storage::disk('public')->put($filePath, file_get_contents($file),'public');
            // Storage::disk('s3')->put($uploadPath.$name, file_get_contents($file),'public');
        }else{
            $name = $post->image_name;
            $path = $post->upload_path;
        }
        $post_data = [
            'title'=>$request->title,
            'body'=>$request->body,
            'image_name'=>$name,
            'upload_path' => $path
        ];

        $post->update($post_data);
        flash()->success('Post has been updated.');
        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $me = Auth::user();
        if( $me->hasRole('Admin') ) {
            $post = Post::findOrFail($post->id);
        } else {
            $post = $me->posts()->findOrFail($post->id);
        }
        Post::where('id', $post->id)
                ->update([
                    'is_delete'=>'0',
                    'delete_by'=>$post->user_id,
                ]);
        // $post->delete();

        flash()->success('Post has been deleted.');

        return redirect()->route('posts.index');
    }

    public function isactive($user_id, $value){
        $me = Auth::user();
        if( $me->hasRole('Admin') ) {
            $post = Post::findOrFail($post->id);
        } else {
            $post = $me->posts()->findOrFail($post->id);
        }
        
        flash()->success('Post has been deleted.');
        return redirect()->route('posts.index');
    }

    public function csvupload(){
        return view('post.importcsv');
    }

    /**
     * Import xlx, csv file .
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request){
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){
 
                    foreach ($data as $key => $value) {
                        $insert[] = [
                        'id' => $value->id,
                        'title' => $value->title,
                        'body' => $value->body,
                        ];
                    }
 
                    if(!empty($insert)){
 
                        $insertData = DB::table('posts')->insert($insert);
                        if ($insertData) {
                            Session::flash('success', 'Your Data has successfully imported');
                        }else {                        
                            Session::flash('error', 'Error inserting the data..');
                            return back();
                        }
                    }
                }
                return back();
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }
}
