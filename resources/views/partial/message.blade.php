@foreach (['danger', 'warning', 'success', 'info'] as $key)
     @if(Session::has($key))
         <p class="alert alert-{{ $key }}">{{ Session::get($key) }}</p>
     @endif
  @endforeach	