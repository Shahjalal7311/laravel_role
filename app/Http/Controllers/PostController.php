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
        
        $save = $request->user()->posts()->create($post_data);
        if(!empty($save)){
            $artical = Post::findOrFail($save->id);
             $file = $request->file('image_name');
             $url = 'storage/posts/';
             //https://s3.' .'ap-northeast-1'. '.amazonaws.com/' .'rv-inspect' . '/';
             // $name = time() . $file->getClientOriginalName();
             
             $filePath = 'posts/'.$save->id .'/'. $name;
             $path = $url.$filePath;
             Storage::disk('public')->put($filePath, file_get_contents($file));
             Post::where('id', $save->id)->update([
                  'image_path'=>$path,
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
            'body' => 'required|min:20',
            'image_name' => 'required',
        ]);

        $me = $request->user();
        if( $me->hasRole('Admin') ) {
            $post = Post::findOrFail($post->id);
        } else {
            $post = $me->posts()->findOrFail($post->id);
        }

        $check_has = $request->hasFile('image_name');
        $file_name = $request->file('image_name');
        $name = $todate.'.'.$file_name->getClientOriginalExtension();
        $path_url = public_path('/uploads/posts');
        $path = $path_url.'/'.$post->id;
        $imagename = FileUploadComponent::upload($check_has,$file_name, $path, $name);
        $post_data = [
            'title'=>$post->title,
            'body'=>$post->body,
            'image_name'=>$imagename,
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
