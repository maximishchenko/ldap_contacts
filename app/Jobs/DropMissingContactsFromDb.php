<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Contacts;

/**
 * Удаляет контакты присутствующие в БД, но отсутствующие в LDAP
 */
class DropMissingContactsFromDb implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * значение LDAP ObjectGUID контакта, хранимое в БД
     * @var string
     */
    protected $dbObjectGuid;

    /**
     * Массив ObjectGUID, полученный из LDAP
     * @var array
     */
    protected $ldapObjectGuidsArray;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dbObjectGuid, $ldapObjectGuidsArray)
    {
        $this->dbObjectGuid = $dbObjectGuid;
        $this->ldapObjectGuidsArray = $ldapObjectGuidsArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Удаляет из БД контакт, в случае его отсутствия в результате LDAP-запроса
         */
        if (!in_array($this->dbObjectGuid, $this->ldapObjectGuidsArray)) {
            $this->dropContact();
        }
    }

    /**
     * Удаляет контакт из справочника
     * @return mixed
     */
    protected function dropContact()
    {
        Contacts::where('sid', $this->dbObjectGuid)->delete();
    }
}
