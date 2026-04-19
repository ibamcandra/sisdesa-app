<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class District extends Model {
    protected $table = 'indonesia_districts';
    protected $guarded = [];
    public function city() { return $this->belongsTo(City::class, 'city_code', 'code'); }
}
