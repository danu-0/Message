<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $table = 'pesan';
    protected $fillable = ['user_id', 'recipient_id', 'content'];
    protected $hidden=['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
