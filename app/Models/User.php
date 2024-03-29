<?php

namespace chatty\Models;

use chatty\Models\Status;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;


class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'users';

    protected $fillable = [
        'username', 
        'location', 
        'first_name', 
        'last_name', 
        'email', 
        'password'
    ];


    protected $hidden = [
        'password', 
        'remember_token'
    ];



    // Relationship to STATUS table

    public function statuses()
    {
        return $this->hasMany('chatty\Models\Status', 'user_id');
    }


    public function likes()
    {
        return $this->hasMany('chatty\Models\Like', 'user_id');
    }

    /**
     *
     * HELPER functions  to get various user name combinations
     *
     */
    // get full name
    public function getName() 
    {
        if ($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }
        if ($this->first_name) {
            return $this->first_name;
        }
        return null;
    }

    // get name or username
    public function getNameOrUsername()
    {
        return $this->getName() ?: $this->username;
    }

    // get first- or username
    public function getFirstnameOrUsername()
    {
        return $this->first_name ?: $this->username;
    }


    //get avatar from user
    public function getAvatarUrl()
    {
        return "https://www.gravatar.com/avatar/{{md5($this->email)}}?d=mm&s=40";
    }

    /**
     *
     * Relationships with FRIENDS table
     *
     */
    public function friendsOfMine() 
    {
        return $this->belongsToMany('chatty\Models\User', 'friends', 'user_id', 'friend_id');
    }

    public function friendOf()
    {
        return $this->belongsToMany('chatty\Models\User', 'friends', 'friend_id', 'user_id');
    }

    public function friends()
    {
        $friendsList = 
            $this->friendsOfMine()->wherePivot('accepted', true)->get()
                ->merge( $this->friendOf()->wherePivot('accepted', true )->get() );
        //dd($friendsList);
        return $friendsList;
    }


    /**
     * FRIEND REQUESTS handling
     */
    public function friendRequests()
    {
        return $this->friendsOfMine()->wherePivot('accepted', false)->get();
    }

    public function friendRequestsPending()
    {
        return $this->friendOf()->wherePivot('accepted', false)->get();
    }

    public function hasFriendRequestPending(User $user)
    {
        return (bool) $this->friendRequestsPending()->where('id', $user->id)->count();
    }

    public function hasFriendRequestReceived(User $user)
    {
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }

    public function addFriend(User $user)
    {
        return $this->friendOf()->attach($user->id);
    }

    public function deleteFriend(User $user)
    {
        $this->friendOf()->detach($user->id);
        $this->friendsOfMine()->detach($user->id);
    }

    public function acceptFriendRequest(User $user)
    {
        return $this->friendRequests()->where('id', $user->id)->first()
                ->pivot->update(['accepted' => true]);
    }

    public function isFriendsWith(User $user)
    {
        return (bool) $this->friends()->where('id', $user->id)->count();
    }


    // find out if a user has already liked a certain status
    public function hasLikedStatus(Status $status)
    {
        return (bool) $status->likes->where('user_id', $this->id)->count();
    }

}
