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
** ������� �������: ������� ������ <dpurgin@hotmail.kz>
********************************************************************************
*/

//locale
DEFINE('_LOCALESTR1', 'eng_ENG.ISO8859-1'); //NEW
DEFINE('_LOCALESTR2', 'eng_ENG.utf-8'); //NEW
DEFINE('_LOCALESTR3', 'english'); //NEW
DEFINE('_STRFTIMEFORMAT','%a %B %d, %Y %H:%M:%S'); //NEW - see strftime() sintax

// �������� �����
DEFINE('_CHARSET','windows-1251');
DEFINE('_TITLE','������� ������ ������� � ������������ (BASE) '.$BASE_installID);
DEFINE('_FRMLOGIN','�����:');
DEFINE('_FRMPWD','������:');
DEFINE('_SOURCE','��������');
DEFINE('_SOURCENAME','��� ���������');
DEFINE('_DEST','����������');
DEFINE('_DESTNAME','��� ����������');
DEFINE('_SORD','�������� ��� ����������');
DEFINE('_EDIT','�������������');
DEFINE('_DELETE','�������');
DEFINE('_ID','ID');
DEFINE('_NAME','���');
DEFINE('_INTERFACE','���������');
DEFINE('_FILTER','������');
DEFINE('_DESC','��������');
DEFINE('_LOGIN','�����');
DEFINE('_ROLEID','ID ����');
DEFINE('_ENABLED','��������');
DEFINE('_SUCCESS','�������');
DEFINE('_SENSOR','������');
DEFINE('_SENSORS','Sensors'); //NEW
DEFINE('_SIGNATURE','���������');
DEFINE('_TIMESTAMP','�����');
DEFINE('_NBSOURCEADDR','�����&nbsp;���������');
DEFINE('_NBDESTADDR','�����&nbsp;����������');
DEFINE('_NBLAYER4','����&nbsp;���&nbsp;�����');
DEFINE('_PRIORITY','���������');
DEFINE('_EVENTTYPE','��� �������');
DEFINE('_JANUARY','������');
DEFINE('_FEBRUARY','�������');
DEFINE('_MARCH','����');
DEFINE('_APRIL','������');
DEFINE('_MAY','���');
DEFINE('_JUNE','����');
DEFINE('_JULY','����');
DEFINE('_AUGUST','������');
DEFINE('_SEPTEMBER','��������');
DEFINE('_OCTOBER','�������');
DEFINE('_NOVEMBER','������');
DEFINE('_DECEMBER','�������');
DEFINE('_LAST','���������');
DEFINE('_FIRST','First'); //NEW
DEFINE('_TOTAL','Total'); //NEW
DEFINE('_ALERT','��������������');
DEFINE('_ADDRESS','�����');
DEFINE('_UNKNOWN','����������');
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

//������ ����
DEFINE('_HOME','�����');
DEFINE('_SEARCH','�����');
DEFINE('_AGMAINT','��������� ����� ��������������');
DEFINE('_USERPREF','��������� ������������');
DEFINE('_CACHE','��� � ������');
DEFINE('_ADMIN','�����������������');
DEFINE('_GALERTD','������ ������ ��������������');
DEFINE('_GALERTDT','������ ������� ����������� ��������������');
DEFINE('_USERMAN','���������� ��������������');
DEFINE('_LISTU','������ �������������');
DEFINE('_CREATEU','������� ������������');
DEFINE('_ROLEMAN','���������� ������');
DEFINE('_LISTR','������ �����');
DEFINE('_CREATER','������� ����');
DEFINE('_LISTALL','���� ������');
DEFINE('_CREATE','�������');
DEFINE('_VIEW','�����������');
DEFINE('_CLEAR','��������');
DEFINE('_LISTGROUPS','������ �����');
DEFINE('_CREATEGROUPS','������� ������');
DEFINE('_VIEWGROUPS','����������� ������');
DEFINE('_EDITGROUPS','������������� ������');
DEFINE('_DELETEGROUPS','������� ������');
DEFINE('_CLEARGROUPS','�������� ������');
DEFINE('_CHNGPWD','�������� ������');
DEFINE('_DISPLAYU','�������� ������������');

//base_footer.php
DEFINE('_FOOTER','( by <A class="largemenuitem" href="mailto:base@secureideas.net">����� ������� (Kevin Johnson)</A> � �������� ������� <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE</A><BR>�������� �� ACID ������ �������� (Roman Danyliw)');

//index.php --�������� ����� � �������
DEFINE('_LOGINERROR','������������ �� ���������� ��� ��� ������ ��������!<br>����������, ����������� ��� ���');

// base_main.php
DEFINE('_MOSTRECENT','����� ��������� ');
DEFINE('_MOSTFREQUENT','����� ������ ');
DEFINE('_ALERTS',' ��������������:');
DEFINE('_ADDRESSES',' ������');
DEFINE('_ANYPROTO','����� ��������');
DEFINE('_UNI','����������');
DEFINE('_LISTING','�������');
DEFINE('_TALERTS','����������� ��������������: ');
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_L24ALERTS','�������������� �� ��������� 24 ����: ');
DEFINE('_L72ALERTS','�������������� �� ��������� 72 ����: ');
DEFINE('_UNIALERTS',' ���������� ��������������');
DEFINE('_LSOURCEPORTS','��������� �����-���������: ');
DEFINE('_LDESTPORTS','��������� �����-����������: ');
DEFINE('_FREGSOURCEP','����� ������ �����-���������: ');
DEFINE('_FREGDESTP','����� ������ �����-����������: ');
DEFINE('_QUERIED','������ ��');
DEFINE('_DATABASE','���� ������:');
DEFINE('_SCHEMAV','������ �����:');
DEFINE('_TIMEWIN','��������� ����:');
DEFINE('_NOALERTSDETECT','�������������� ���');
DEFINE('_USEALERTDB','Use Alert Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW

//base_auth.inc.php
DEFINE('_ADDEDSF','������� ���������');
DEFINE('_NOPWDCHANGE','���������� �������� ������: ');
DEFINE('_NOUSER','������������ �� ����������!');
DEFINE('_OLDPWD','������ ��������� ������ �� ������������� ����� �������!');
DEFINE('_PWDCANT','���������� �������� ��� ������: ');
DEFINE('_PWDDONE','��� ������ �������!');
DEFINE('_ROLEEXIST','���� ��� ����������');
DEFINE('_ROLEIDEXIST','ID ���� ��� ����������');
DEFINE('_ROLEADDED','���� ������� ���������');

//base_roleadmin.php
DEFINE('_ROLEADMIN','����������������� ����� BASE');
DEFINE('_FRMROLEID','ID ����:');
DEFINE('_FRMROLENAME','��� ����:');
DEFINE('_FRMROLEDESC','��������:');
DEFINE('_UPDATEROLE','Update Role'); //NEW

//base_useradmin.php
DEFINE('_USERADMIN','����������������� ������������� BASE');
DEFINE('_FRMFULLNAME','������ ���:');
DEFINE('_FRMROLE','����:');
DEFINE('_FRMUID','ID ������������:');
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW

//admin/index.php
DEFINE('_BASEADMIN','����������������� BASE ');
DEFINE('_BASEADMINTEXT','����������, �������� ����� �����.');

//base_action.inc.php
DEFINE('_NOACTION','������� ��� �������������� �� �������');
DEFINE('_INVALIDACT',' �������� ��������');
DEFINE('_ERRNOAG','���������� �������� ��������������, �� �� �������');
DEFINE('_ERRNOEMAIL','���������� ��������� �������������� �� e-mail, �� ������ e-mail-�����');
DEFINE('_ACTION','��������');
DEFINE('_CONTEXT','��������');
DEFINE('_ADDAGID','�������� � �� (�� ID)');
DEFINE('_ADDAG','��������-�����-��');
DEFINE('_ADDAGNAME','�������� � �� (�� �����)');
DEFINE('_CREATEAG','������� �� (�� �����)');
DEFINE('_CLEARAG','�������� ��');
DEFINE('_DELETEALERT','������� ��������������(-�)');
DEFINE('_EMAILALERTSFULL','��������� ��������������(-�) (���������)');
DEFINE('_EMAILALERTSSUMM','��������� ��������������(-�) (��������)');
DEFINE('_EMAILALERTSCSV','��������� ��������������(-�) (csv)');
DEFINE('_ARCHIVEALERTSCOPY','������������ ��������������(-�) (����������)');
DEFINE('_ARCHIVEALERTSMOVE','������������ ��������������(-�) (�����������)');
DEFINE('_IGNORED','�������������� ');
DEFINE('_DUPALERTS',' �������������(-����) ��������������(-�)');
DEFINE('_ALERTSPARA',' ��������������(-�)');
DEFINE('_NOALERTSSELECT','�������������� �� ������� ���');
DEFINE('_NOTSUCCESSFUL','�� ��� ��������');
DEFINE('_ERRUNKAGID','������ ����������� ������������� �� (��������, �� �� ����������)');
DEFINE('_ERRREMOVEFAIL','�� ������� ������� ����� ��');
DEFINE('_GENBASE','������������� BASE');
DEFINE('_ERRNOEMAILEXP','������ ��������: �� ������� ��������� ���������������� �������������� ��');
DEFINE('_ERRNOEMAILPHP','��������� ������������ ����� PHP.');
DEFINE('_ERRDELALERT','������ �������� ��������������');
DEFINE('_ERRARCHIVE','������ ���������:');
DEFINE('_ERRMAILNORECP','������ �����: ���������� �� ������');

//base_cache.inc.php
DEFINE('_ADDED','��������� ');
DEFINE('_HOSTNAMESDNS',' ����� ������ � ���� IP DNS');
DEFINE('_HOSTNAMESWHOIS',' ����� ������ � ���� Whois');
DEFINE('_ERRCACHENULL','������ �����������: ��������� ��� NULL-�������?');
DEFINE('_ERRCACHEERROR','������ ����������� �������:');
DEFINE('_ERRCACHEUPDATE','�� ������� �������� ��� �������');
DEFINE('_ALERTSCACHE',' ��������������(-�) � ���� ��������������');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','�� ������� ������� ���� ����������� SQL');
DEFINE('_ERRSQLCONNECT','������ ����������� � �� :');
DEFINE('_ERRSQLCONNECTINFO','<P>��������� ���������� ����������� � �� � ����� <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : ��� �� MySQL, � ������� �������� ��������������
               = $alert_host     : ����, �� ������� �������� ��
               = $alert_port     : ����, �� ������� �������� ��
               = $alert_user     : ��� ������������ ��
               = $alert_password : ������ ������������
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','������ (p)����������� � �� :');
DEFINE('_ERRSQLDB','������ ��:');
DEFINE('_DBALCHECK','�������� �������������� ���������� �� �');
DEFINE('_ERRSQLDBALLOAD1','<P><B>������ ������� �������������� ���������� ��: </B> �� ');
DEFINE('_ERRSQLDBALLOAD2','<P>��������� ���������� �������������� ���������� �� <CODE>$DBlib_path</CODE> � <CODE>base_conf.php</CODE>
            <P>
            � ������ ������ ������������ ADODB ��� ���������� ������ � ��, ��� ����� ���� ��������� �
            ');
DEFINE('_ERRSQLDBTYPE','������ �������� ��� ��');
DEFINE('_ERRSQLDBTYPEINFO1','���������� <CODE>\$DBtype</CODE> � <CODE>base_conf.php</CODE> ����������� � ���������������� �������� ���� �� ');
DEFINE('_ERRSQLDBTYPEINFO2','�������������� ������ ��������� ��: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','��������� ������ BASE:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','��������� ��');
DEFINE('_SECONDS','������');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','���������� �������� �����');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','�������� ��������� ����������� �������:');

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','����������� SigName');
DEFINE('_ERRSIGPROIRITYUNK','����������� SigPriority');
DEFINE('_UNCLASS','������������������');

//base_state_citems.inc.php
DEFINE('_DENCODED','������ ������������ ���');
DEFINE('_NODENCODED','(������ �� �������������, �������������� ������ ��������� ��)');
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
DEFINE('_PHPERRORCSESSION','������ PHP: ���������� ������� ���-������ (����������������). ������, BASE �� ��������������� ������������ ������ ���������� ���������.  ���������� <CODE>use_user_session=1</CODE> � <CODE>base_conf.php</CODE>');
DEFINE('_PHPERRORCSESSIONCODE','������ PHP: ��� ��������������� ������� ������ ��-������ (����������������), �� ������, ��������� � <CODE>user_session_path</CODE> ������������.');
DEFINE('_PHPERRORCSESSIONVAR','PHP ERROR: ��� ��������������� ������� ������� ���-������ (����������������), �� ������������� ����� �������� �� ������� � BASE.  ���� ������� ������� ������ ��������������, ���������� ���������� <CODE>user_session_path</CODE> � <CODE>base_conf.php</CODE>.');
DEFINE('_PHPSESSREG','������ ����������������');

//base_state_criteria.inc.php
DEFINE('_REMOVE','��������');
DEFINE('_FROMCRIT','�� ���������');
DEFINE('_ERRCRITELEM','�������� ������� ���������');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','������ ������ ��������');
DEFINE('_DISPLAYING','�����������');
DEFINE('_DISPLAYINGTOTAL','����������� �������������� %d-%d �� %d');
DEFINE('_NOALERTS','�������������� �� �������.');
DEFINE('_QUERYRESULTS','���������� �������');
DEFINE('_QUERYSTATE','��������� �������');
DEFINE('_DISPACTION','{ action }'); //NEW

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','��������� ��� �� ��� ������ �������. ���������� ��� ���!');
DEFINE('_ERRAGNAMEEXIST','��������� �� �� ����������.');
DEFINE('_ERRAGIDSEARCH','��������� ID �� ��� ������ ��������.  ���������� ��� ���!');
DEFINE('_ERRAGLOOKUP','������ ������ ID ��');
DEFINE('_ERRAGINSERT','������ ������� ����� ��');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','��������� ����� �������������� (��)');
DEFINE('_ERRAGUPDATE','������ ���������� ��');
DEFINE('_ERRAGPACKETLIST','������ �������� ������ ������� �� ��:');
DEFINE('_ERRAGDELETE','������ �������� ��');
DEFINE('_AGDELETE','������� �������');
DEFINE('_AGDELETEINFO','���������� �������');
DEFINE('_ERRAGSEARCHINV','��������� �������� ������ �������. ���������� ��� ���!');
DEFINE('_ERRAGSEARCHNOTFOUND','�� ������ ��������� �� ������� �� ����� ��.');
DEFINE('_NOALERTGOUPS','����� �������������� ���');
DEFINE('_NUMALERTS','����� ��������������');
DEFINE('_ACTIONS','��������');
DEFINE('_NOTASSIGN','��� �� ���������');
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW

//base_common.php
DEFINE('_PORTSCAN','������� ������������ ������');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','�� ������� ������� ������ ���');
DEFINE('_DBINDEXCREATE','������ ������� ������ ���');
DEFINE('_ERRSNORTVER','��� ����� ���� ������ ������. �������������� ���� �������������� ��������� ������ � ������� Snort 1.7-beta0 ��� ����� ������� ������');
DEFINE('_ERRSNORTVER1','��-��������');
DEFINE('_ERRSNORTVER2','����� ���� ��������/��������');
DEFINE('_ERRDBSTRUCT1','������ �� �����, �� ��������� �� BASE');
DEFINE('_ERRDBSTRUCT2','�� ������������. ����������� <A HREF="base_db_setup.php">�������� ���������</A> ��� ���������������� � ����������� ��.');
DEFINE('_ERRPHPERROR','������ PHP');
DEFINE('_ERRPHPERROR1','������������� ������');
DEFINE('_ERRVERSION','������');
DEFINE('_ERRPHPERROR2','PHP ������� �����.  ����������, �������� �� �� 4.0.4 ��� ����� �������');
DEFINE('_ERRPHPMYSQLSUP','<B>���� PHP ��������</B>: <FONT>���������� ��������� MySQL, ����������� ��� 
               ������ ���� ��������������, �� �������� � PHP.  
               ����������, ���������������� PHP � ����������� ����������� (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>���� PHP ��������</B>: <FONT>���������� ��������� PostgreSQL, ����������� ��� 
               ������ ���� ��������������, �� �������� � PHP.  
               ����������, ���������������� PHP � ����������� ����������� (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>���� PHP ��������</B>: <FONT>���������� ��������� MS SQL Server, ����������� ���
               ������ ���� ��������������, �� �������� � PHP.  
               ����������, ���������������� PHP � ����������� �����������  (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP build incomplete</B>: <FONT>the prerequisite Oracle support required to 
                   read the alert database was not built into PHP.  
                   Please recompile PHP with the necessary library (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','��������� �������:');
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
DEFINE('_CHARTMONTH','{mês}'); //NEW
DEFINE('_GRAPHALERTS','Graph Alerts'); //NEW
DEFINE('_AXISCONTROLS','X / Y AXIS CONTROLS'); //NEW
DEFINE('_CHRTTYPEHOUR','����� (����) � ����� ��������������');
DEFINE('_CHRTTYPEDAY','����� (���) � ����� ��������������');
DEFINE('_CHRTTYPEWEEK','����� (������) � ����� ��������������');
DEFINE('_CHRTTYPEMONTH','����� (������) � ����� ��������������');
DEFINE('_CHRTTYPEYEAR','����� (����) � ����� ��������������');
DEFINE('_CHRTTYPESRCIP','IP-��������  � ����� ��������������');
DEFINE('_CHRTTYPEDSTIP','IP-���������� � ����� ��������������');
DEFINE('_CHRTTYPEDSTUDP','UDP ����-���������� � ����� ��������������');
DEFINE('_CHRTTYPESRCUDP','UDP ����-�������� � ����� ��������������');
DEFINE('_CHRTTYPEDSTPORT','TCP ����-���������� � ����� ��������������');
DEFINE('_CHRTTYPESRCPORT','TCP ����-�������� � ����� ��������������');
DEFINE('_CHRTTYPESIG','���. ������������� � ����� ��������������');
DEFINE('_CHRTTYPESENSOR','������ � ����� ��������������');
DEFINE('_CHRTBEGIN','������ �������:');
DEFINE('_CHRTEND','����� �������:');
DEFINE('_CHRTDS','�������� ������:');
DEFINE('_CHRTX','��� X');
DEFINE('_CHRTY','��� Y');
DEFINE('_CHRTMINTRESH','����������� ��������� ��������');
DEFINE('_CHRTROTAXISLABEL','��������� ����� �� ��� (90 ��������)');
DEFINE('_CHRTSHOWX','�������� ����� ����� ��� X');
DEFINE('_CHRTDISPLABELX','���������� ����� ��� X ������');
DEFINE('_CHRTDATAPOINTS','������ ������');
DEFINE('_CHRTYLOG','��������������� ��� Y');
DEFINE('_CHRTYGRID','���������� ����� ����� ��� Y');

//base_graph_main.php
DEFINE('_CHRTTITLE','������ BASE');
DEFINE('_ERRCHRTNOTYPE','�� ������ ��� �������');
DEFINE('_ERRNOAGSPEC','�� �� �������. ������������ ��� ��������������.');
DEFINE('_CHRTDATAIMPORT','������ ������� ������');
DEFINE('_CHRTTIMEVNUMBER','����� � ����� ��������������');
DEFINE('_CHRTTIME','�����');
DEFINE('_CHRTALERTOCCUR','������ ��������������');
DEFINE('_CHRTSIPNUMBER','IP-�������� � ����� ��������������');
DEFINE('_CHRTSIP','IP-��������');
DEFINE('_CHRTDIPALERTS','IP-���������� � ����� ��������������');
DEFINE('_CHRTDIP','IP-����������');
DEFINE('_CHRTUDPPORTNUMBER','UDP ���� (����������) � ����� ��������������');
DEFINE('_CHRTDUDPPORT','UDP ����-����������');
DEFINE('_CHRTSUDPPORTNUMBER','UDP ���� (��������) � ����� ��������������');
DEFINE('_CHRTSUDPPORT','UDP ����-��������');
DEFINE('_CHRTPORTDESTNUMBER','TCP ���� (����������) � ����� ��������������');
DEFINE('_CHRTPORTDEST','TCP ����-����������');
DEFINE('_CHRTPORTSRCNUMBER','TCP ���� (��������) � ����� ��������������');
DEFINE('_CHRTPORTSRC','TCP ����-��������');
DEFINE('_CHRTSIGNUMBER','���. ������������� � ����� ��������������');
DEFINE('_CHRTCLASS','�������������');
DEFINE('_CHRTSENSORNUMBER','������ � ����� ��������������');
DEFINE('_CHRTHANDLEPERIOD','����������� ������� ��� �������������');
DEFINE('_CHRTDUMP','������ ������ ...');
DEFINE('_CHRTDRAW','��������� �������');
DEFINE('_ERRCHRTNODATAPOINTS','��� ����� ������ ��� ���������');
DEFINE('_GRAPHALERTDATA','Graph Alert Data'); //NEW

//base_maintenance.php
DEFINE('_MAINTTITLE','���������');
DEFINE('_MNTPHP','���� PHP:');
DEFINE('_MNTCLIENT','������:');
DEFINE('_MNTSERVER','������:');
DEFINE('_MNTSERVERHW','HW �������:');
DEFINE('_MNTPHPVER','������ PHP:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','������� ���������������� PHP:');
DEFINE('_MNTPHPMODS','����������� ������:');
DEFINE('_MNTDBTYPE','��� DB:');
DEFINE('_MNTDBALV','������ ���������� DB:');
DEFINE('_MNTDBALERTNAME','��� �� ��������������:');
DEFINE('_MNTDBARCHNAME','��� �� ������:');
DEFINE('_MNTAIC','��� ���������� � ���������������:');
DEFINE('_MNTAICTE','����� �������:');
DEFINE('_MNTAICCE','���������� �������:');
DEFINE('_MNTIPAC','��� IP-�������');
DEFINE('_MNTIPACUSIP','���������� IP-���������:');
DEFINE('_MNTIPACDNSC','������������ DNS:');
DEFINE('_MNTIPACWC','������������ Whois:');
DEFINE('_MNTIPACUDIP','���������� IP-����������:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','�������� ���� (sid,cid)');
DEFINE('_QAALERTDELET','�������������� �������');
DEFINE('_QATRIGGERSIG','���������� ���������');
DEFINE('_QANORMALD','Normal Display'); //NEW
DEFINE('_QAPLAIND','Plain Display'); //NEW
DEFINE('_QANOPAYLOAD','Fast logging used so payload was discarded'); //NEW

//base_qry_common.php
DEFINE('_QCSIG','���������');
DEFINE('_QCIPADDR','IP ������');
DEFINE('_QCIPFIELDS','IP ����');
DEFINE('_QCTCPPORTS','TCP �����');
DEFINE('_QCTCPFLAGS','TCP �����');
DEFINE('_QCTCPFIELD','TCP ����');
DEFINE('_QCUDPPORTS','UDP �����');
DEFINE('_QCUDPFIELDS','UDP ����');
DEFINE('_QCICMPFIELDS','ICMP ����');
DEFINE('_QCDATA','������');
DEFINE('_QCERRCRITWARN','��������:');
DEFINE('_QCERRVALUE','��������');
DEFINE('_QCERRFIELD','����');
DEFINE('_QCERROPER','��������');
DEFINE('_QCERRDATETIME','����/�����');
DEFINE('_QCERRPAYLOAD','�������� ��������');
DEFINE('_QCERRIP','IP �����');
DEFINE('_QCERRIPTYPE','IP ����� ����');
DEFINE('_QCERRSPECFIELD',' ������(-�) � ���� ���������, �� ���������� ���� �� ���� �������.');
DEFINE('_QCERRSPECVALUE','������(-�) ��� ��������, �� �� ������� �������� ��� ������������ ��.');
DEFINE('_QCERRBOOLEAN','� �������� �������� ������� ��������� ����������, �� �� ������������ ���������� ��������� (����., AND, OR).');
DEFINE('_QCERRDATEVALUE','������(-�) ��� ������������(-��), ��� ������ ��������� ����/�����, �� �������� �� �������.');
DEFINE('_QCERRINVHOUR','(�������� �����) �� ������ �������� ���� ��� ���������� �������.');
DEFINE('_QCERRDATECRIT','������(-�), ��� ������������(-��), ��� ������ ��������� ����/�����, �� �������� �� �������.');
DEFINE('_QCERROPERSELECT','������(-�), �� �� ���� �������� �� ������.');
DEFINE('_QCERRDATEBOOL','������� ��������� ��������� ����/������� ��� ���������� ���������� ����� ���� (����., AND, OR).');
DEFINE('_QCERRPAYCRITOPER','������(-�) ��� �������� ��������, �� �������� (����., has, has not) �� ��� ������.');
DEFINE('_QCERRPAYCRITVALUE','������(-�) ��� �����������, ��� ��������� �������� ��������, �� �������� �� �������.');
DEFINE('_QCERRPAYBOOL','������� ��������� ��������� �������� ��� ����������� ��������� ����� ���� (����., AND, OR).');
DEFINE('_QCMETACRIT','���� ��������');
DEFINE('_QCIPCRIT','�������� IP');
DEFINE('_QCPAYCRIT','�������� ��������');
DEFINE('_QCTCPCRIT','�������� TCP');
DEFINE('_QCUDPCRIT','�������� UDP');
DEFINE('_QCICMPCRIT','�������� ICMP');
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QCERRINVIPCRIT','�������� ��������: IP �����');
DEFINE('_QCERRCRITADDRESSTYPE','������(-�) ��� �������� ��������, �� ��� ������ (����., ��������, ����������) �� ��� ������.');
DEFINE('_QCERRCRITIPADDRESSNONE','������������(-��), ��� IP ����� ������ ���� ���������, �� ����� �� ������.');
DEFINE('_QCERRCRITIPADDRESSNONE1','������(-�) (#');
DEFINE('_QCERRCRITIPIPBOOL','� �������� �������� ������� ��������� IP ������� ��� ����������� ��������� ����� ���� (����., AND, OR)');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','������� ����������');
DEFINE('_QFRMSORTNONE','none'); //NEW
DEFINE('_QFRMTIMEA','����� (����������)');
DEFINE('_QFRMTIMED','����� (����������)');
DEFINE('_QFRMSIG','���������');
DEFINE('_QFRMSIP','IP-��������');
DEFINE('_QFRMDIP','IP-����������');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','����� ����������');
DEFINE('_QSCTIMEPROF','��������� ��������');
DEFINE('_QSCOFALERTS','��������������');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','������ ��������������');

//base_stat_common.php
DEFINE('_SCCATEGORIES','���������:');
DEFINE('_SCSENSORTOTAL','�������/�����:');
DEFINE('_SCTOTALNUMALERTS','����� ���������� ��������������:');
DEFINE('_SCSRCIP','IP-��������:');
DEFINE('_SCDSTIP','IP-����������:');
DEFINE('_SCUNILINKS','���������� IP �����');
DEFINE('_SCSRCPORTS','�����-���������: ');
DEFINE('_SCDSTPORTS','�����-����������: ');
DEFINE('_SCSENSORS','�������');
DEFINE('_SCCLASS','�������������');
DEFINE('_SCUNIADDRESS','���������� ������: ');
DEFINE('_SCSOURCE','��������');
DEFINE('_SCDEST','����������');
DEFINE('_SCPORT','����');

//base_stat_ipaddr.php
DEFINE('_PSEVENTERR','������ ������� ������������ ������: ');
DEFINE('_PSEVENTERRNOFILE','�� ���� ���� �� ������ � ���������� $portscan_file.');
DEFINE('_PSEVENTERROPENFILE','�� ������� ������� ���� ������� ������������ ������');
DEFINE('_PSDATETIME','����/�����');
DEFINE('_PSSRCIP','IP-��������');
DEFINE('_PSDSTIP','IP-����������');
DEFINE('_PSSRCPORT','����-��������');
DEFINE('_PSDSTPORT','����-����������');
DEFINE('_PSTCPFLAGS','����� TCP');
DEFINE('_PSTOTALOCC','�����<BR> �������');
DEFINE('_PSNUMSENSORS','����� ��������');
DEFINE('_PSFIRSTOCC','������<BR> ������');
DEFINE('_PSLASTOCC','���������<BR> ������');
DEFINE('_PSUNIALERTS','���������� ��������������');
DEFINE('_PSPORTSCANEVE','������� ������������ ������');
DEFINE('_PSREGWHOIS','����� (whois) �');
DEFINE('_PSNODNS','�� �������� DNS-����������');
DEFINE('_PSNUMSENSORSBR','����� <BR>��������');
DEFINE('_PSOCCASSRC','������ <BR>��� ���������.');
DEFINE('_PSOCCASDST','������ <BR>��� ����������.');
DEFINE('_PSWHOISINFO','���������� Whois');
DEFINE('_PSTOTALHOSTS','Total Hosts Scanned'); //NEW
DEFINE('_PSDETECTAMONG','%d unique alerts detected among %d alerts on %s'); //NEW
DEFINE('_PSALLALERTSAS','all alerts with %s/%s as'); //NEW
DEFINE('_PSSHOW','show'); //NEW
DEFINE('_PSEXTERNAL','external'); //NEW

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP �����');
DEFINE('_SIPLSOURCEFGDN','�������� FQDN');
DEFINE('_SIPLDESTFGDN','���������� FQDN');
DEFINE('_SIPLDIRECTION','�����������');
DEFINE('_SIPLPROTO','��������');
DEFINE('_SIPLUNIDSTPORTS','���������� �����-����������');
DEFINE('_SIPLUNIEVENTS','���������� �������');
DEFINE('_SIPLTOTALEVENTS','����� �������');

//base_stat_ports.php
DEFINE('_UNIQ','����������');
DEFINE('_DSTPS','�����-����������');
DEFINE('_SRCPS','����-���������');
DEFINE('_OCCURRENCES','Occurrences'); //NEW

//base_stat_sensor.php
DEFINE('SPSENSORLIST','������ ��������');

//base_stat_time.php
DEFINE('_BSTTITLE','��������� ������� ��������������');
DEFINE('_BSTTIMECRIT','�������� �������');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>�� ������ ���������� ���������!</B>  ������� �� "����", "����", ��� "�����", ����� ������� ����������� ���������� ����������.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>�� ������ ��� ���������� ���������!</B>  �������� ��� "�", ����� ������� ���� ����, ��� "�����", ����� ������� ��������.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>�������� ��� �� ������!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>�������� ����� �� ������!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>�������� ���� �� ������!</B></FONT>');
DEFINE('_BSTPROFILEBY','Profile by'); //NEW
DEFINE('_TIMEON','on'); //NEW
DEFINE('_TIMEBETWEEN','between'); //NEW
DEFINE('_PROFILEALERT','Profile Alert'); //NEW

//base_stat_uaddr.php
DEFINE('_UNISADD','���������� ������-���������');
DEFINE('_SUASRCIP','IP-��������');
DEFINE('_SUAERRCRITADDUNK','������ ��������: ����������� ���� ������ -- �������������� �����-����������');
DEFINE('_UNIDADD','����������� ������-����������');
DEFINE('_SUADSTIP','IP-����������');
DEFINE('_SUAUNIALERTS','����������&nbsp;��������������');
DEFINE('_SUASRCADD','�����&nbsp;��������.');
DEFINE('_SUADSTADD','�����.&nbsp;����������');

//base_user.php
DEFINE('_BASEUSERTITLE','���������������� ��������� BASE');
DEFINE('_BASEUSERERRPWD','��� ������ �� ����� ���� ������ ��� ��� ������ �� �������!');
DEFINE('_BASEUSEROLDPWD','������ ������:');
DEFINE('_BASEUSERNEWPWD','����� ������:');
DEFINE('_BASEUSERNEWPWDAGAIN','��� ��� ����� ������:');

DEFINE('_LOGOUT','�����');

?>
