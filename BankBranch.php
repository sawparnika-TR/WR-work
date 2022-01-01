<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    use HasFactory;
    protected $primarykey = 'bb_id';

    protected $fillable = [

        'bb_id',
        'bb_bk_id',
        'bb_branch_name',
        'bb_ifsc',
        'status'
    ];
}
