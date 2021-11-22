<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Share;

class Companies extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'companies';
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
            if (!isset($model->slug)) {
                $model->slug = Str::slug($model->name, '-');
            }
            if (!isset($model->sort) || empty($model->sort)) {
                $model->sort = Share::DEFAULT_SORT;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

//    public function sluggable()
//    {
//        return [
//            'slug' => [
//                'source' => 'name'
//            ]
//        ];
//    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Возвращает массив названий организаций, хранящихся в БД
     * @return array
     */
    public static function getNamesArray()
    {
        return static::select('name')->pluck('name')->toarray();
    }

	/**
	* Возвращает массив организаций, указанных как родительские
	*/
	public static function getParentsArray()
	{
		$companies = static::where('status', Share::STATUS_ACTIVE)
					->where('parent_id', '=', null)
					->select('id', 'name')
					->orderBy('sort', 'asc')
					->orderBy('name', 'asc')
					->groupBy('id')
					->get()
					->toarray();

		$parentsArray = [];
		$parentsArray[null] = " -- ";
		foreach ($companies as $key => $company) {
			$parentsArray[$company["id"]] = $company["name"];
		}
		return $parentsArray;
	}

    /**
     * Удаляет из справочника запись об организации по ее названию
     * @param string $company Название организации
     */
    public static function dropCompanyByName($company)
    {
        static::where('name', $company)->delete();
    }

    /**
     * Возвращает список активных организаций
     *
     * @return array
     */
    public static function getActiveCompaniesSlugArray()
    {
        $companies = static::select('*')
            ->where('status', Share::STATUS_ACTIVE)
            ->orderBy('sort', 'asc')
            ->pluck('slug')
            ->toarray();
        return $companies;
    }

    /**
     * Добавляет в БД новую организацию
     * @return mixed
     */
    public static function createCompany($name)
    {
        $company = static::create([
            'name' => $name,
            'sort' => Share::DEFAULT_SORT,
            'status' => 1,
        ]);
        return $company;
    }

    /**
     * Возвращает название организации в соответствии со значением slug
     * из url-запроса
     * в случае отсутствия slug в url - возвращает null
     * @param string $slug
     * @return string|null
     */
    public static function getCompanyNameBySlug($slug = null)
    {
        if (!empty($slug)) {
            $company = static::where('slug', '=', $slug)->first();
            return !empty($company->name) ? $company->name : null;
        }
        return null;
    }

    /**
     * Возвращает сотрудников организации по переданному slug организации
     *
     * @param [type] $slug
     * @return void
     */
    public static function getContactsBySlug($slug)
    {
        $company = static::where([['slug', '=', $slug], ['status', '=', Share::STATUS_ACTIVE]])->first();
        $contacts = Contacts::where([['company', '=', $company->name], ['status', '=', Share::STATUS_ACTIVE]])->get();
        return $contacts;
    }

    /**
     * Возвращает структуру организаций для Google Org Charts
     *
     * @return void
     */
    public static function getCompaniesOrgChartData()
    {
		$rootCompanies = Companies::where('status', Share::STATUS_ACTIVE)
					->where('parent_id', '=', null)
					->select('name', 'slug')
					->orderBy('sort', 'asc')
					->orderBy('name', 'asc')
					->groupBy('id')
					->get();

        $childCompanies = Companies::where('status', Share::STATUS_ACTIVE)
                    ->where('parent_id', '<>', null)
                    ->select('name', 'parent_id', 'slug')
                    ->get();

        $result = [];
        foreach($rootCompanies as $company) {
            $url = route('company.show', ['slug' => $company->slug]);
            $rootLevelArray = [['v' => $company->name, 'f' => '<a href='.$url.'>'.$company->name.'</a>'], '', $url];
            array_push($result, $rootLevelArray);
        }
        foreach ($childCompanies as $company) {
            $parent_name = Companies::where('id', $company->parent_id)->first();
            $url = route('company.show', ['slug' => $company->slug]);
            $childLevelArray = [['v' => $company->name, 'f' => '<a href='.$url.'>'.$company->name.'</a>'], $parent_name->name, $url];
            array_push($result, $childLevelArray);
        }
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
