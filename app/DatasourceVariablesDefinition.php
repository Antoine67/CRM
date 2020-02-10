<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatasourceVariablesDefinition extends Model
{
    protected $table = 'datasources_variables_definition';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name' , 'description', 'id_datasource_default'
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

    public function customer()
    {
        return $this->hasOne('App\DatasourceDefault', 'id_datasource_default', 'id');
    }
}
