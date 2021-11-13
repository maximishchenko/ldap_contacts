<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Contacts;
use App\Models\Companies;
use App\Models\Departments;
use App\Models\LdapUsers;
use App\Models\LdapSyncQueues;

/**
 * Осуществляет синхронизацию LDAP-каталога Active Directory c БД приложения
 */
class LdapSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ldap:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run user\'s data synchronization process with LDAP Active directory';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ldap = new LdapUsers();

        /**
         * Возвращает массив данных пользователей из LDAP-каталога Active Directory
         */
        $users = $ldap->getLdapUsers();

        /**
         * Возвращает массив objectGUID из LDAP-запроса
         */
        $ldapObjectGuidArray = $ldap->getObjectGuidsArray();

        /**
         * Возвращает массив objectGUID, хранящийся в БД
         */
        $dbObjectGuidArray = Contacts::getObjectGiudsArray();

        /**
         * Возвращает массив, содержащий уникальные значения аттрибута company из LDAP-запроса
         */
        $ldapCompaniesArray = $ldap->getCompaniesArray();

        /**
         * Возвращает массив, содержащий названия организаций из БД
         */
        $dbCompaniesArray = Companies::getNamesArray();

        /**
         * Возвращает массив, содержащий уникальные значения аттрибута department из LDAP-запроса
         */
        $ldapDepartmentsArray = $ldap->getDepartmentsArray();

        /**
         * Возвращает массив, содержащий названия структурных подразделений из БД
         */
        $dbDepartmentsArray = Departments::getNamesArray();

        /**
         * Обработка записей, полученных из LDAP
         * Добавление/обновление записей
         * Отправка данных в очередь
         */
        LdapSyncQueues::syncUsers($users, $dbObjectGuidArray);

        /**
         * Обработка хранящихся в БД записй
         * Удаление неактуальных (objectGUID, которых отсутствует в LDAP)
         * Отправка данных в очередь
         */
        LdapSyncQueues::dropMissingContacts($dbObjectGuidArray, $ldapObjectGuidArray);

        /**
         * Обработка записей, полученных из LDAP
         * Добавление записей в таблицу, содержащую данные организаций
         * Отправка данных в очередь
         */
        LdapSyncQueues::syncCompanies($ldapCompaniesArray, $dbCompaniesArray);

        /**
         * Обработка хранящихся в БД записей
         * Удаление неактуальных (отсутствующих в LDAP)
         * Отправка данных в очередь
         */
        LdapSyncQueues::dropMissingCompanies($dbCompaniesArray, $ldapCompaniesArray);

        /**
         * Обработка записей, полученных из LDAP
         * Добавление записей в таблицу, содержащую данные структурных подразделений
         * Отправка данных в очередь
         */
        LdapSyncQueues::syncDepartments($ldapDepartmentsArray, $dbDepartmentsArray);

        /**
         * Обработка хранящихся в БД записей
         * Удаление неактуальных (отсутствующих в LDAP)
         * Отправка данных в очередь
         */
        LdapSyncQueues::dropMissingDepartments($dbDepartmentsArray, $ldapDepartmentsArray);
    }
}
