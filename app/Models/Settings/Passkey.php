<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Passkey extends Pivot
{
    protected $table = 'passkeys';
}
