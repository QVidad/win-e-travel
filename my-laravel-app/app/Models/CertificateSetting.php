<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateSetting extends Model
{
    protected $fillable = [
        'university_name',
        'college_name',
        'signer_name',
        'signer_title',
        'signature_image_path',
        'university_logo_path',
        'college_logo_path',
        'description',
        'background_image_path',
    ];
}
