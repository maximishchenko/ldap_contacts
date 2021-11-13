<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Companies;

/**
 * Загружает в БД организации из LDAP-каталога Active Directory
 */
class LoadCompaniesFromLdap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Наименование организации
     * @var string
     */
    protected $company;

    /**
     * Массив организаций из БД
     * @var array
     */
    protected $dbCompaniesArray;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $company, array $dbCompaniesArray)
    {
        $this->company = $company;
        $this->dbCompaniesArray = $dbCompaniesArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Проверяет наличие организации в БД
         * В случае отсутствия - добавляет
         */
        if (!in_array($this->company, $this->dbCompaniesArray))
        {
            Companies::createCompany($this->company);
        }
    }
}
