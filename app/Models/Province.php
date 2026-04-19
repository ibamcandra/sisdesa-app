<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Province extends Model {
    protected $table = 'indonesia_provinces';
    protected $guarded = [];
    public function cities() { return $this->hasMany(City::class, 'province_code', 'code'); }
}
