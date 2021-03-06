<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<!-- Title of Post Form Input -->
{!! Form::select('size', ['1' => 'General info', '2' => 'Other'], 'S', ['class' => 'form-control', 'placeholder' => 'Select Category']); !!}

<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Title') !!}
    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title of Post']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>
<!-- Text Form Input -->
<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('image_name', 'Photo') !!}
    {!! Form::file('image_name', null, ['class' => 'form-control', 'placeholder' => 'Photo']) !!}
    @if ($errors->has('image_name')) <p class="help-block">{{ $errors->first('image_name') }}</p> @endif
    @if (!empty($artical->image_name))
    	<img src="{{asset('uploads/articals').'/'.$artical->id.'/'.$artical->image_name}}" width="50" height="50" alt="{{$artical->image_name}}"/>
    @endif
</div>
<!-- Text body Form Input -->
<div class="form-group @if ($errors->has('body')) has-error @endif">
    {!! Form::label('body', 'Body') !!}
    {!! Form::textarea('body', null, ['class' => 'form-control my-editor', 'placeholder' => 'Body of Post...']) !!}
    @if ($errors->has('body')) <p class="help-block">{{ $errors->first('body') }}</p> @endif
</div>


