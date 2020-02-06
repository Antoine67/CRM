<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Datasource extends Model
{
    protected $table = 'datasources';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_associated', 'query', 'id_database', 'id_customer',
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
         'created_at' => 'datetime',
         'updated_at' => 'datetime',
    ];

    public function database()
    {
        return $this->hasOne('App\Database', 'id', 'id_database');
        /*print_r("ID : ". $this->id_database);
        return \App\Database::first()->where('id', '=', $this->id_database);*/
        
    }
}
