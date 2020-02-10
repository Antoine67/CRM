<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatasourceVariable extends Model
{
    protected $table = 'datasources_variables';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value' , 'id_datasource_variable_definition' , 'id_customer'
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
        return $this->hasOne('App\Customer', 'id_customer', 'id');
    }

    public function datasource_variable_definition()
    {
        return $this->hasOne('App\DatasourceVariablesDefinition', 'id_datasource_variable_definition', 'id');
    }
}
