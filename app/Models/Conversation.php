<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_one', 'user_two'];

    public $appends = ['talking_to'];

    public function getTalkingToAttribute() {
        $talkingToId = $this->user_one;
        if ($this->user_one == auth()->user()->id) {
            $talkingToId = $this->user_two;
        }
        return User::find($talkingToId);
    }

    public function messages() {
        return $this->hasMany(ChatMessage::class);
    }
}
