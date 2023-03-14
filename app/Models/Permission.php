<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $table = 'permissions';
    protected $fillable = ['level'];

    private string $level;



    /**
     * Get the value of level
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Set the value of level
     */
    public function setLevel($level): void
    {
        $this->level = $level;
    }
}
