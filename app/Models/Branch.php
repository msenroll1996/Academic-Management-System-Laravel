<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'status', 'phone_no'];

    public function userBranches()
    {
        return $this->hasMany(UserBranch::class)->where('user_id', Auth::user()->id);
    }
}
