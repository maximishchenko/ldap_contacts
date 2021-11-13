#### Установка

- Клонировать репозиторий
```
git clone maximishchenko/ldap_contacts
```

- Установить зависимости
```
composer install
```

- Указать параметры подключения к СУБД и серверу LDAP в файле .env

- Для формирования структуры БД выполнить:
```
php artisan migrate
php artisan db:seed --class=SettingsSeeder
```

- Авторизоваться в панели управления под пользователем Active Directory
Заполнить параметры подключения к LDAP-каталогу в разделе "Настройки"

- Выполнить первичную синхронизацию
```
php artisan ldap:sync
```

- Указать значения сортировки в разделах (Организации, Подразделения и Контакты). 

- Заполнить справочные данные организаций

- Задания планировщика
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

#### Редактирование файлов js и css производится в каталоге resources, затем необходимо запустить npm run dev|prod (в зависимости от среды развертывания, необходимо установить nodejs, yarn)

#### Сопоставление полей таблицы БД с полями LDAP-каталога Active Directory

| Название аттрибута | Название поля в БД | Название поля в LDAP | Название аттрибута Active Directory |
|-------------|----------|---------|---------|
| LDAP ID | sid | objectGUID | - (вручную не заполняется)
| Ф.И.О. | displayName | displayName | Общие/Выводимое имя |
| Email | mail | mail | Общие/Эл.почта |
| Тел. (гор.) | telephoneNumber | telephoneNumber | Общие/Номер телефона (гор.) |
| Тел. (внутр.) | ipPhone | ipPhone | Общие/Номер телефона (внутр.) |
| Тел. (дом.) | homePhone | homePhone | Телефонные номера/домашний |
| Тел. (моб.) | mobilePhone | mobile | Телефонные номера/моб. |
| Организация | company | company | Организация/Организация |
| Подразделение | department | department | Организация/Отдел |
| Должность | title | title | Организация/Должность |
| Дата последнего изменения | whenChanged | whenChanged | - (вручную не заполняется) |
| № комнаты | physicalDeliveryOfficeName | physicalDeliveryOfficeName | Общие/Комната |
| Сортировка | sort | - | - |
| Статус | status | - | - |
