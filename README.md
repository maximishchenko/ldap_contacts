Клонировать репозиторий
git clone ......

Установить зависимости
composer install

Указать параметры подключения к СУБД и серверу LDAP в файле .env

выполнить:
php artisan migrate
php artisan db:seed --class=SettingsSeeder

Авторизоваться в панели управления
Заполнить параметры подключения к LDAP-каталогу в разделе "Настройки"

Выполнить первичную синхронизацию
php artisan ldap:sync

Указать значения сортировки в разделах (Организации, Подразделения и Контакты)

Задания планировщика
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

Редактирование файлов js и css производится в каталоге resources, затем необходимо запустить npm run dev|prod (в зависимости от среды развертывания, необходимо установить nodejs, yarn)

Сопоставление полей таблицы БД с полями LDAP-каталога Active Directory

Название аттрибута          Название поля в БД              Название поля в LDAP            Название аттрибута Active Directory
LDAP ID                     sid                             objectGUID                      - (вручную не заполняется)
Ф.И.О.                      displayName                     displayName                     Общие/Выводимое имя
Email                       mail                            mail                            Общие/Эл.почта
Тел. (внутр.)               telephoneNumber                 telephoneNumber|ipPhone         Общие/Номер телефона
Тел. (гор.)                 homePhone                       homePhone                       Телефонные номера/домашний
Тел. (моб.)                 mobilePhone                     mobile                          Телефонные номера/домашний
Организация                 company                         company                         Организация/Организация
Подразделение               department                      department                      Организация/Отдел
Должность                   title                           title                           Организация/Должность
Дата последнего изменения   whenChanged                     whenChanged                     - (вручную не заполняется)
№ комнаты                  physicalDeliveryOfficeName      physicalDeliveryOfficeName      Общие/Комната
Сортировка                  sort                            -                               -
Статус                      status                          -                               -
