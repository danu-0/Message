<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\CssSelector\Node\FunctionNode;

class Pesan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pesan';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'recipient_id', 'content'];
    protected $hidden = ['deleted_at'];
    protected function serializeDate($date)
    {
        return $date->format(format: 'Y-m-d H:i:s');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function recipient(){
        return $this->belongsTo(User::class,'recipient_id');
    }

}
