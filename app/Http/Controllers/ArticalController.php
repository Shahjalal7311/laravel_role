<?php

namespace App\Http\Controllers;

use File;
use App\Artical;
use App\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Common\FileUploadComponent;

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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        // $request->artical_image->store('image_name');
        $check_has = $request->hasFile('image_name');
        $file_name = $request->file('image_name');
        $name = $todate.'.'.$file_name->getClientOriginalExtension();
        $path_url = public_path('/uploads/articals');
        //file uploads
        
        $save_data = [
            'title'=>$request->title,
            'body'=>$request->body,
            'image_name'=>$name,
            'image_path'=>$path_url,
        ];
        
        $data = $request->user()->articals()->create($save_data);
        if($data->toarray()){
            $path = $path_url.'/'.$data->id;
            File::makeDirectory($path, $mode = 0777, true, true);
            FileUploadComponent::upload($check_has,$file_name, $path, $name);
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
        $file_name = $request->file('image_name');
        $name = $todate.'.'.$file_name->getClientOriginalExtension();
        $path_url = public_path('/uploads/articals');
        $path = $path_url.'/'.$artical->id;
        $imagename = FileUploadComponent::upload($check_has,$file_name, $path, $name);
        $save_data = [
            'title'=>$artical->title,
            'body'=>$artical->body,
            'image_name'=>$imagename,
        ];

        $data = $artical->update($save_data);
        if($data){
            $request->session()->flash('success', 'Record successfully added!');
        }else{
            $request->session()->flash('warning', 'Record not added!');
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
            $post = Artical::findorFail($artical->id);
        }else{
            $post = $me->artical()->findorFail($artical->id);
        }

        Artical::where('id',$post->id)
                ->update([
                    'is_delete'=>'0',
                    'delete_by'=>$post->user_id,
                ]);
        flash()->success('Post has been deleted.');
        return redirect()->route('articals.index');        
    }
}
