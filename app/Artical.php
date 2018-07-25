<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artical extends Model
{
	protected $fillable = ['title', 'body','image_name'];

	protected $table = 'articals';
    //
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
