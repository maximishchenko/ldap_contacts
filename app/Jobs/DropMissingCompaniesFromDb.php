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
 * Удаляет организации, присутствующие в БД, но отсутствующие в LDAP
 */
class DropMissingCompaniesFromDb implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Название организации, полученное из БД
     * @var string
     */
    protected $company;

    /**
     * Массив уникальных значений организаций, полученыых из LDAP-запроса
     * @var array
     */
    protected $ldapCompaniesArray;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $company, array $ldapCompaniesArray)
    {
        $this->company = $company;
        $this->ldapCompaniesArray = $ldapCompaniesArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Удаляет из БД организацию, в случае ее отсутствия в результате LDAP-запроса
         */
        if (!in_array($this->company, $this->ldapCompaniesArray))
        {
            Companies::dropCompanyByName($this->company);
        }
    }
}
