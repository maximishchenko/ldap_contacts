<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Contacts;
use App\Models\Share;

/**
 * Загружает в БД контактыиз LDAP-каталога Active Directory
 */
class LoadContactsFromLdap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Массив данных контакта из LDAP
     * @var array
     */
    protected $contact;

    /**
     * Массив sid, хранимый бд
     * @var array
     */
    protected $dbSidArray;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $contact, array $dbSidArray)
    {
        $this->contact = $contact;
        $this->dbSidArray = $dbSidArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Проверка корректности переданного массива данных контакта
         * по наличию ключа sid
         */
        if ($this->isObjectGuidKeyExist()) {
            /**
             * Проверка существования значения sid в БД
             */
            \Log::info('Проверка наличия LDAP ObjectGUID в БД');
            if ($this->isObjectGuidExist()) {
                /**
                 * Обновление данных контакта
                 */
                \Log::info('Создание нового контакта');
                $this->updateContact();
            } else {
                /**
                 * Добавление нового контакта
                 */
                \Log::info('Обновление данных контакта');
                $this->createContact();
            }
        }
    }

    /**
     * Проверяет наличие ключа, содержащего LDAP ObjectGUID, в переданном массиве данных контакта
     * @return boolean
     */
    protected function isObjectGuidKeyExist()
    {
        return array_key_exists('sid', $this->contact);
    }

    /**
     * Проверяет наличие в БД записи с текущим LDAP objectGuid
     * @return boolean
     */
    protected function isObjectGuidExist()
    {
        return (in_array($this->contact['sid'], $this->dbSidArray)) ? true : false;
    }


    /**
     * Добавляет в БД новый контакт
     * @return mixed
     */
    protected function createContact()
    {
        $contact = Contacts::create([
            'sid' => $this->contact['sid'],
            'displayName' => $this->contact['displayName'],
            'mail' => $this->contact['mail'],
            'telephoneNumber' => $this->contact['telephoneNumber'],
            'homePhone' => $this->contact['homePhone'],
            'ipPhone' => $this->contact['ipPhone'],
            'mobile' => $this->contact['mobile'],
            'company' => $this->contact['company'],
            'department' => $this->contact['department'],
            'title' => $this->contact['title'],
            'physicalDeliveryOfficeName' => $this->contact['physicalDeliveryOfficeName'],
            'distinguishedName' => $this->contact['distinguishedName'],
            'manager' => $this->contact['manager'],
            'whenChanged' => $this->contact['whenChanged'],
            'status' => 1,
        ]);
        return $contact;
    }

    /**
     * Обновляет данные контакта
     * Находит запись по значению sid
     * Сравнивает значение whenChanged cо значением из LDAP
     * Если время изменения в LDAP больше чем в БД - обновить поля в БД
     * @return mixed
     */
    protected function updateContact()
    {
        $ldapObjectGuid = $this->contact['sid'];
        $ldapWhenChanged = $this->contact['whenChanged'];
        $dbContact = Contacts::findContactByObjectGuid($ldapObjectGuid);
        if (Share::isRecordChanged($dbContact->whenChanged, $ldapWhenChanged))
        {
            $contact = $dbContact->update([
                'displayName' => $this->contact['displayName'],
                'mail' => $this->contact['mail'],
                'telephoneNumber' => $this->contact['telephoneNumber'],
                'homePhone' => $this->contact['homePhone'],
                'ipPhone' => $this->contact['ipPhone'],
                'mobile' => $this->contact['mobile'],
                'company' => $this->contact['company'],
                'department' => $this->contact['department'],
                'title' => $this->contact['title'],
                'physicalDeliveryOfficeName' => $this->contact['physicalDeliveryOfficeName'],
                'distinguishedName' => $this->contact['distinguishedName'],
                'manager' => $this->contact['manager'],
                'whenChanged' => $this->contact['whenChanged'],
            ]);
            return $contact;
        }
    }
}
