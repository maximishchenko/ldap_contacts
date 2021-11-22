<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\Contacts;
use App\Models\Share;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    //
    /**
     * Страница справочника
     */
    public function index()
    {
        $company = new Companies();
        $model = $company->getCompaniesOrgChartData();
        return view("company", [
            'model' => $model,
        ]);
    }

    public function show($slug)
    {
        $model = new Companies();
        $contacts = $model->getContactsBySlug($slug);
        $contactModel = new Contacts();
        $data = $contactModel->getContactsOrgChartData($slug);
        return view("orgchart", [
            'contacts' => $contacts,
            'data' => $data,
        ]);
    }
}
