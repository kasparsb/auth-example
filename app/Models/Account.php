<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['hash', 'type', 'name',];

    public $dates = ['approved_at', 'online_at', 'offline_at',];

    protected $casts = [
        'device_info' => 'object',
        'info' => 'object',
    ];

    public static function findOrFailByHash($hash) {
        $r = Account::byHash($hash)->first();

        if ($r) {
            return $r;
        }

        abort(404);
    }

    public function scopeByHash($query, $hash) {
        return $query->where('hash', '=', $hash);
    }

    public function scopeByUnapproved($query) {
        $query->whereNUll('approved_at');
    }

    public function scopeByApproved($query) {
        $query->whereNotNUll('approved_at');
    }

    public function getIsApprovedAttribute() {
        return is_null($this->approved_at) ? false : true;
    }

    /**
     * Vai account ir admin
     * Nosaka pēc lauka type
     */
    public function getIsAdminAttribute() {
        return $this->type == 'admin';
    }

    public function getIsTabletAttribute() {
        return $this->type == 'tablet';
    }

    public function setInfo($name, $data) {
        $info = is_object($this->info) ? $this->info : (object)[];

        $info->{$name} = $data;

        $this->info = $info;
        $this->save();
    }
}
