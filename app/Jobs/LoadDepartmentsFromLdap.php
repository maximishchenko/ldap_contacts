<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Departments;

/**
 * Загружает в БД структурные подразделения из LDAP-каталога Active Directory
 */
class LoadDepartmentsFromLdap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     *
     * @var string
     */
    protected $department;

    /**
     *
     * @var array
     */
    protected $dbDepartmentsArray;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($department, array $dbDepartmentsArray)
    {
        $this->department = $department;
        $this->dbDepartmentsArray = $dbDepartmentsArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Проверяет наличие структурного подразделения в БД
         * В случае отсутствия - добавляет
         */
        if (!in_array($this->department, $this->dbDepartmentsArray))
        {
            Departments::createDepartment($this->department);
        }
    }
}
