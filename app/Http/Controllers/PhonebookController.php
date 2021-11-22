<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacts;
use App\Models\Companies;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Export;
use App\Models\Share;

class PhonebookController extends Controller
{
    //

    /**
     * Страница справочника
     */
    public function index()
    {
        $model = new Contacts();
        return view("addressbook", [
            'model' => $model,
        ]);
    }

    /**
     * Выгружает справочник организации в Excel
     *
     * @param string $slug slug-идентификатор организации
     * @return mixed
     */
    public function companyToExcel($slug)
    {
        ob_start();
        $spreadsheet = Export::companyToExcel($slug);
        $writer = new Xlsx($spreadsheet);
        Export::setExcelHeaders($slug);
        return $writer->save('php://output');
        ob_end_flush();
    }


    /**
     * Автодополнение строки поиска справочника
     *
     * @param Request $request
     * @return string html-строка с результатами выборки
     */
    public function autocomplete(Request $request)
    {
        $displayName = $request->get('displayName');
        $company = $request->get('company');

        $contacts = Contacts::orderBy('sort', 'asc')
                ->select("displayName")
                ->where("status", Share::STATUS_ACTIVE)
                ->where("displayName","LIKE","%{$displayName}%");

        $data = ($company !== null) ? $contacts->where('company', $company)->get() : $contacts->get();

        if ($data->count() > 0) {
            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; width: 100%;top: 53px;">';
            foreach($data as $row) {
                $output .= '
                    <li style="padding: 3px 10px;"><a class="autocomplete" href="#">'.$row->displayName.'</a></li>
                ';
            }
            $output .= '</ul>';
            //echo $output;
        } else {
            $output = '<ul class="dropdown-menu" style="display:block; position:absolute; width: 100%;top: 53px;">';
            $output .= '
                <li style="padding: 3px 10px;">
                    <span>' . trans("messages.results_not_found") . '</span>
                </li>
            ';
            $output .= '</ul>';
            //echo $output;
        }
        return $output;
    }
}
