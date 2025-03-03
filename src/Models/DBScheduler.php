<?php

namespace Satishsinghdevbha\DbScheduler\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DBScheduler extends Model {
    use HasFactory;

    protected $table;

    public function __construct(array $attributes = [])
    {
        $this->table = config("db-scheduler.table_name");
        parent::__construct($attributes);
    }

    protected $fillable = [
        "command", "arguments", "options", "cron_expression", "environments", "status"
    ];

}
