<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contacts;
use App\Models\Companies;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Export extends Model
{

    /**
     * Расширение файла excel
     * @var string
     */
    public static $excelExtension = '.xlsx';

    public static $pdfExtension = '.pdf';

    /**
     * Каталог приложения для экспорта файлов
     *
     * @var string
     */
    public static $exportFolder = 'public/export';

    public function __construct(array $attributes = array()) {
        return parent::__construct($attributes);
    }

    public static function companyToExcel($slug)
    {
        // номер строки заголовка книги
        $firstSheet = 1;
        // номер строки заголовка таблицы
        $tableHeaderSheet = $firstSheet + 10;
        // Номер первой строки (для вывода отделов)
        $tableFirstRow = $firstSheet + 10;
        // цвет заливки ячеек
        $sheetColor = 'afafaf';
        $tableSheetColor = '797979';
        // количество ячеек данных организации
        $companyDataSheetCount = 8;
        // Столбцы к которым применяется перенос по словам
        $wrapTextColumns = ['A', 'B', 'C', 'F'];
        // Цвет шрифта в заголовках таблицы
        $tableheaderFontColor = 'ffffff';
        // Столбцы, в которых текст выравнивается по центру
        $centeredTableColumns = ['D', 'E', 'G'];

        // Оформление, текст по центру ячейки
        $styleCenter = static::setTextAlignCenter();
        $fontStyleArray = static::getDefaultFontsSettings();

        $tableContactsFirstRow = $tableFirstRow + 1;

        $contactsModel = new Contacts();
        $company = Companies::where('slug', $slug)->firstOrFail();
        $departments = $contactsModel->getDepartments($company->name);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Альбомная ориентация листа
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        // Формат листа А4
        $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        // Заголовок книги
        $sheet->getCell('A'.$firstSheet)->setValue(trans('messages.excel_company_name'));
        // Название организации
        $sheet->getCell('B'.$firstSheet)->setValue($company->name);
        // Заголовки данных организации
        $sheet->getCell('A'.($firstSheet + 2))->setValue(trans('messages.official_postal_and_reference_details'));
        $sheet->getCell('A'.($firstSheet + 3))->setValue(trans('messages.companies_address'));
        // $sheet->getCell('A'.($firstSheet + 4))->setValue(trans('messages.companies_duty_service_phone'));
        // $sheet->getCell('A'.($firstSheet + 5))->setValue(trans('messages.companies_help_desk_phone'));
        $sheet->getCell('A'.($firstSheet + 4))->setValue(trans('messages.companies_reception_phone'));
        $sheet->getCell('A'.($firstSheet + 5))->setValue(trans('messages.companies_phone'));
        // $sheet->getCell('A'.($firstSheet + 8))->setValue(trans('messages.companies_fax_departmental'));
        $sheet->getCell('A'.($firstSheet + 6))->setValue(trans('messages.companies_fax_city'));
        // $sheet->getCell('A'.($firstSheet + 10))->setValue(trans('messages.companies_teletype_telex'));
        $sheet->getCell('A'.($firstSheet + 7))->setValue(trans('messages.companies_email'));
        // $sheet->getCell('A'.($firstSheet + 12))->setValue(trans('messages.companies_helpline'));
        // $sheet->getCell('A'.($firstSheet + 13))->setValue(trans('messages.companies_phone_city_code'));
        // $sheet->getCell('A'.($firstSheet + 14))->setValue(trans('messages.companies_phone_departmental_code'));
        // заполнение данных организации
        $sheet->getCell('B'.($firstSheet + 3))->setValue($company->address);
        // $sheet->getCell('B'.($firstSheet + 4))->setValue($company->duty_service_phone);
        // $sheet->getCell('B'.($firstSheet + 5))->setValue($company->help_desk_phone);
        $sheet->getCell('B'.($firstSheet + 4))->setValue($company->reception_phone);
        $sheet->getCell('B'.($firstSheet + 5))->setValue($company->phone);
        // $sheet->getCell('B'.($firstSheet + 8))->setValue($company->fax_departmental);
        $sheet->getCell('B'.($firstSheet + 6))->setValue($company->fax_city);
        // $sheet->getCell('B'.($firstSheet + 10))->setValue($company->teletype_telex);
        $sheet->getCell('B'.($firstSheet + 7))->setValue($company->email);
        // $sheet->getCell('B'.($firstSheet + 12))->setValue($company->helpline);
        // $sheet->getCell('B'.($firstSheet + 13))->setValue($company->phone_city_code);
        // $sheet->getCell('B'.($firstSheet + 14))->setValue($company->phone_departmental_code);

        // Настройки шрифтов по-умолчанию
        $spreadsheet->getDefaultStyle()->applyFromArray($fontStyleArray);
        $sheet->getStyle('A' . $firstSheet)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()
                ->setRGB($sheetColor);

        // Цвет ячейки данных организации
        for ($i = 3; $i <= $companyDataSheetCount; $i++) {
            $sheet->getStyle('A' . ($firstSheet + $i))
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()
                    ->setRGB($sheetColor);
        }

        $sheet->getStyle('A'.($firstSheet + 3))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Заголовок книги (жирный текст по центру объединенных ячеек)
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('B' . $firstSheet . ':H' . $firstSheet);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A' . ($firstSheet + 2) . ':H' . ($firstSheet + 2));

        // Заголовок данных организации, выделение жирным курсивом
        $sheet->getStyle('A' . ($firstSheet + 2))->getFont()->setBold(true)->setItalic(true);

        // Жирный шрифт
        $sheet->getStyle('A' . $firstSheet)->getFont()->setBold(true);

        // Заголовки таблицы. Жирный шрифт
        for ($i = 3; $i <= $companyDataSheetCount; $i++) {
            $sheet->getStyle('A' . ($firstSheet + $i))->getFont()->setBold(true);
        }

        // Заголовки таблицы (жирный текст по центру)
        $sheet->getStyle('A' . $tableHeaderSheet . ':H' . $tableHeaderSheet)->applyFromArray($styleCenter);
        $sheet->getStyle('A' . $tableHeaderSheet . ':H' . $tableHeaderSheet)->getFont()->setBold(true);
        $sheet->getStyle('A' . ($firstSheet + 1) . ':H' . ($firstSheet + 1))->getFont()->setBold(true);

        // Заголовки таблицы цвет ячеек
        $sheet->getStyle('A' . $tableHeaderSheet . ':H' . $tableHeaderSheet)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()
                ->setRGB($tableSheetColor);

        // данные организации, выравнивание по-центру
        for ($i = 3; $i <= $companyDataSheetCount; $i++) {
            $sheet->getStyle('B' . ($firstSheet + $i))->applyFromArray($styleCenter);
        }

        // Установить ширину ячеек
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(10);

        // Перенос по словам
        foreach ($wrapTextColumns as $wrap) {
            $sheet->getStyle($wrap)->getAlignment()->setWrapText(true);
        }

        // Заголовки таблицы
        $sheet->getCell('A' . $tableHeaderSheet)->setValue(trans('messages.contacts_department'));
        $sheet->getCell('B' . $tableHeaderSheet)->setValue(trans('messages.contacts_title'));
        $sheet->getCell('C' . $tableHeaderSheet)->setValue(trans('messages.table_displayName'));
        $sheet->getCell('D' . $tableHeaderSheet)->setValue(strip_tags(trans('messages.table_telephoneNumber')));
        $sheet->getCell('E' . $tableHeaderSheet)->setValue(strip_tags(trans('messages.ipPhone')));
        $sheet->getCell('F' . $tableHeaderSheet)->setValue(strip_tags(trans('messages.mobile')));
        $sheet->getCell('G' . $tableHeaderSheet)->setValue(trans('messages.table_mail_post'));
        $sheet->getCell('H' . $tableHeaderSheet)->setValue(trans('messages.table_physicalDeliveryOfficeName'));

        // Цвет текста заголовков таблицы
        $sheet->getStyle('A' . $tableHeaderSheet . ':H' . $tableHeaderSheet)->getFont()->getColor()->setRGB($tableheaderFontColor);
        // Высота и вертикальное выравнивание (по-центру) заголовков таблицы
        $sheet->getRowDimension($tableHeaderSheet)->setRowHeight(40);
        $sheet->getStyle('A' . $tableHeaderSheet . ':H' . $tableHeaderSheet)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        foreach ($departments as $key => $department) {

            // Номер первой ячейки
            if (!isset($startSheet) || empty($startSheet)) {
                $startSheet = $tableFirstRow;
            }

            // Вычислить кол-во строк для объединения (исходя из кол-ва контактов в отделе)
            $endSheet = $startSheet + $contactsModel->getCountContacts($company->name, $department->department);
            $startSheet = $startSheet + 1;

            // Объединить ячейки в столбце
            $sheet->mergeCells('A'. $startSheet . ':A' . $endSheet);
            // Записать название отдела
            $sheet->getCell('A'.$startSheet)->setValue($department->department);

            $sheet->getStyle('A'.$startSheet)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            // Счетчик номера ячейки
            $startSheet = $endSheet;

            // Получение списка контактов
            $contacts = $contactsModel->getContacts($company->name, $department->department);
            // Заполнение контактов
            foreach ($contacts as $key => $contact) {
                $sheet->getCell('B'.$tableContactsFirstRow)->setValue($contact->title);
                $sheet->getCell('C'.$tableContactsFirstRow)->setValue($contact->displayName);
                $sheet->getCell('D'.$tableContactsFirstRow)->setValue($contact->telephoneNumber);
                $sheet->getCell('E'.$tableContactsFirstRow)->setValue($contact->ipPhone);
                $sheet->getCell('F'.$tableContactsFirstRow)->setValue($contact->mobile);
                $sheet->getCell('G'.$tableContactsFirstRow)->setValue($contact->mail);
                $sheet->getCell('H'.$tableContactsFirstRow)->setValue($contact->physicalDeliveryOfficeName);

                // Выравнивание номеров телефонов и кабинетов по центру
                foreach ($centeredTableColumns as $centered) {
                    $sheet->getStyle($centered.$tableContactsFirstRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Увеличение счетчика
                $tableContactsFirstRow = $tableContactsFirstRow + 1;
            }
        }

        $sheet->getStyle('A' . $tableFirstRow . ':H' .  $tableContactsFirstRow)->applyFromArray(static::setTableBorders());

        return $spreadsheet;
    }

    /**
     * Возвращает полный путь хранения выгружаемого файла
     *
     * @param string имя выгружаемого файла
     * @return string
     */
    public static function getExportPath($filename)
    {
        return base_path() . '/' . static::$exportFolder . '/' . $filename . static::$excelExtension;
    }

    /**
     * Заголовки для файла Excel
     * @param type $slug
     */
    public static function setExcelHeaders($slug)
    {
        $company = Companies::where('slug', $slug)->firstOrFail();
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment; filename="'. date("d-m-Y-H-i-s") . '-' . $company->slug . static::$excelExtension . '"');
        header("Cache-Control: max-age=0");

    }

    /**
     * Заголовки для файла PDF
     * @param type $slug
     */
    public static function setPdfHeaders($slug)
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'. date("d-m-Y-H-i-s") . "-" . $slug . static::$pdfExtension . '"');
    }

    /**
     * Настройки шрифта по-умолчанию
     * @return array
     */
    protected static function getDefaultFontsSettings()
    {
        return [
            'font'  => [
               'bold'  => false,
               'color' => [
                   'rgb' => '000000'
                ],
               'size'  => 10,
               'name'  => 'Times New Roman'
            ]
        ];
    }

    /**
     * Выравнивание текста по-центру
     * @return array
     */
    protected static function setTextAlignCenter()
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
    }

    /**
     * Границы таблицы
     * @return array
     */
    protected static function setTableBorders()
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ]
        ];
    }
}
