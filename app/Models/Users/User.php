<?php
namespace HorseStories\Models\Users;

use HorseStories\Events\Event;
use HorseStories\Models\Comments\Comment;
use HorseStories\Models\Conversations\Conversation;
use HorseStories\Models\Horses\Horse;
use HorseStories\Models\Notifications\Notification;
use HorseStories\Models\Roles\Role;
use HorseStories\Models\Settings\Setting;
use HorseStories\Models\Statuses\Status;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, FollowingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'date_of_birth',
        'country',
        'gender',
        'about'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function horses()
    {
        return $this->hasMany(Horse::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function statuses()
    {
        return $this->hasManyThrough(Status::class, Horse::class, 'user_id', 'horse_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany(Status::class, 'likes')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'creator_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)->withPivot('last_view', 'deleted_at')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(Setting::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'receiver_id', 'id');
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if ($role->name == $name) return true;
        }

        return false;
    }

    /**
     * @param \HorseStories\Models\Roles\Role|int $role
     */
    public function assignRole($role)
    {
        return $this->roles()->attach($role);
    }

    /**
     * @param \HorseStories\Models\Roles\Role|int $role
     * @return int
     */
    public function removeRole($role)
    {
        return $this->roles()->detach($role);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('administrator') ? true : false;
    }

    /**
     * @param \HorseStories\Models\Conversations\Conversation $conversation
     */
    public function addConversation(Conversation $conversation)
    {
        $this->conversations()->attach($conversation);
    }

    /**
     * @return bool
     */
    public function hasUnreadMessages()
    {
        foreach ($this->conversations as $conversation) {
            if ($conversation->pivot->last_view == null) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public function countUnreadMessages()
    {
        $count = 0;

        foreach ($this->conversations as $conversation) {
            if ($conversation->pivot->last_view == null) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param \HorseStories\Models\Horses\Horse $horse
     * @return bool
     */
    public function isHorseOwner(Horse $horse)
    {
        return $this->id == $horse->owner->id;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->settings->language ?: 'en';
    }

    /**
     * @return bool
     */
    public function hasUnreadNotifications()
    {
        return $this->countUnreadNotifications() > 0;
    }

    /**
     * @return int
     */
    public function countUnreadNotifications()
    {
        $count = 0;

        foreach ($this->notifications as $notification) {
            if ($notification->read == false) {
                $count++;
            }
        }

        return $count;
    }

    public function markNotificationsAsRead()
    {
        foreach ($this->notifications as $notification) {
            $notification->markAsRead();
        }
    }
}
