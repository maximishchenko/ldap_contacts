<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\Settings\app\Models\Setting;

/**
 * Осуществляет операции поиска пользователей в LDAP-каталоге Active Directory
 */
class LdapUsers extends Model
{
    /**
     * Количество записей, возвращаемое в рамках выполнения одного запроса к ldap
     * @var int
     */
    protected $ldapQueryLimit;

    public function __construct(array $attributes = array()) {
        $this->ldapUsers = $this->setLdapUsers();

        return parent::__construct($attributes);
    }

    /**
     * Возвращает массив данных пользователей из Active Directory
     * @access public
     * @return array
     */
    public function getLdapUsers()
    {
        return $this->ldapUsers;
    }

    /**
     * Возвращает массив, содержащий уникальные значения department из LDAP-запроса
     * @return array
     */
    public function getDepartmentsArray()
    {
        $departmentsArray = array_column($this->ldapUsers, 'department');
        $departmentsUniqueArray = array_unique($departmentsArray);
        return array_filter($departmentsUniqueArray);
    }

    /**
     * Возвращает массив, содержащий objectGUID из LDAP-запроса
     * @access public
     * @return array
     */
    public function getObjectGuidsArray()
    {
        return array_column($this->ldapUsers, "sid");
    }

    /**
     * Возвращает массив, содержащий уникальные значения company из LDAP-запроса
     * @return array
     */
    public function getCompaniesArray()
    {
        $companiesArray = array_column($this->ldapUsers, 'company');
        $companiesUniqueArray = array_unique($companiesArray);
        return array_filter($companiesUniqueArray);
    }

    /**
     * Cоздает массив данных пользователей из Active Directory
     * @access protected
     * @return array
     */
    protected function setLdapUsers()
    {
        $users = [];
        $ldapUsers = $this->getUsersFromLdap();

        foreach ($ldapUsers as $key => $user) {
            $users[$key]['sid'] = $user->getConvertedSid();
            $users[$key]['displayName'] = $this->getArrayZeroValueOrEmpty($user->displayName);
            $users[$key]['mail'] = $this->getArrayZeroValueOrEmpty($user->mail);
            $users[$key]['telephoneNumber'] = $this->getArrayZeroValueOrEmpty($user->telephoneNumber);
            $users[$key]['homePhone'] = $this->getArrayZeroValueOrEmpty($user->homePhone);
            $users[$key]['ipPhone'] = $this->getArrayZeroValueOrEmpty($user->ipPhone);
            $users[$key]['mobile'] = $this->getArrayZeroValueOrEmpty($user->mobile);
            $users[$key]['company'] = $this->getArrayZeroValueOrEmpty($user->company);
            $users[$key]['department'] = $this->getArrayZeroValueOrEmpty($user->department);
            $users[$key]['title'] = $this->getArrayZeroValueOrEmpty($user->title);
            $users[$key]['physicalDeliveryOfficeName'] = $this->getArrayZeroValueOrEmpty($user->physicalDeliveryOfficeName);
            $users[$key]['whenChanged'] = $user->getUpdatedAtTimestamp();
        }
        return $users;
    }

    /**
     * Совершает запрос к LDAP-каталогу Active Directory
     * Выполняет поиск активных пользователей в указанном в настройках подразделении
     * @access protected
     * @return mixed
     */
    protected function getUsersFromLdap()
    {
        $ad = new \Adldap\Adldap();

        $config = [
            'hosts'    => [
                Setting::get('LDAP_HOSTS')
            ],
            'base_dn'  => Setting::get('LDAP_BASE_DN'),
            'username' => Setting::get('LDAP_USERNAME'),
            'password' => Setting::get('LDAP_PASSWORD'),
        ];
        $ad->addProvider($config);
        try {
            $provider = $ad->connect();
            $users = $provider->search()
                    ->users()
                    ->in(Setting::get('LDAP_SEARCH_ROOT'))
                    ->where('userAccountControl:1.2.840.113556.1.4.803:', '!=', '2')
                    ->limit(Setting::get('LDAP_SEARCH_LIMIT'))
					->paginate();
                    //->get();
            return $users;
        } catch (\Adldap\Auth\BindException $e) {
//            dd($e);
        }
    }

    /**
     * Возвращает значение массива с индексом 0
     * Если индекс отсутствует, возвращает null
     * @access protected
     * @param array $attribute
     * @return string|null
     */
    protected function getArrayZeroValueOrEmpty($attribute) {
        if (isset($attribute[0]) && !empty($attribute[0])) {
            return $attribute[0];
        }
        return null;
    }
}
