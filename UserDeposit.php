<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeposit extends Model
{
    use HasFactory;
    protected $primarykey = 'ud_id';

    protected $fillable = [

        'ud_id',
        'ud_us_id',
        'ud_bank_id',
        'ud_deposit_date',
        'ud_amount',
        'ud_bb_id',
        'ud_approved_status',
        'ud_approved_date',
        'ud_approved_by'
    ];
}
