<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'contact_user_id'];
    protected $hidden = ['deleted_at'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function contact()
    {
        return $this->belongsTo(User::class, 'contact_user_id');
    }
    protected function serializeDate($date)
    {
        return $date->format(format: 'Y-m-d H:i:s');
    }
}
