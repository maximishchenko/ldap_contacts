<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{

    protected $settings = [
        [
            'key'         => 'ENABLE_AUTOCOMPLETE',
            'name'        => 'ENABLE_AUTOCOMPLETE',
            'description' => 'Активировать функционал автодополнения поиска',
            'value'       => 1,
            'field'       => '{"name":"value","label":"Активировать функционал автодополнения поиска","type":"checkbox"}',
            'active'      => 1,
        ],
        [
            'key'         => 'ENABLE_LOADING_ANIMATION',
            'name'        => 'ENABLE_LOADING_ANIMATION',
            'description' => 'Активировать функционал анимации загрузки',
            'value'       => 1,
            'field'       => '{"name":"value","label":"Активировать функционал анимации загрузки","type":"checkbox"}',
            'active'      => 1,
        ],
        [
            'key'         => 'ENABLE_SUBSCRIPTION',
            'name'        => 'ENABLE_SUBSCRIPTION',
            'description' => 'Активировать функционал подписки на рассылки',
            'value'       => 1,
            'field'       => '{"name":"value","label":"Активировать функционал подписки на рассылки","type":"checkbox"}',
            'active'      => 1,
        ],
        [
            'key'         => 'SMTP_PASSWORD',
            'name'        => 'SMTP_PASSWORD',
            'description' => 'Пароль для подключения к почтовому серверу',
            'value'       => 'This is very secure password',
            'field'       => '{"name":"value","label":"Пароль для подключения к почтовому серверу","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'SMTP_USERNAME',
            'name'        => 'SMTP_USERNAME',
            'description' => 'Имя пользователя для подключения к почтовому серверу',
            'value'       => 'username@mail.corp.acme.org',
            'field'       => '{"name":"value","label":"Имя пользователя для подключения к почтовому серверу","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'SMTP_ENCRYPTION',
            'name'        => 'SMTP_ENCRYPTION',
            'description' => 'Шифрование, использование для подключения к почтовому серверу',
            'value'       => 'ssl',
            'field'       => '{"name":"value","label":"Шифрование, использование для подключения к почтовому серверу (false, ssl, tls)","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'SMTP_PORT',
            'name'        => 'SMTP_PORT',
            'description' => 'Порт для подключения к почтовому серверу',
            'value'       => '465',
            'field'       => '{"name":"value","label":"Порт для подключения к почтовому серверу (25, 587, 465)","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'SMTP_HOST',
            'name'        => 'SMTP_HOST',
            'description' => 'IP-адрес или FQDN-имя почтового сервера',
            'value'       => 'mail.corp.acme.org',
            'field'       => '{"name":"value","label":"IP-адрес или FQDN-имя почтового сервера","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'LDAP_TIMEOUT',
            'name'        => 'LDAP_TIMEOUT',
            'description' => 'Таймаут подключения к серверу LDAP',
            'value'       => 5,
            'field'       => '{"name":"value","label":"Таймаут подключения к серверу LDAP","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'LDAP_SEARCH_LIMIT',
            'name'        => 'LDAP_SEARCH_LIMIT',
            'description' => 'Лимит поиска в LDAP',
            'value'       => 10000,
            'field'       => '{"name":"value","label":"Лимит поиска в LDAP","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'LDAP_PASSWORD',
            'name'        => 'LDAP_PASSWORD',
            'description' => 'Пароль пользователя для подключения к контроллеру домена',
            'value'       => 'This is very secure password',
            'field'       => '{"name":"value","label":"Пароль пользователя для подключения к контроллеру домена","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'LDAP_USERNAME',
            'name'        => 'LDAP_USERNAME',
            'description' => 'Имя пользователя для подключения к контроллеру домена',
            'value'       => 'username@corp.acme.org',
            'field'       => '{"name":"value","label":"Имя пользователя для подключения к контроллеру домена","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'LDAP_SEARCH_ROOT',
            'name'        => 'LDAP_SEARCH_ROOT',
            'description' => 'Подразделение для поиска в LDAP',
            'value'       => "OU=Users,DC=corp,DC=acme,DC=org",
            'field'       => '{"name":"value","label":"Подразделение для поиска в LDAP","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'LDAP_BASE_DN',
            'name'        => 'LDAP_BASE_DN',
            'description' => 'Корневой элемент каталога LDAP',
            'value'       => 'DC=corp,DC=acme,DC=org',
            'field'       => '{"name":"value","label":"Корневой элемент каталога LDAP","type":"text"}',
            'active'      => 1,
        ],
        [
            'key'         => 'LDAP_HOSTS',
            'name'        => 'LDAP_HOSTS',
            'description' => 'IP-адрес или FQDN-имя контроллера домена',
            'value'       => 'corp.acme.org',
            'field'       => '{"name":"value","label":"IP-адрес или FQDN-имя контроллера домена","type":"text"}',
            'active'      => 1,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $index => $setting) {
            $result = DB::table('settings')->insert($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }

        $this->command->info('Inserted '.count($this->settings).' records.');
    }
}
