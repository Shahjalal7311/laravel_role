<?php

namespace App\Http\Controllers;

use File;
use App\Artical;
use App\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Common\FileUploadComponent;
use Illuminate\Support\Facades\Storage;

class ArticalController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Artical::where('is_delete','1')
            ->orderby('id','desc')
            ->with('user')
            ->paginate();
        return view('artical.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('artical.new');
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
        ]);

        $check_has = $request->hasFile('image_name');
        $file_name = $request->file('image_name');
        if(!empty($check_has)){
            $name = time().'.'.$file_name->getClientOriginalExtension();
        }else{
            $name = '';
        }
        //file uploads 
        $save_data = [
            'title'=>$request->title,
            'body'=>$request->body,
            'image_name'=>$name,
        ];
        
        $data = $request->user()->articals()->create($save_data);
        if($data->toarray()){
            if($request->hasFile('image_name')){
                $artical = Artical::findOrFail($data->id);
                 $file = $request->file('image_name');
                 // $url = 'storage/app/';
                 $url = 'https://'.env('AWS_BUCKET').'.s3.' .env('AWS_REGION'). '.amazonaws.com/';
                 $filePath = 'development/laravel/articals/'.$data->id .'/';
                 $uploadPath = 'development/laravel/articals/'.$data->id .'/'.$name;
                 $path = $url.$uploadPath;
                 $save = Storage::disk('s3')->put($filePath.$name, file_get_contents($file),'public');
                 Artical::where('id', $data->id)->update([
                  'upload_path'=>$path,
                ]);
             }
            $request->session()->flash('success', 'Record successfully added!');
        }else{
            $request->session()->flash('warning', 'Record not added!');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Artical  $artical
     * @return \Illuminate\Http\Response
     */
    public function show(Artical $artical)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Artical  $artical
     * @return \Illuminate\Http\Response
     */
    public function edit(Artical $artical)
    {
        $artical = Artical::findorFail($artical->id);
        return view('artical.edit',compact('artical'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Artical  $artical
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artical $artical)
    {
        $todate = date("Y-m-d-H-i-s");
        $this->validate($request, [
            'title' => 'required|min:10',
            'body' => 'required|min:20',
            'image_name' => 'required',
        ]);

        $me = $request->user();
        if( $me->hasRole('Admin') ) {
            $artical = Artical::findOrFail($artical->id);
        } else {
            $artical = $me->articals()->findOrFail($artical->id);
        }
        
        $check_has = $request->hasFile('image_name');
        $file = $request->file('image_name');
        $name = time().'.'.$file->getClientOriginalExtension();
        $url = 'https://'.env('AWS_BUCKET').'.s3.' .env('AWS_REGION'). '.amazonaws.com/';
        $filePath = 'development/laravel/articals/'.$artical->id .'/';
        $uploadPath = 'development/laravel/articals/'.$artical->id .'/'.$name;
        $path = $url.$uploadPath;
        // $url = 'storage/app/';
        // Storage::disk('public')->put($filePath.$name, file_get_contents($file),'public');
        Storage::disk('s3')->put($filePath.$name, file_get_contents($file),'public');
        $data = Artical::where('id',$artical->id)
                ->update([
                    'title'=>$artical->title,
                    'body'=>$artical->body,
                    'image_name'=>$name,
                    'upload_path'=>$path,
                ]);
        if($data){
            $request->session()->flash('success', 'Record successfully Updated!');
        }else{
            $request->session()->flash('warning', 'Record not Updated!');
        }
        return redirect()->route('articals.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Artical  $artical
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artical $artical)
    {
        $me = Auth::user();

        if($me->hasRole('admin')){
            $artical = Artical::findorFail($artical->id);
        }else{
            $artical = $me->artical()->findorFail($artical->id);
        }

        Artical::where('id',$artical->id)
                ->update([
                    'is_delete'=>'0',
                    // 'delete_by'=>$artical->user_id,
                ]);
        flash()->success('Post has been deleted.');
        return redirect()->route('articals.index');        
    }
}
