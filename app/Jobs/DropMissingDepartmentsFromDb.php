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
 * Сверяет список подразделений, хранящийся в БД
 * с подразделениями полученными из LDAP-каталога Active Directory
 * Удаляет из БД подразделение в случае его отсутствия в Active Directory
 */
class DropMissingDepartmentsFromDb implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Название отдела, полученное из БД
     * @var string
     */
    protected $department;

    /**
     * Массив уникальных значений отделов, полученыых из LDAP-запроса
     * @var array
     */
    protected $ldapDepartmentsArray;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $department, array $ldapDepartmentsArray)
    {
        $this->department = $department;
        $this->ldapDepartmentsArray = $ldapDepartmentsArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Удаляет из БД отдел, в случае его отсутствия в результате LDAP-запроса
         */
        if (!in_array($this->department, $this->ldapDepartmentsArray))
        {
            Departments::dropDepartmentByName($this->department);
        }
    }
}
