<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        "full_name",
        "bank_name",
        "bank_branch",
        "beneficiary_name",
        "account_number",
        "iban_number",
        "swift_number",
        "phone_number",
        "distributor_id"
    ];

    public function distributor() {
        return $this->belongsTo(Distributor::class);
    }
}
