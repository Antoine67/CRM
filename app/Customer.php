<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public $connected = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'created_at', 'updated_at', 'picture', 'sharepoint_client', 'sharepoint_extranet', 'phone', 'web_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    public function datasources()
    {
        return $this->hasMany('App\Datasource', 'id_customer', 'id');
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'id_customer', 'id');
    }


    public function getLastUpdate() {
        if($this->updated_at === null) return " - ";
        return Carbon::create($this->updated_at->toDateTimeString(), 'Europe/Paris')->format('d/m/Y à H:i');
    }
}
