<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendors extends Model
{
    use HasFactory;

    protected $fillable = [
        "company_name",
        "company_email",
        "company_number",
        "company_fax",
        "company_address1",
        "company_address2",
        "company_website",
        "business_type",
        "store_name",
        "license",
        "registration",
        "nop",
        "payment_type",
        "bank_name",
        "branch_name",
        "account_name",
        "account_number",
        "verify",
    ];
}
