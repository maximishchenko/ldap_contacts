<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Share;
use App\Models\Companies;
use App\Models\Departments;

class Contacts extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'contacts';
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
     * Возвращает список организаций
     * @return mixed
     */
    public function getCompanies($slug = null, $displayName = null)
    {
        $companies = Companies::orderBy('companies.sort', 'asc')->orderBy('companies.name', 'asc');
        $companies->groupBy('name');
		//$companies->where('parent_id', '=', null);
        $companies = $companies->where('companies.status', '=', Share::STATUS_ACTIVE);
        if (isset($slug) && !empty($slug)) {
            $companies = $companies->where('slug', '=', $slug);
        }
        if (!empty($displayName)) {
            $companies->join('contacts', 'contacts.company', '=', 'companies.name');
            $companies->where('contacts.displayName', 'like', '%' . $displayName . '%');
        }

        return $companies->get();
    }



    /**
     * Возвращает список организаций, имеющих подчиненные таможенные органы или не входящие в состав других таможенных органов
     * @return mixed
     */
    public function getParentCompanies($slug = null, $displayName = null)
    {
        $companies = Companies::orderBy('companies.sort', 'asc')->orderBy('companies.name', 'asc');
        $companies->groupBy('name');
		$companies->where('parent_id', '=', null);
        $companies = $companies->where('companies.status', '=', Share::STATUS_ACTIVE);
        if (isset($slug) && !empty($slug)) {
            $companies = $companies->where('slug', '=', $slug);
        }
        if (!empty($displayName)) {
            $companies->join('contacts', 'contacts.company', '=', 'companies.name');
            $companies->where('contacts.displayName', 'like', '%' . $displayName . '%');
        }

        return $companies->get();
    }


	public function getChildCompaniesCount($id)
	{
		$child = Companies::where('parent_id', '=', $id)
					->where('status', '=', Share::STATUS_ACTIVE)
					->count();
		return ($child > 0) ? true : false;
	}

	/**
	 * Возвращает дочерние организации
	 */
	public function getChildsArray($id)
	{
		$companies = Companies::where('parent_id', $id)
			->where('status', '=', Share::STATUS_ACTIVE)
			//->select('id', 'name')
			->orderBy('sort', 'asc')
			->orderBy('name', 'asc')
			->groupBy('id')
			->get();
			//->toarray();
		return $companies;
	}

	/**
	 * Проверяет необходимость разворачивать дерево вложенных организаций на основании url
	 */
	public function isTreeOpened($id, $slug)
	{
		$childCompanies = $this->getChildsArray($id);

		$childsArray = [];
		foreach ($childCompanies as $key => $company) {
			array_push($childsArray, $company->slug);
		}
		if (in_array($slug, $childsArray)) {
			return true;
		} elseif ($slug == request('slug')) {
			return false;
		} else {
			return false;
		}
	}

    /**
     * Возвращает список отделов по названию организации
     * @param string $company название организации
     * @return mixed
     */
    public function getDepartments($company, $displayName = null)
    {
        $departments = Contacts::orderBy('departments.sort', 'asc')
			->orderBy('departments.name', 'asc')
            ->select('contacts.department')
            ->join('departments', 'departments.name', '=', 'contacts.department')
            ->where('contacts.status', '=', Share::STATUS_ACTIVE)
            ->where('contacts.company', '=', $company)
            ->distinct();

        if (!empty($displayName)) {
            return $departments->where('contacts.displayName', 'like', '%' . $displayName . '%')->get();
        }

        return $departments->get();
    }

    /**
     * Возвращает список контактов по названию организации и отделу
     * @param string $company
     * @param string $department
     * @param string $displayName
     * @return mixed
     */
    public function getContacts($company, $department, $displayName = null)
    {
        $contacts = Contacts::orderBy('contacts.sort', 'asc')
			->orderBy('contacts.displayName', 'asc')
            ->select('contacts.*')
            ->where('contacts.status', '=', Share::STATUS_ACTIVE)
            ->where('contacts.department', '=', $department)
            ->where('contacts.company', '=', $company);
            //->whereRaw('telephoneNumber <> ""')
            //->whereRaw('homePhone <> ""');

        if (!empty($displayName)) {
            return $contacts->where('contacts.displayName', 'like', '%' . $displayName . '%')->get();
        }

        return $contacts->get();
    }

    public function getCountContacts($company, $department, $displayName = null)
    {
        $contacts = Contacts::orderBy('contacts.sort', 'asc')
            ->select('contacts.*')
            ->where('contacts.status', '=', Share::STATUS_ACTIVE)
            ->where('contacts.department', '=', $department)
            ->where('contacts.company', '=', $company);
            //->whereRaw('telephoneNumber <> ""')
            //->whereRaw('homePhone <> ""');

        if (!empty($displayName)) {
            return $contacts->where('contacts.displayName', 'like', '%' . $displayName . '%')->count();
        }

        return $contacts->count();
    }

    /**
     * Возвращает массив sid из БД
     * @return array
     */
    public static function getObjectGiudsArray()
    {
        return static::select('sid')->pluck('sid')->toarray();
    }

    /**
     * Возвращает данные контакта, хранящегося в БД по полю objectGuid
     * @param string $objectGuid LDAP objectGuid, для поиска записи в БД
     * @return mixed
     */
    public static function findContactByObjectGuid($objectGuid)
    {
        return static::where('sid', $objectGuid)->first();
    }

    /**
     * Возвращает структуру сотрудников для Google Org Charts
     *
     * @return void
     */
    public static function getContactsOrgChartData($slug)
    {
        $company = Companies::where('slug', '=', $slug)->first();
		$rootContacts = self::where([['status', '=', Share::STATUS_ACTIVE], ['company', '=', $company->name], ['manager', '=', null]])
					->orderBy('sort', 'asc')
					->orderBy('displayName', 'asc')
					->groupBy('id')
					->get();

        $childContacts = self::where([['status', '=', Share::STATUS_ACTIVE], ['company', '=', $company->name], ['manager', '<>', null]])
                    ->get();


        $result = [];
        foreach($rootContacts as $contact) {
            $rootLevelArray = [['v' => $contact->distinguishedName, 'f' => $contact->displayName], '', ''];
            array_push($result, $rootLevelArray);
        }
        foreach ($childContacts as $contact) {
            $parent_name = self::where('distinguishedName', $contact->manager)->first();
            $childLevelArray = [['v' => $contact->distinguishedName, 'f' => $contact->displayName], $parent_name->distinguishedName, ''];
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
