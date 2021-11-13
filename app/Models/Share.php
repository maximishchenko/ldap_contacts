<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Содержит значения, общие для всех методов
 */
class Share extends Model
{
    /**
     * Значение сортировки по-умолчанию
     */
    const DEFAULT_SORT = 500;

    const STATUS_ACTIVE = 1;

    const STATUS_BLOCKED = 0;

    /**
     * Проверяет была ли изменена запись
     * Сравнивает поле whenChanged БД с полем whenChanged данных контакта из LDAP
     * @param integer $localTimestamp аттрибут времени последнего изменения в LDAP, хранимый в БД (в формате unix timestamp)
     * @param integer $remoteTimestamp аттрибут времени последнего изменения в LDAP (в формате unix timestamp)
     * @return boolean
     */
    public static function isRecordChanged($localTimestamp, $remoteTimestamp)
    {
        return ((int)$remoteTimestamp > (int)$localTimestamp) ? true : false;
    }
}
