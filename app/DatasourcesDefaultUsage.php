<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatasourcesDefaultUsage extends Model
{
    protected $table = 'datasources_default_usage';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_associated' , 'id_customer', 'default_usage',
    ];



}
