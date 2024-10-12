<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'logs_requests';

    protected $fillable = [
        'url',
        'http_method',
        'controller',
        'controller_method',
        'request_body',
        'request_headers',
        'user_id',
        'user_ip',
        'user_agent',
        'response_status',
        'response_body',
        'response_headers',
    ];

    protected $casts = [
        'request_body' => 'array',
        'request_headers' => 'array',
        'response_body' => 'array',
        'response_headers' => 'array',
    ];

    /**
     * Returns the user that made the HTTP request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
