<?php

namespace App\Models;

use App\Contracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;

/**
 * @property int       id
 * @property int|mixed user_id
 * @property string    subject
 * @property string    body
 * @property User      user
 */
class News extends Model
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Sortable;

    public $table = 'news';

    protected $fillable = [
        'user_id',
        'subject',
        'body',
        'public',
        'published_at',
        'stub',
        'visible',
        'slug',
    ];

    public static $rules = [
        'subject' => 'required',
        'body'    => 'required',
    ];

    public $sortable = [
        'id',
        'subject',
        'user_id',
        'created_at',
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
