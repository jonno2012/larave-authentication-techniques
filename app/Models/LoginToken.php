<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class LoginToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token'];

    public static function generateFor(User $user): object
    {
        return self::create([
            'user_id' => $user->id,
            'token' => Str::random(50)
        ]);
    }

    public function send()
    {
        $url = url('/auth/token', $this->token);
        Mail::raw(
            "<a href='{$url}'>{$url}</a>",
            function($message) {
                $message->to($this->user->email)
                    ->subject('Login to Laracasts');
            }
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
