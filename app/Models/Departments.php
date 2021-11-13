<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Share;

class Departments extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'departments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!isset($model->sort) || empty($model->sort)) {
                $model->sort = Share::DEFAULT_SORT;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    
    /**
     * Возвращает массив названий структурных подразделений, хранящихся в БД
     * @return array
     */
    public static function getNamesArray()
    {
        return static::select('name')->pluck('name')->toarray();
    }    
    
    /**
     * Удаляет из справочника запись об отделе по его названию
     * @param string $department Название отдела
     */
    public static function dropDepartmentByName($department)
    {
        static::where('name', $department)->delete();
    }
    
    /**
     * Добавляет в БД новый отдел
     * @return mixed
     */
    public static function createDepartment($name)
    {
        $department = static::create([
            'name' => $name,
            'sort' => Share::DEFAULT_SORT,
            'status' => 1,
        ]);
        return $department;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
