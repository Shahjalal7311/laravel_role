<?php 
  $cr_route = Route::currentRouteName();
  $breadcrumb = explode('.', $cr_route);
 ?>
<h1>
    Dashboard
</h1>
<ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
	<li class="active" style="text-transform: capitalize;">{{$breadcrumb[0]}}</li>
</ol>