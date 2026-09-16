<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'visibility',
        'icon',
        'created_by',
    ];

    /**
     * The admin who created this announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Users targeted by an exclusive announcement.
     */
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'announcement_user');
    }

    /**
     * Users who have read this announcement.
     */
    public function reads(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'announcement_reads')->withPivot('read_at');
    }

    /**
     * Check if a user has read this announcement.
     */
    public function isReadBy(User $user): bool
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if this announcement is visible to a given user.
     */
    public function isVisibleTo(User $user): bool
    {
        if ($this->visibility === 'public') {
            return true;
        }

        return $this->recipients()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope: announcements visible to a specific user.
     */
    public function scopeVisibleTo($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('visibility', 'public')
              ->orWhereHas('recipients', function ($sub) use ($user) {
                  $sub->where('user_id', $user->id);
              });
        });
    }
}
