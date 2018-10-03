@extends('layouts.app_admin')

@section('title', 'Posts')

@section('content')

<section class="content-header">
  @include('partial.breadcome')
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">{{ $result->total() }} {{ str_plural('Articals', $result->count()) }}</h3>
              <div class="pull-right">
                @can('add_articals')
                    <a href="{{ route('articals.create') }}" class="btn btn-primary btn-sm"> 
                        <i class="glyphicon glyphicon-plus-sign"></i> Create
                    </a>
                @endcan
              </div>
              <div class="pull-right">
                 @can('import_articals')
                  <a href="{{ route('add') }}" class="btn btn-primary btn-sm" style="margin-right: 20px;"> 
                      <i class="glyphicon glyphicon-plus-sign"></i> Import
                  </a>
                  @endcan
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Author</th>
                        <th>Created At</th>
                        @can('edit_articals', 'edit_articals')
                            <th class="text-center">Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $item)
                        <?php $image_name =  $item->image_name; ?>
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>
                              <!-- <?php if((!($image_name))){ ?>
                              <img src="<?php  asset("storage/articals/$item->id/$item->image_name")?>" width="50" height="50" alt="{{$item->title}}"/>
                            <?php } else { ?>
                              <img src="{{asset('img/post_dafult.jpg')}}" width="50" height="50" alt="{{$item->title}}"/>
                            <?php } ?> -->
                            <a href="<?php echo $item->upload_path ?>" target="_blank">
                              <img src="<?php echo $item->upload_path ?>" width="50" height="50" alt="{{$item->title}}"/>
                            </a>
                            
                            </td>
                            <td>{{ $item->user['name'] }}</td>
                            <td>{{ $item->created_at->toFormattedDateString() }}</td>
                            @can('edit_articals', 'edit_articals')
                            <td class="text-center">
                                @include('shared._actions', [
                                    'entity' => 'articals',
                                    'id' => $item->id
                                ])
                            </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
                {{ $result->links() }}
              </ul>
            </div>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>    
</section>

@endsection