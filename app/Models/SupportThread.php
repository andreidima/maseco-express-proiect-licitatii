<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SupportThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_user_id',
        'admin_user_id',
        'subject',
        'type',
        'status',
        'problem_category',
        'problem_severity',
        'problem_summary',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    protected $with = ['latestMessage'];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(SupportMessage::class)->latestOfMany();
    }
}
