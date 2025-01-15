<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'notification';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'content'];
    protected $hidden = ['deleted_at'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function serializeDate($date)
    {
        return $date->format(format: 'Y-m-d H:i:s');
    }
}




