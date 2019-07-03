<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Russian language file
**      To translate into another language, copy this file and
**          translate each variable into your chosen language.
**          Leave any variable not translated so that the system will have
**          something to display.
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net>
** Joel Esler <joelesler@users.sourceforge.net>
** Russian translation by: Dmitry Purgin <dpurgin@hotmail.kz>
** Русский перевод: Дмитрий Пургин <dpurgin@hotmail.kz>
********************************************************************************
*/

$UI_Spacing = 1; // Inter Character Spacing.
$UI_ILC = 'ru'; // ISO 639-1 Language Code.
$UI_IRC = ''; // Region Code.
// Locales.
$UI_Locales = array( 'eng_ENG.ISO8859-1', 'eng_ENG.utf-8', 'english' );
// Time Format - See strftime() syntax.
$UI_Timefmt = '%a %B %d, %Y %H:%M:%S';
// UI Init.
$UI_Charset = 'UTF-8';
$UI_Title = 'Базовый движок анализа и безопасности';
// Common Words.
$UI_CW_Edit = 'Редактировать';
$UI_CW_Delete = 'Удалить';
$UI_CW_Src = 'Источника';
$UI_CW_Dst = 'назначения';
$UI_CW_Id = 'ID';
$UI_CW_Name = 'Имя';
$UI_CW_Int = 'Интерфейс';
$UI_CW_Filter = 'Фильтр';
$UI_CW_Desc = 'Описание';
$UI_CW_SucDesc = 'Успешно';
$UI_CW_Sensor = 'Сенсор';
$UI_CW_Sig = 'Сигнатура';
$UI_CW_Role = 'роли';
$UI_CW_Pw = 'Пароль';
$UI_CW_Ts = 'Время';
$UI_CW_Addr = 'Адрес';
$UI_CW_Layer = 'Слой';
$UI_CW_Proto = 'Протокол';
$UI_CW_Pri = 'Приоритет';
$UI_CW_Event = 'события';
$UI_CW_Type = 'тип';
$UI_CW_ML1 = 'Январь';
$UI_CW_ML2 = 'Февраль';
$UI_CW_ML3 = 'Март';
$UI_CW_ML4 = 'Апрель';
$UI_CW_ML5 = 'Май';
$UI_CW_ML6 = 'Июнь';
$UI_CW_ML7 = 'Июль';
$UI_CW_ML8 = 'Август';
$UI_CW_ML9 = 'Сентябрь';
$UI_CW_ML10 = 'Октябрь';
$UI_CW_ML11 = 'Ноябрь';
$UI_CW_ML12 = 'Декабрь';
// Common Phrases.
$UI_CP_SrcName = array($UI_CW_Name,$UI_CW_Src);
$UI_CP_DstName = array($UI_CW_Name,$UI_CW_Dst);
$UI_CP_SrcDst = array($UI_CW_Src,'или',$UI_CW_Dst);
$UI_CP_SrcAddr = array($UI_CW_Addr,$UI_CW_Src);
$UI_CP_DstAddr = array($UI_CW_Addr,$UI_CW_Dst);
$UI_CP_L4P = array($UI_CW_Layer,'4',$UI_CW_Proto);
$UI_CP_ET = array($UI_CW_Type,$UI_CW_Event);
// Authentication Data.
$UI_AD_UND = 'Логин';
$UI_AD_RID = array($UI_CW_Id,$UI_CW_Role);
$UI_AD_ASD = 'Включено';

// основные фразы
DEFINE('_LAST','Последний');
DEFINE('_FIRST','First'); //NEW
DEFINE('_TOTAL','Total'); //NEW
DEFINE('_ALERT','Предупреждения');
DEFINE('_ADDRESS',$UI_CW_Addr);
DEFINE('_UNKNOWN','неизвестно');
DEFINE('_AND','AND'); //NEW
DEFINE('_OR','OR'); //NEW
DEFINE('_IS','is'); //NEW
DEFINE('_ON','on'); //NEW
DEFINE('_IN','in'); //NEW
DEFINE('_ANY','any'); //NEW
DEFINE('_NONE','none'); //NEW
DEFINE('_HOUR','Hour'); //NEW
DEFINE('_DAY','Day'); //NEW
DEFINE('_MONTH','Month'); //NEW
DEFINE('_YEAR','Year'); //NEW
DEFINE('_ALERTGROUP','Alert Group'); //NEW
DEFINE('_ALERTTIME','Alert Time'); //NEW
DEFINE('_CONTAINS','contains'); //NEW
DEFINE('_DOESNTCONTAIN','does not contain'); //NEW
DEFINE('_SOURCEPORT','source port'); //NEW
DEFINE('_DESTPORT','dest port'); //NEW
DEFINE('_HAS','has'); //NEW
DEFINE('_HASNOT','has not'); //NEW
DEFINE('_PORT','Port'); //NEW
DEFINE('_FLAGS','Flags'); //NEW
DEFINE('_MISC','Misc'); //NEW
DEFINE('_BACK','Back'); //NEW
DEFINE('_DISPYEAR','{ year }'); //NEW
DEFINE('_DISPMONTH','{ month }'); //NEW
DEFINE('_DISPHOUR','{ hour }'); //NEW
DEFINE('_DISPDAY','{ day }'); //NEW
DEFINE('_DISPTIME','{ time }'); //NEW
DEFINE('_ADDADDRESS','ADD Addr'); //NEW
DEFINE('_ADDIPFIELD','ADD IP Field'); //NEW
DEFINE('_ADDTIME','ADD TIME'); //NEW
DEFINE('_ADDTCPPORT','ADD TCP Port'); //NEW
DEFINE('_ADDTCPFIELD','ADD TCP Field'); //NEW
DEFINE('_ADDUDPPORT','ADD UDP Port'); //NEW
DEFINE('_ADDUDPFIELD','ADD UDP Field'); //NEW
DEFINE('_ADDICMPFIELD','ADD ICMP Field'); //NEW
DEFINE('_ADDPAYLOAD','ADD Payload'); //NEW
DEFINE('_MOSTFREQALERTS','Most Frequent Alerts'); //NEW
DEFINE('_MOSTFREQPORTS','Most Frequent Ports'); //NEW
DEFINE('_MOSTFREQADDRS','Most Frequent IP addresses'); //NEW
DEFINE('_LASTALERTS','Last Alerts'); //NEW
DEFINE('_LASTPORTS','Last Ports'); //NEW
DEFINE('_LASTTCP','Last TCP Alerts'); //NEW
DEFINE('_LASTUDP','Last UDP Alerts'); //NEW
DEFINE('_LASTICMP','Last ICMP Alerts'); //NEW
DEFINE('_QUERYDB','Query DB'); //NEW
DEFINE('_QUERYDBP','Query+DB'); //NEW - Equals to _QUERYDB where spaces are '+'s. 
                                //Should be something like: DEFINE('_QUERYDBP',str_replace(" ", "+", _QUERYDB));
DEFINE('_SELECTED','Selected'); //NEW
DEFINE('_ALLONSCREEN','ALL on Screen'); //NEW
DEFINE('_ENTIREQUERY','Entire Query'); //NEW
DEFINE('_OPTIONS','Options'); //NEW
DEFINE('_LENGTH','length'); //NEW
DEFINE('_CODE','code'); //NEW
DEFINE('_DATA','data'); //NEW
DEFINE('_TYPE','type'); //NEW
DEFINE('_NEXT','Next'); //NEW
DEFINE('_PREVIOUS','Previous'); //NEW

//пункты меню
DEFINE('_HOME','Домой');
DEFINE('_SEARCH','Поиск');
DEFINE('_AGMAINT','Поддержка Групп Предупреждений');
DEFINE('_USERPREF','Установки пользователя');
DEFINE('_CACHE','Кэш и Статус');
DEFINE('_ADMIN','Администрирование');
DEFINE('_GALERTD','График данных предупреждений');
DEFINE('_GALERTDT','График времени определения предупреждений');
DEFINE('_USERMAN','Управление пользователями');
DEFINE('_LISTU','Список пользователей');
DEFINE('_CREATEU','Создать пользователя');
DEFINE('_ROLEMAN','Управление ролями');
DEFINE('_LISTR','Список ролей');
DEFINE('_CREATER','Создать роль');
DEFINE('_LISTALL','Весь список');
DEFINE('_CREATE','Создать');
DEFINE('_VIEW','Просмотреть');
DEFINE('_CLEAR','Очистить');
DEFINE('_LISTGROUPS','Список групп');
DEFINE('_CREATEGROUPS','Создать группу');
DEFINE('_VIEWGROUPS','Просмотреть группу');
DEFINE('_EDITGROUPS',"$UI_CW_Edit группу");
DEFINE('_DELETEGROUPS',"$UI_CW_Delete группу");
DEFINE('_CLEARGROUPS','Очистить группу');
DEFINE('_CHNGPWD',"Поменять $UI_CW_Pw");
DEFINE('_DISPLAYU','Показать пользователя');

//base_footer.php
DEFINE('_FOOTER','( by <A class="largemenuitem" href="mailto:base@secureideas.net">Кевин Джонсон (Kevin Johnson)</A> и комманда проекта <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE</A><BR>Основано на ACID Романа Данылива (Roman Danyliw)');

//index.php --страница входа в систему
DEFINE('_LOGINERROR',"Пользователь не существует или Ваш $UI_CW_Pw неверный!<br>Пожалуйста, попытайтесь еще раз");

// base_main.php
DEFINE('_MOSTRECENT','Самые последние ');
DEFINE('_MOSTFREQUENT','Самые частые ');
DEFINE('_ALERTS',' Предупреждения:');
DEFINE('_ADDRESSES',' '.$UI_CW_Addr.'а');
DEFINE('_ANYPROTO','любой протокол');
DEFINE('_UNI','уникальный');
DEFINE('_LISTING','листинг');
DEFINE('_TALERTS','Сегодняшние предупреждения: ');
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_L24ALERTS','Предупреждения за последние 24 часа: ');
DEFINE('_L72ALERTS','Предупреждения за последние 72 часа: ');
DEFINE('_UNIALERTS',' Уникальные предупреждения');
DEFINE('_LSOURCEPORTS',"Последние порты-$UI_CW_Src".'и: ');
DEFINE('_LDESTPORTS',"Последние порты-$UI_CW_Dst: ");
DEFINE('_FREGSOURCEP',"Самые частые порты-$UI_CW_Src".'и: ');
DEFINE('_FREGDESTP',"Самые частые порты-$UI_CW_Dst: ");
DEFINE('_QUERIED','Запрос по');
DEFINE('_DATABASE','База данных:');
DEFINE('_SCHEMAV','Версия схемы:');
DEFINE('_TIMEWIN','Временное окно:');
DEFINE('_NOALERTSDETECT','предупреждений нет');
DEFINE('_USEALERTDB','Use Alert Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW

//base_auth.inc.php
DEFINE('_ADDEDSF',"$UI_CW_SucDesc добавлено");
DEFINE('_NOPWDCHANGE',"Невозможно поменять $UI_CW_Pw: ");
DEFINE('_NOUSER','Пользователь не сущетсвует!');
DEFINE('_OLDPWD',"Старый введенный $UI_CW_Pw не соответствует нашим записям!");
DEFINE('_PWDCANT',"Невозможно поменять Ваш $UI_CW_Pw: ");
DEFINE('_PWDDONE',"Ваш $UI_CW_Pw изменен!");
DEFINE('_ROLEEXIST','Роль уже существует');
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." уже существует");
DEFINE('_ROLEADDED',"Роль $UI_CW_SucDesc добавлена");

//base_roleadmin.php
DEFINE('_ROLEADMIN','Администрирование ролей BASE');
DEFINE('_FRMROLENAME',"$UI_CW_Name $UI_CW_Role:");
DEFINE('_UPDATEROLE',"Update $UI_CW_Role"); //NEW

//base_useradmin.php
DEFINE('_USERADMIN','Администрирование пользователей BASE');
DEFINE('_FRMFULLNAME',"Полное $UI_CW_Name:");
DEFINE('_FRMUID',"$UI_CW_Id пользователя:");
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW

//admin/index.php
DEFINE('_BASEADMIN','Администрирование BASE ');
DEFINE('_BASEADMINTEXT','Пожалуйста, выберите опцию слева.');

//base_action.inc.php
DEFINE('_NOACTION','Дествие для предупреждений не указано');
DEFINE('_INVALIDACT',' неверное действие');
DEFINE('_ERRNOAG','Невозможно добавить предупреждения, ГП не указана');
DEFINE('_ERRNOEMAIL','Невозможно отправить предупреждения по e-mail, не указан e-mail-'.$UI_CW_Addr);
DEFINE('_ACTION','ДЕЙСТВИЕ');
DEFINE('_CONTEXT','контекст');
DEFINE('_ADDAGID',"ДОБАВИТЬ в ГП (по $UI_CW_Id)");
DEFINE('_ADDAG','ДОБАВИТЬ-Новую-ГП');
DEFINE('_ADDAGNAME','ДОБАВИТЬ в ГП (по имени)');
DEFINE('_CREATEAG','Создать ГП (по имени)');
DEFINE('_CLEARAG','Очистить ГП');
DEFINE('_DELETEALERT',"$UI_CW_Delete предупреждение(-я)");
DEFINE('_EMAILALERTSFULL','Отправить предупреждение(-я) (полностью)');
DEFINE('_EMAILALERTSSUMM',"Отправить предупреждение(-я) ($UI_CW_Desc)");
DEFINE('_EMAILALERTSCSV','Отправить предупреждение(-я) (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Архивировать предупреждение(-я) (копировать)');
DEFINE('_ARCHIVEALERTSMOVE','Архивировать предупреждение(-я) (переместить)');
DEFINE('_IGNORED','Игнорированное ');
DEFINE('_DUPALERTS',' дублирующееся(-иеся) предупреждение(-я)');
DEFINE('_ALERTSPARA',' предупреждение(-я)');
DEFINE('_NOALERTSSELECT','Предупреждения не выбраны или');
DEFINE('_NOTSUCCESSFUL','не был успешным');
DEFINE('_ERRUNKAGID','Указан неизвестный идентификатор ГП (возможно, ГП не существует)');
DEFINE('_ERRREMOVEFAIL',"Не удалось $UI_CW_Delete новый ГП");
DEFINE('_GENBASE','Сгенерировано BASE');
DEFINE('_ERRNOEMAILEXP','ОШИБКА ЭКСПОРТА: Не удалось отправить экспортированные предупреждения на');
DEFINE('_ERRNOEMAILPHP','Проверьте конфигурацию почты PHP.');
DEFINE('_ERRDELALERT','Ошибка удаления предупреждения');
DEFINE('_ERRARCHIVE','Ошибка архивации:');
DEFINE('_ERRMAILNORECP','ОШИБКА ПОЧТЫ: Получатель не указан');

//base_cache.inc.php
DEFINE('_ADDED','Добавлены ');
DEFINE('_HOSTNAMESDNS',' имена хостов к кэшу IP DNS');
DEFINE('_HOSTNAMESWHOIS',' имена хостов к кэшу Whois');
DEFINE('_ERRCACHENULL','ОШИБКА Кэширования: обнаружен ряд NULL-событий?');
DEFINE('_ERRCACHEERROR','ОШИБКА КЭШИРОВАНИЯ СОБЫТИЙ:');
DEFINE('_ERRCACHEUPDATE','Не удалось обновить кэш событий');
DEFINE('_ALERTSCACHE',' предупреждение(-я) к кэшу предупреждений');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Не удалось открыть файл трассировки SQL');
DEFINE('_ERRSQLCONNECT','Ошибка подключения к БД :');
DEFINE('_ERRSQLCONNECTINFO','<P>Проверьте переменные подключения к БД в файле <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : '.$UI_CW_Name.' БД MySQL, в которой хранятся предупреждения
               = $alert_host     : хост, на котором хранится БД
               = $alert_port     : порт, на котором хранится БД
               = $alert_user     : '.$UI_CW_Name.' пользователя БД
               = $alert_password : '.$UI_CW_Pw.' пользователя
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Ошибка (p)подключения к БД :');
DEFINE('_ERRSQLDB','ОШИБКА БД:');
DEFINE('_DBALCHECK','Проверка абстракционной библиотеки БД в');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Ошибка зарузки абстракционной библиотеки БД: </B> из ');
DEFINE('_ERRSQLDBALLOAD2','<P>Проверьте переменную абстракционной библиотеки БД <CODE>$DBlib_path</CODE> в <CODE>base_conf.php</CODE>
            <P>
            В данный момент используется ADODB как библиотека работы с БД, она может быть загружена с
            ');
DEFINE('_ERRSQLDBTYPE','Указан неверный '.$UI_CW_Type.' БД');
DEFINE('_ERRSQLDBTYPEINFO1','Переменная <CODE>\$DBtype</CODE> в <CODE>base_conf.php</CODE> установлена в нераспознаваемое значение '.$UI_CW_Type.'а БД ');
DEFINE('_ERRSQLDBTYPEINFO2','Поддерживаются только следующие БД: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','ФАТАЛЬНАЯ ОШИБКА BASE:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Загружено за');
DEFINE('_SECONDS','секунд');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Невозможно получить '.$UI_CW_Addr);

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Выходной заголовок результатов запроса:');

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','Неизвестный SigName');
DEFINE('_ERRSIGPROIRITYUNK','Неизвестный SigPriority');
DEFINE('_UNCLASS','неклассифицировано');

//base_state_citems.inc.php
DEFINE('_DENCODED','данные закодированы как');
DEFINE('_NODENCODED','(данные не преобразованы, предполагается родная кодировка БД)');
DEFINE('_SHORTJAN','Jan'); //NEW
DEFINE('_SHORTFEB','Feb'); //NEW
DEFINE('_SHORTMAR','Mar'); //NEW
DEFINE('_SHORTAPR','Apr'); //NEW
DEFINE('_SHORTMAY','May'); //NEW
DEFINE('_SHORTJUN','Jun'); //NEW
DEFINE('_SHORTJLY','Jly'); //NEW
DEFINE('_SHORTAUG','Aug'); //NEW
DEFINE('_SHORTSEP','Sep'); //NEW
DEFINE('_SHORTOCT','Oct'); //NEW
DEFINE('_SHORTNOV','Nov'); //NEW
DEFINE('_SHORTDEC','Dec'); //NEW
DEFINE('_DISPSIG','{ signature }'); //NEW
DEFINE('_DISPANYCLASS','{ any Classification }'); //NEW
DEFINE('_DISPANYPRIO','{ any Priority }'); //NEW
DEFINE('_DISPANYSENSOR','{ any Sensor }'); //NEW
DEFINE('_DISPADDRESS','{ adress }'); //NEW
DEFINE('_DISPFIELD','{ field }'); //NEW
DEFINE('_DISPPORT','{ port }'); //NEW
DEFINE('_DISPENCODING','{ encoding }'); //NEW
DEFINE('_DISPCONVERT2','{ Convert To }'); //NEW
DEFINE('_DISPANYAG','{ any Alert Group }'); //NEW
DEFINE('_DISPPAYLOAD','{ payload }'); //NEW
DEFINE('_DISPFLAGS','{ flags }'); //NEW
DEFINE('_SIGEXACTLY','exactly'); //NEW
DEFINE('_SIGROUGHLY','roughly'); //NEW
DEFINE('_SIGCLASS','Signature Classification'); //NEW
DEFINE('_SIGPRIO','Signature Priority'); //NEW
DEFINE('_SHORTSOURCE','Source'); //NEW
DEFINE('_SHORTDEST','Dest'); //NEW
DEFINE('_SHORTSOURCEORDEST','Src or Dest'); //NEW
DEFINE('_NOLAYER4','no layer4'); //NEW
DEFINE('_INPUTCRTENC','Input Criteria Encoding Type'); //NEW
DEFINE('_CONVERT2WS','Convert To (when searching)'); //NEW

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','ОШИБКА PHP: Обнаружена частная РНР-сессия (пользовательская). Однако, BASE не сконфигурирован распознавать данный конкретный заголовок.  Установите <CODE>use_user_session=1</CODE> в <CODE>base_conf.php</CODE>');
DEFINE('_PHPERRORCSESSIONCODE','ОШИБКА PHP: Был сконфигурирован частный хэндер НР-сессии (пользовательской), но хэндер, указанный в <CODE>user_session_path</CODE> неправильный.');
DEFINE('_PHPERRORCSESSIONVAR','PHP ERROR: Был сконфигурирован частный хэндлер РНР-сессии (пользовательской), но имплементация этого хэндлера не указана в BASE.  Если хэндлер частеой сессии предпочтителен, установите переменную <CODE>user_session_path</CODE> в <CODE>base_conf.php</CODE>.');
DEFINE('_PHPSESSREG','Сессия зарегестрирована');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Удаление');
DEFINE('_FROMCRIT','из критериев');
DEFINE('_ERRCRITELEM','Неверный элемент критериев');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Верный список запросов');
DEFINE('_DISPLAYING','Отображение');
DEFINE('_DISPLAYINGTOTAL','Отображение предупреждений %d-%d из %d');
DEFINE('_NOALERTS','Предупреждения не найдены.');
DEFINE('_QUERYRESULTS','Результаты запроса');
DEFINE('_QUERYSTATE','Состояние запроса');
DEFINE('_DISPACTION','{ action }'); //NEW

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH',"Указанное $UI_CW_Name ГП для поиска неверно. Попробуйте еще раз!");
DEFINE('_ERRAGNAMEEXIST','Указанная ГП не существует.');
DEFINE('_ERRAGIDSEARCH',"Указанный $UI_CW_Id ГП для поиска неверный.  Попробуйте еще раз!");
DEFINE('_ERRAGLOOKUP',"Ошибка поиска $UI_CW_Id ГП");
DEFINE('_ERRAGINSERT','Ошибка вставки новой ГП');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Поддержка Групп Предупреждений (ГП)');
DEFINE('_ERRAGUPDATE','Ошибка обновления ГП');
DEFINE('_ERRAGPACKETLIST','Ошибка удаления списка пакетов из ГП:');
DEFINE('_ERRAGDELETE','Ошибка удаления ГП');
DEFINE('_AGDELETE',"$UI_CW_SucDesc УДАЛЕНО");
DEFINE('_AGDELETEINFO','информация удалена');
DEFINE('_ERRAGSEARCHINV','Введенные критерии поиска неверны. Попробуйте еще раз!');
DEFINE('_ERRAGSEARCHNOTFOUND','По данным критериям не найдено ни одной ГП.');
DEFINE('_NOALERTGOUPS','Групп Предупреждений нет');
DEFINE('_NUMALERTS','Число предупреждений');
DEFINE('_ACTIONS','Действия');
DEFINE('_NOTASSIGN','еще но назначено');
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW

//base_common.php
DEFINE('_PORTSCAN','Траффик сканирования портов');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Не удалось создать индекс для');
DEFINE('_DBINDEXCREATE',"Индекс $UI_CW_SucDesc создан для");
DEFINE('_ERRSNORTVER','Это может быть старой версии. Поддерживаются базы предупреждений созданные только с помощью Snort 1.7-beta0 или более поздней версии');
DEFINE('_ERRSNORTVER1','БД-подложка');
DEFINE('_ERRSNORTVER2','может быть неполной/неверной');
DEFINE('_ERRDBSTRUCT1','Версия БД верна, но структура БД BASE');
DEFINE('_ERRDBSTRUCT2','не присутствует. Используйте <A HREF="base_db_setup.php">Страницу установки</A> для конфигурирования и оптимизации БД.');
DEFINE('_ERRPHPERROR','ОШИБКА PHP');
DEFINE('_ERRPHPERROR1','Несовместимая версия');
DEFINE('_ERRVERSION','Версия');
DEFINE('_ERRPHPERROR2','PHP слишком стара.  Пожалуйста, обновите ее до 4.0.4 или более поздней');
DEFINE('_ERRPHPMYSQLSUP','<B>билд PHP неполный</B>: <FONT>встроенная поддержка MySQL, необходимая для 
               чтения базы предупреждений, не встроена в PHP.  
               Пожалуйста, перекомпилируйте PHP с необходимой библиотекой (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>билд PHP неполный</B>: <FONT>встроенная поддержка PostgreSQL, необходимая для 
               чтения базы предупреждений, не встроена в PHP.  
               Пожалуйста, перекомпилируйте PHP с необходимой библиотекой (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>билд PHP неполный</B>: <FONT>встроенная поддержка MS SQL Server, необходимая для
               чтения базы предупреждений, не встроена в PHP.  
               Пожалуйста, перекомпилируйте PHP с необходимой библиотекой  (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP build incomplete</B>: <FONT>the prerequisite Oracle support required to 
                   read the alert database was not built into PHP.  
                   Please recompile PHP with the necessary library (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Заголовок графика:');
DEFINE('_CHARTTYPE','Chart Type:'); //NEW
DEFINE('_CHARTTYPES','{ chart type }'); //NEW
DEFINE('_CHARTPERIOD','Chart Period:'); //NEW
DEFINE('_PERIODNO','no period'); //NEW
DEFINE('_PERIODWEEK','7 (a week)'); //NEW
DEFINE('_PERIODDAY','24 (whole day)'); //NEW
DEFINE('_PERIOD168','168 (24x7)'); //NEW
DEFINE('_CHARTSIZE','Size: (width x height)'); //NEW
DEFINE('_PLOTMARGINS','Plot Margins: (left x right x top x bottom)'); //NEW
DEFINE('_PLOTTYPE','Plot type:'); //NEW
DEFINE('_TYPEBAR','bar'); //NEW
DEFINE('_TYPELINE','line'); //NEW
DEFINE('_TYPEPIE','pie'); //NEW
DEFINE('_CHARTHOUR','{hora}'); //NEW
DEFINE('_CHARTDAY','{dia}'); //NEW
DEFINE('_CHARTMONTH','{mГЄs}'); //NEW
DEFINE('_GRAPHALERTS','Graph Alerts'); //NEW
DEFINE('_AXISCONTROLS','X / Y AXIS CONTROLS'); //NEW
DEFINE('_CHRTTYPEHOUR',$UI_CW_Ts.' (часы) и число предупреждений');
DEFINE('_CHRTTYPEDAY',$UI_CW_Ts.' (дни) и число предупреждений');
DEFINE('_CHRTTYPEWEEK',$UI_CW_Ts.' (недели) и число предупреждений');
DEFINE('_CHRTTYPEMONTH',$UI_CW_Ts.' (месяцы) и число предупреждений');
DEFINE('_CHRTTYPEYEAR',$UI_CW_Ts.' (годы) и число предупреждений');
DEFINE('_CHRTTYPESRCIP',"IP-$UI_CW_Src  и число предупреждений");
DEFINE('_CHRTTYPEDSTIP',"IP-$UI_CW_Dst и число предупреждений");
DEFINE('_CHRTTYPEDSTUDP',"UDP порт-$UI_CW_Dst и число предупреждений");
DEFINE('_CHRTTYPESRCUDP',"UDP порт-$UI_CW_Src и число предупреждений");
DEFINE('_CHRTTYPEDSTPORT',"TCP порт-$UI_CW_Dst и число предупреждений");
DEFINE('_CHRTTYPESRCPORT',"TCP порт-$UI_CW_Src и число предупреждений");
DEFINE('_CHRTTYPESIG','Сиг. классификация и число предупреждений');
DEFINE('_CHRTTYPESENSOR',"$UI_CW_Sensor и число предупреждений");
DEFINE('_CHRTBEGIN','Начало графика:');
DEFINE('_CHRTEND','Конец графика:');
DEFINE('_CHRTDS',"$UI_CW_Src данных:");
DEFINE('_CHRTX','Ось X');
DEFINE('_CHRTY','Ось Y');
DEFINE('_CHRTMINTRESH','Минимальное пороговое значение');
DEFINE('_CHRTROTAXISLABEL','Повернуть метки на оси (90 градусов)');
DEFINE('_CHRTSHOWX','Показать сетку линий оси X');
DEFINE('_CHRTDISPLABELX','Показывать метку оси X каждые');
DEFINE('_CHRTDATAPOINTS','единиц данных');
DEFINE('_CHRTYLOG','Логарифмическая ось Y');
DEFINE('_CHRTYGRID','Показывать сетку линий оси Y');

//base_graph_main.php
DEFINE('_CHRTTITLE','График BASE');
DEFINE('_ERRCHRTNOTYPE','Не указан '.$UI_CW_Type.' графика');
DEFINE('_ERRNOAGSPEC','ГП ну указана. Используются все предупреждения.');
DEFINE('_CHRTDATAIMPORT','Начало импорта данных');
DEFINE('_CHRTTIMEVNUMBER',$UI_CW_Ts.' и число предупреждений');
DEFINE('_CHRTTIME',$UI_CW_Ts);
DEFINE('_CHRTALERTOCCUR','Случаи предупреждений');
DEFINE('_CHRTSIPNUMBER',"IP-$UI_CW_Src и число предупреждений");
DEFINE('_CHRTSIP',"IP-$UI_CW_Src");
DEFINE('_CHRTDIPALERTS',"IP-$UI_CW_Dst и число предупреждений");
DEFINE('_CHRTDIP',"IP-$UI_CW_Dst");
DEFINE('_CHRTUDPPORTNUMBER',"UDP порт ($UI_CW_Dst) и число предупреждений");
DEFINE('_CHRTDUDPPORT',"UDP порт-$UI_CW_Dst");
DEFINE('_CHRTSUDPPORTNUMBER',"UDP порт ($UI_CW_Src) и число предупреждений");
DEFINE('_CHRTSUDPPORT',"UDP порт-$UI_CW_Src");
DEFINE('_CHRTPORTDESTNUMBER',"TCP порт ($UI_CW_Dst) и число предупреждений");
DEFINE('_CHRTPORTDEST',"TCP порт-$UI_CW_Dst");
DEFINE('_CHRTPORTSRCNUMBER',"TCP порт ($UI_CW_Src) и число предупреждений");
DEFINE('_CHRTPORTSRC',"TCP порт-$UI_CW_Src");
DEFINE('_CHRTSIGNUMBER','Сиг. классификация и число предупреждений');
DEFINE('_CHRTCLASS','Классификация');
DEFINE('_CHRTSENSORNUMBER',"$UI_CW_Sensor и число предупреждений");
DEFINE('_CHRTHANDLEPERIOD','Определение периода при необходимости');
DEFINE('_CHRTDUMP','Запись данных ...');
DEFINE('_CHRTDRAW','Рисование графика');
DEFINE('_ERRCHRTNODATAPOINTS','Нет точек данных для рисования');
DEFINE('_GRAPHALERTDATA','Graph Alert Data'); //NEW

//base_maintenance.php
DEFINE('_MAINTTITLE','Поддержка');
DEFINE('_MNTPHP','Билд PHP:');
DEFINE('_MNTCLIENT','КЛИЕНТ:');
DEFINE('_MNTSERVER','СЕРВЕР:');
DEFINE('_MNTSERVERHW','HW СЕРВЕРА:');
DEFINE('_MNTPHPVER','ВЕРСИЯ PHP:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','Уровень протоколирования PHP:');
DEFINE('_MNTPHPMODS','Загруженные модули:');
DEFINE('_MNTDBTYPE',$UI_CW_Type.' DB:');
DEFINE('_MNTDBALV','Версия абстракции DB:');
DEFINE('_MNTDBALERTNAME',"$UI_CW_Name БД предупреждений:");
DEFINE('_MNTDBARCHNAME',"$UI_CW_Name БД архива:");
DEFINE('_MNTAIC','Кэш информации о предупреждениях:');
DEFINE('_MNTAICTE','Всего событий:');
DEFINE('_MNTAICCE','Кэшировано событий:');
DEFINE('_MNTIPAC','Кэш IP-'.$UI_CW_Addr.'ов');
DEFINE('_MNTIPACUSIP',"Уникальные IP-$UI_CW_Src".'и:');
DEFINE('_MNTIPACDNSC','Кэшированных DNS:');
DEFINE('_MNTIPACWC','Кэшированных Whois:');
DEFINE('_MNTIPACUDIP',"Уникальные IP-$UI_CW_Dst:");

//base_qry_alert.php
DEFINE('_QAINVPAIR','Неверная пара (sid,cid)');
DEFINE('_QAALERTDELET','Предупреждение УДАЛЕНО');
DEFINE('_QATRIGGERSIG',"Триггерная $UI_CW_Sig");
DEFINE('_QANORMALD','Normal Display'); //NEW
DEFINE('_QAPLAIND','Plain Display'); //NEW
DEFINE('_QANOPAYLOAD','Fast logging used so payload was discarded'); //NEW

//base_qry_common.php
DEFINE('_QCSIG',$UI_CW_Sig);
DEFINE('_QCIPADDR','IP '.$UI_CW_Addr.'а');
DEFINE('_QCIPFIELDS','IP поля');
DEFINE('_QCTCPPORTS','TCP порты');
DEFINE('_QCTCPFLAGS','TCP флаги');
DEFINE('_QCTCPFIELD','TCP поля');
DEFINE('_QCUDPPORTS','UDP порты');
DEFINE('_QCUDPFIELDS','UDP поля');
DEFINE('_QCICMPFIELDS','ICMP поля');
DEFINE('_QCDATA','Данные');
DEFINE('_QCERRCRITWARN','Внимание:');
DEFINE('_QCERRVALUE','Величина');
DEFINE('_QCERRFIELD','Поле');
DEFINE('_QCERROPER','Оператор');
DEFINE('_QCERRDATETIME',"Дата/$UI_CW_Ts");
DEFINE('_QCERRPAYLOAD','Величина загрузки');
DEFINE('_QCERRIP','IP '.$UI_CW_Addr);
DEFINE('_QCERRIPTYPE','IP '.$UI_CW_Addr.' '.$UI_CW_Type.'а');
DEFINE('_QCERRSPECFIELD',' введен(-а) в поле протокола, но конкретное поле не было указано.');
DEFINE('_QCERRSPECVALUE','выбран(-а) как критерий, но не указана величина для соответствия ей.');
DEFINE('_QCERRBOOLEAN','В качестве критерия введено несоклько протоколов, но не использованы логические операторы (напр., AND, OR).');
DEFINE('_QCERRDATEVALUE','выбран(-а) как показывающий(-ая), что должна совпадать дата/'.$UI_CW_Ts.', но значение не указано.');
DEFINE('_QCERRINVHOUR','(Неверное '.$UI_CW_Ts.') Не введен критерий даты для указанного времени.');
DEFINE('_QCERRDATECRIT','выбран(-а), как показывающий(-ая), что должна совпадать дата/'.$UI_CW_Ts.', но значение не указано.');
DEFINE('_QCERROPERSELECT','введен(-а), но ни один оператор не выбран.');
DEFINE('_QCERRDATEBOOL','Введены несколько критериев даты/времени без логических операторов между ними (напр., AND, OR).');
DEFINE('_QCERRPAYCRITOPER','введен(-а) как критерий загрузки, но оператор (напр., has, has not) не был указан.');
DEFINE('_QCERRPAYCRITVALUE','выбран(-а) как показывющий, что критерием является загрузка, но значение не указано.');
DEFINE('_QCERRPAYBOOL','Введено несколько критериев загрузки без логического оператора между ними (напр., AND, OR).');
DEFINE('_QCMETACRIT','Мета критерий');
DEFINE('_QCIPCRIT','Критерий IP');
DEFINE('_QCPAYCRIT','Критерий загрузки');
DEFINE('_QCTCPCRIT','Критерий TCP');
DEFINE('_QCUDPCRIT','Критерий UDP');
DEFINE('_QCICMPCRIT','Критерий ICMP');
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QCERRINVIPCRIT','Неверный критерий: IP '.$UI_CW_Addr);
DEFINE('_QCERRCRITADDRESSTYPE','введен(-а) как значение критерия, но '.$UI_CW_Type.' '.$UI_CW_Addr."а (напр., $UI_CW_Src, $UI_CW_Dst) не был указан.");
DEFINE('_QCERRCRITIPADDRESSNONE','показывающий(-ая), что IP '.$UI_CW_Addr.' должен быть критерием, но '.$UI_CW_Addr.' не указан.');
DEFINE('_QCERRCRITIPADDRESSNONE1','выбран(-а) (#');
DEFINE('_QCERRCRITIPIPBOOL','В качестве критерия введены несколько IP '.$UI_CW_Addr.'ов без логического оператора между ними (напр., AND, OR)');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Порядок сортировки');
DEFINE('_QFRMSORTNONE','none'); //NEW
DEFINE('_QFRMTIMEA',$UI_CW_Ts.' (восходящий)');
DEFINE('_QFRMTIMED',$UI_CW_Ts.' (нисходящий)');
DEFINE('_QFRMSIG',$UI_CW_Sig);
DEFINE('_QFRMSIP',"IP-$UI_CW_Src");
DEFINE('_QFRMDIP',"IP-$UI_CW_Dst");

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Общая статистика');
DEFINE('_QSCTIMEPROF','Временной профииль');
DEFINE('_QSCOFALERTS','предупреждений');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','Список предупреждений');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Категории:');
DEFINE('_SCSENSORTOTAL',$UI_CW_Sensor.'ы/Всего:');
DEFINE('_SCTOTALNUMALERTS','Общее количество предупреждений:');
DEFINE('_SCSRCIP',"IP-$UI_CW_Src:");
DEFINE('_SCDSTIP',"IP-$UI_CW_Dst:");
DEFINE('_SCUNILINKS','Уникальные IP связи');
DEFINE('_SCSRCPORTS',"Порты-$UI_CW_Src".'и: ');
DEFINE('_SCDSTPORTS','Порты-$UI_CW_Dst: ');
DEFINE('_SCSENSORS',$UI_CW_Sensor.'ы');
DEFINE('_SCCLASS','классификации');
DEFINE('_SCUNIADDRESS','Уникальные '.$UI_CW_Addr.'ы: ');
DEFINE('_SCSOURCE',$UI_CW_Src);
DEFINE('_SCDEST',$UI_CW_Dst);
DEFINE('_SCPORT','Порт');

//base_stat_ipaddr.php
DEFINE('_PSEVENTERR','ОШИБКА '.$UI_CW_Event.' СКАНИРОВАНИЯ ПОРТОВ: ');
DEFINE('_PSEVENTERRNOFILE','Ни один файл не указан в переменной $portscan_file.');
DEFINE('_PSEVENTERROPENFILE','Не удалось открыть файл событий сканирования портов');
DEFINE('_PSDATETIME',"Дата/$UI_CW_Ts");
DEFINE('_PSSRCIP',"IP-$UI_CW_Src");
DEFINE('_PSDSTIP',"IP-$UI_CW_Dst");
DEFINE('_PSSRCPORT',"порт-$UI_CW_Src");
DEFINE('_PSDSTPORT',"порт-$UI_CW_Dst");
DEFINE('_PSTCPFLAGS','Флаги TCP');
DEFINE('_PSTOTALOCC','Всего<BR> Случаев');
DEFINE('_PSNUMSENSORS',"Число $UI_CW_Sensor".'ов');
DEFINE('_PSFIRSTOCC','Первый<BR> Случай');
DEFINE('_PSLASTOCC','Последний<BR> Случай');
DEFINE('_PSUNIALERTS','Уникальные предупреждения');
DEFINE('_PSPORTSCANEVE',$UI_CW_Event.' сканирования портов');
DEFINE('_PSREGWHOIS','Поиск (whois) в');
DEFINE('_PSNODNS','не получено DNS-разрешения');
DEFINE('_PSNUMSENSORSBR',"Число <BR>$UI_CW_Sensor".'ов');
DEFINE('_PSOCCASSRC',"Случаи <BR>как $UI_CW_Src".'и.');
DEFINE('_PSOCCASDST',"Случаи <BR>как $UI_CW_Dst");
DEFINE('_PSWHOISINFO','Информация Whois');
DEFINE('_PSTOTALHOSTS','Total Hosts Scanned'); //NEW
DEFINE('_PSDETECTAMONG','%d unique alerts detected among %d alerts on %s'); //NEW
DEFINE('_PSALLALERTSAS','all alerts with %s/%s as'); //NEW
DEFINE('_PSSHOW','show'); //NEW
DEFINE('_PSEXTERNAL','external'); //NEW

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP Связи');
DEFINE('_SIPLSOURCEFGDN',"$UI_CW_Src FQDN");
DEFINE('_SIPLDESTFGDN',"$UI_CW_Dst FQDN");
DEFINE('_SIPLDIRECTION','Направление');
DEFINE('_SIPLPROTO',$UI_CW_Proto);
DEFINE('_SIPLUNIDSTPORTS','Уникальные порты-'.$UI_CW_Dst);
DEFINE('_SIPLUNIEVENTS','Уникальные '.$UI_CW_Event);
DEFINE('_SIPLTOTALEVENTS','Всего событий');

//base_stat_ports.php
DEFINE('_UNIQ','Уникальные');
DEFINE('_DSTPS',"Порты-$UI_CW_Dst");
DEFINE('_SRCPS',"Порт-$UI_CW_Src".'и');
DEFINE('_OCCURRENCES','Occurrences'); //NEW

//base_stat_sensor.php
DEFINE('SPSENSORLIST',"Список $UI_CW_Sensor".'ов');

//base_stat_time.php
DEFINE('_BSTTITLE','Временной профиль предупреждений');
DEFINE('_BSTTIMECRIT','Критерии времени');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>Не указан профайлинг критериев!</B>  Нажмите на "часы", "день", или "месяц", чтобы выбрать зернистость агрегатной статистики.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>Не указан '.$UI_CW_Type.' временного параметра!</B>  Выберите или "в", чтобы указать одну дату, или "между", чтобы указать интервал.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>Параметр Год не указан!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>Параметр Месяц не указан!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>Параметр День не указан!</B></FONT>');
DEFINE('_BSTPROFILEBY','Profile by'); //NEW
DEFINE('_TIMEON','on'); //NEW
DEFINE('_TIMEBETWEEN','between'); //NEW
DEFINE('_PROFILEALERT','Profile Alert'); //NEW

//base_stat_uaddr.php
DEFINE('_UNISADD','Уникальные '.$UI_CW_Addr."а-$UI_CW_Src".'и');
DEFINE('_SUASRCIP',"IP-$UI_CW_Src");
DEFINE('_SUAERRCRITADDUNK','ОШИБКА КРИТЕРИЯ: неизвестный '.$UI_CW_Type.'а '.$UI_CW_Addr."а -- предполагается $UI_CW_Addr-$UI_CW_Dst");
DEFINE('_UNIDADD','Уникальниые '.$UI_CW_Addr."а-$UI_CW_Dst");
DEFINE('_SUADSTIP',"IP-$UI_CW_Dst");
DEFINE('_SUAUNIALERTS','Уникальные&nbsp;предупреждения');
DEFINE('_SUASRCADD',$UI_CW_Addr.'&nbsp;'.$UI_CW_Src);
DEFINE('_SUADSTADD',$UI_CW_Addr.'&nbsp;'.$UI_CW_Dst);

//base_user.php
DEFINE('_BASEUSERTITLE','Пользовательские установки BASE');
DEFINE('_BASEUSERERRPWD',"Ваш $UI_CW_Pw не может быть пустым или два пароля не совпали!");
DEFINE('_BASEUSEROLDPWD',"Старый $UI_CW_Pw:");
DEFINE('_BASEUSERNEWPWD',"Новый $UI_CW_Pw:");
DEFINE('_BASEUSERNEWPWDAGAIN',"Еще раз новый $UI_CW_Pw:");

DEFINE('_LOGOUT','Выход');

?>
