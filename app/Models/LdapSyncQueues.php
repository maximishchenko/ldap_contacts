<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\LoadContactsFromLdap;
use App\Jobs\DropMissingContactsFromDb;
use App\Jobs\LoadCompaniesFromLdap;
use App\Jobs\DropMissingCompaniesFromDb;
use App\Jobs\LoadDepartmentsFromLdap;
use App\Jobs\DropMissingDepartmentsFromDb;

/**
 * Обрабатывает входные данные
 * Передает на обработку соответствующим очередям
 */
class LdapSyncQueues extends Model
{
    /**
     * Название очереди для загрузки контактов
     */
    const LOAD_CONTACTS_QUEUE = 'loadContacts';

    /**
     * Название очереди для очистки несуществующих контактов
     */
    const DROP_CONTACTS_QUEUE = 'dropContacts';

    /**
     * Название очереди для загрузки организаций
     */
    const LOAD_COMPANIES_QUEUE = 'loadCompanies';

    /**
     * Название очереди для очистки несуществующих организаций
     */
    const DROP_COMPANIES_QUEUE = 'dropCompanies';

    /**
     * Название очереди для загрузки отделов
     */
    const LOAD_DEPARTMENTS_QUEUE = 'loadDepartments';

    /**
     * Название очереди для очистки несуществующих отделов
     */
    const DROP_DEPARTMENTS_QUEUE = 'dropDepartments';

    /**
     * Отправляет данные пользователя в очередь синхронизации контактов
     * @param array $users
     * @param array $dbObjectGuidArray
     */
    public static function syncUsers(array $users, array $dbObjectGuidArray)
    {
        foreach ($users as $user) {
            $job = (new LoadContactsFromLdap($user, $dbObjectGuidArray))->onQueue(static::LOAD_CONTACTS_QUEUE);
            dispatch($job);
        }
    }

    /**
     * Сверяет список контактов со списком, полученным из LDAP-каталога Active Directory
     * Удаляет отсутствующие контакты
     * @param array $dbObjectGuidArray
     * @param array $ldapObjectGuidArray
     */
    public static function dropMissingContacts(array $dbObjectGuidArray, array $ldapObjectGuidArray)
    {
        foreach ($dbObjectGuidArray as $dbObjectGuid) {
            $job = (new DropMissingContactsFromDb($dbObjectGuid, $ldapObjectGuidArray))->onQueue(static::DROP_CONTACTS_QUEUE);
            dispatch($job);
        }
    }

    /**
     * Производит загрузку в БД организаций, полученных из LDAP-запроса
     * @param array $ldapCompaniesArray
     * @param array $dbCompaniesArray
     */
    public static function syncCompanies(array $ldapCompaniesArray, array $dbCompaniesArray)
    {
        foreach ($ldapCompaniesArray as $company) {
            $job = (new LoadCompaniesFromLdap($company, $dbCompaniesArray))->onQueue(static::LOAD_COMPANIES_QUEUE);
            dispatch($job);
        }
    }

    /**
     * Сверяет список организаций со списком, полученным из LDAP-каталога Active Directory
     * Удаляет отсутствующие организации
     * @param array $dbCompaniesArray
     * @param array $ldapCompaniesArray
     */
    public static function dropMissingCompanies(array $dbCompaniesArray, array $ldapCompaniesArray)
    {
        foreach ($dbCompaniesArray as $company)
        {
            $job = (new DropMissingCompaniesFromDb($company, $ldapCompaniesArray))->onQueue(static::DROP_COMPANIES_QUEUE);
            dispatch($job);
        }
    }

    /**
     * Производит загрузку в БД отделов, полученных из LDAP-запроса
     * @param array $ldapDepartmentsArray
     * @param array $dbDepartmentsArray
     */
    public static function syncDepartments(array $ldapDepartmentsArray, array $dbDepartmentsArray)
    {
        foreach ($ldapDepartmentsArray as $department)
        {
            $job = (new LoadDepartmentsFromLdap($department, $dbDepartmentsArray))->onQueue(static::LOAD_DEPARTMENTS_QUEUE);
            dispatch($job);
        }
    }

    /**
     * Сверяет список отделов со списком, полученным из LDAP-каталога Active Directory
     * Удаляет отсутствующие отделы
     * @param array $dbDepartmentsArray
     * @param array $ldapDepartmentsArray
     */
    public static function dropMissingDepartments(array $dbDepartmentsArray, array $ldapDepartmentsArray)
    {
        foreach ($dbDepartmentsArray as $department)
        {
            $job = (new DropMissingDepartmentsFromDb($department, $ldapDepartmentsArray))->onQueue(static::DROP_DEPARTMENTS_QUEUE);
            dispatch($job);
        }
    }
}
