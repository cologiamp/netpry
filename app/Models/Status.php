<?php

namespace chatty\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
	protected $table = 'statuses';

	protected $fillable = [
		'body'
	];

	public function user() 
	{
		return $this->belongsTo('chatty\Models\User', 'user_id');
	}

	public function scopeNotReply($query)
	{
		return $query->whereNull('parent_id');
	}

	public function replies()
	{
		return $this->hasMany('chatty\Models\Status', 'parent_id');
	}

	public function likes()
	{
		return $this->morphMany('chatty\Models\Like', 'likeable');
	}
}