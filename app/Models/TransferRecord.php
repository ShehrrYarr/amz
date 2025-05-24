<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferRecord extends Model
{
    use HasFactory;
//     public function fromUser()
// {
//     return $this->belongsTo(User::class, 'from_user_id');
// }
public function fromUser()
{
    return $this->belongsTo(User::class, 'from_user_id');
}

public function mobile()
    {
        return $this->belongsTo(Mobile::class);
    }


public function toUser()
{
    return $this->belongsTo(User::class, 'to_user_id');
}
}
