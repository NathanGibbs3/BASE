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
** Purpose: Turkish language file
**      To translate into another language, copy this file and
**          translate each variable into your chosen language.
**          Leave any variable not translated so that the system will have
**          something to display.
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net>
** Joel Esler <joelesler@users.sourceforge.net>
********************************************************************************
*/

//locale
DEFINE('_LOCALESTR1', 'tur_TUR.ISO8859-9');
DEFINE('_LOCALESTR2', 'tur_TUR.utf-8');
DEFINE('_LOCALESTR3', 'turkish');
DEFINE('_STRFTIMEFORMAT','%a %B %d, %Y %H:%M:%S'); //see strftime() sintax

//common phrases
DEFINE('_CHARSET','iso-8859-9');
DEFINE('_TITLE','Basic Analysis and Security Engine (BASE) '.$BASE_installID);
DEFINE('_FRMLOGIN','Oturum A�:');
DEFINE('_FRMPWD','Parola:');
DEFINE('_SOURCE','Kaynak');
DEFINE('_SOURCENAME','Kaynak Ad�');
DEFINE('_DEST','Var��');
DEFINE('_DESTNAME','Var�� Ad�');
DEFINE('_SORD','Kaynak veya Var��');
DEFINE('_EDIT','D�zenle');
DEFINE('_DELETE','Sil');
DEFINE('_ID','ID');
DEFINE('_NAME','Ad');
DEFINE('_INTERFACE','Arabirim');
DEFINE('_FILTER','S�zge�');
DEFINE('_DESC','Betimleme');
DEFINE('_LOGIN','Oturum A�');
DEFINE('_ROLEID','Rol ID');
DEFINE('_ENABLED','Se�ilir K�l�nm��');
DEFINE('_SUCCESS','Ba�ar�l�');
DEFINE('_SENSOR','Alg�lay�c�');
DEFINE('_SENSORS','Alg�lay�c�lar');
DEFINE('_SIGNATURE','�mza');
DEFINE('_TIMESTAMP','Zaman Damgas�');
DEFINE('_NBSOURCEADDR','Kaynak Adresi');
DEFINE('_NBDESTADDR','Var�� Adresi');
DEFINE('_NBLAYER4','4. Katman Protokol�');
DEFINE('_PRIORITY','�ncelik');
DEFINE('_EVENTTYPE','olay t�r�');
DEFINE('_JANUARY','Ocak');
DEFINE('_FEBRUARY','�ubat');
DEFINE('_MARCH','Mart');
DEFINE('_APRIL','Nisan');
DEFINE('_MAY','May�s');
DEFINE('_JUNE','Haziran');
DEFINE('_JULY','Temmuz');
DEFINE('_AUGUST','A�ustos');
DEFINE('_SEPTEMBER','Eyl�l');
DEFINE('_OCTOBER','Ekim');
DEFINE('_NOVEMBER','Kas�m');
DEFINE('_DECEMBER','Aral�k');
DEFINE('_LAST','Son');
DEFINE('_FIRST','�lk');
DEFINE('_TOTAL','Toplam');
DEFINE('_ALERT','Uyar�');
DEFINE('_ADDRESS','Adres');
DEFINE('_UNKNOWN','bilinmeyen');
DEFINE('_AND','VE');
DEFINE('_OR','YA DA');
DEFINE('_IS','is');
DEFINE('_ON','�zerinde');
DEFINE('_IN','i�inde');
DEFINE('_ANY','herhangibir');
DEFINE('_NONE','hi�biri');
DEFINE('_HOUR','Saat');
DEFINE('_DAY','G�n');
DEFINE('_MONTH','Ay');
DEFINE('_YEAR','Y�l');
DEFINE('_ALERTGROUP','Uyar� Grubu');
DEFINE('_ALERTTIME','Uyar� Zaman�');
DEFINE('_CONTAINS','kapsar');
DEFINE('_DOESNTCONTAIN','kapsamaz');
DEFINE('_SOURCEPORT','kaynak portu');
DEFINE('_DESTPORT','var�� portu');
DEFINE('_HAS','sahip');
DEFINE('_HASNOT','sahip de�il');
DEFINE('_PORT','Port');
DEFINE('_FLAGS','Bayraklar');
DEFINE('_MISC','Misc');
DEFINE('_BACK','Geri');
DEFINE('_DISPYEAR','{ y�l }');
DEFINE('_DISPMONTH','{ ay }');
DEFINE('_DISPHOUR','{ saat }');
DEFINE('_DISPDAY','{ g�n }');
DEFINE('_DISPTIME','{ zaman }');
DEFINE('_ADDADDRESS','Adres EKLE');
DEFINE('_ADDIPFIELD','IP Alan� EKLE');
DEFINE('_ADDTIME','ZAMAN EKLE');
DEFINE('_ADDTCPPORT','TCP Portu EKLE');
DEFINE('_ADDTCPFIELD','TCP Alan� EKLE');
DEFINE('_ADDUDPPORT','UDP Portu EKLE');
DEFINE('_ADDUDPFIELD','UDP Alan� EKLE');
DEFINE('_ADDICMPFIELD','ICMP Alan� EKLE');
DEFINE('_ADDPAYLOAD','Payload EKLE');
DEFINE('_MOSTFREQALERTS','En S�k Uyar�lar');
DEFINE('_MOSTFREQPORTS','En S�k Portlar');
DEFINE('_MOSTFREQADDRS','En S�k IP adresleri');
DEFINE('_LASTALERTS','Son Uyar�lar');
DEFINE('_LASTPORTS','Son Portlar');
DEFINE('_LASTTCP','Son TCP Uyar�lar�');
DEFINE('_LASTUDP','Son UDP Uyar�lar�');
DEFINE('_LASTICMP','Son ICMP Uyar�lar�');
DEFINE('_QUERYDB','Sorgu DB');
DEFINE('_QUERYDBP','Sorgu+DB'); //_QUERYDB 'ye e�it, bo�luklar '+' lard�r. 
                                //Bunun gibi bir �ey olmas� gerekli: DEFINE('_QUERYDBP',str_replace(" ", "+", _QUERYDB));
DEFINE('_SELECTED','Se�ilmi�');
DEFINE('_ALLONSCREEN','HEPS� Ekranda');
DEFINE('_ENTIREQUERY','B�t�n Sorgu');
DEFINE('_OPTIONS','Se�enekler');
DEFINE('_LENGTH','uzunluk');
DEFINE('_CODE','kod');
DEFINE('_DATA','veri');
DEFINE('_TYPE','t�r');
DEFINE('_NEXT','Sonraki');
DEFINE('_PREVIOUS','�nceki');

//Menu items
DEFINE('_HOME','Ev');
DEFINE('_SEARCH','Ara');
DEFINE('_AGMAINT','Uyar� Grubu Bak�m�');
DEFINE('_USERPREF','Kullan�c� Ye�lenenleri');
DEFINE('_CACHE','�nbellek & Durum');
DEFINE('_ADMIN','Y�netim');
DEFINE('_GALERTD','�izge Uyar� Verisi');
DEFINE('_GALERTDT','�izge Uyar�s� Alg�lama Zaman�');
DEFINE('_USERMAN','Kullan�c� Y�netimi');
DEFINE('_LISTU','Kullan�c�lar� Listele');
DEFINE('_CREATEU','Bir Kullan�c� Yarat');
DEFINE('_ROLEMAN','Rol Y�netimi');
DEFINE('_LISTR','Rolleri Listele');
DEFINE('_CREATER','Bir Rol Yarat');
DEFINE('_LISTALL','Hepsini Listele');
DEFINE('_CREATE','Yarat');
DEFINE('_VIEW','G�r�n�m');
DEFINE('_CLEAR','Temizle');
DEFINE('_LISTGROUPS','Gruplar� Listele');
DEFINE('_CREATEGROUPS','Grup Yarat');
DEFINE('_VIEWGROUPS','Grup G�r�nt�le');
DEFINE('_EDITGROUPS','Grup D�zenle');
DEFINE('_DELETEGROUPS','Grup Sil');
DEFINE('_CLEARGROUPS','Grup Temizle');
DEFINE('_CHNGPWD','Parola De�i�tir');
DEFINE('_DISPLAYU','Kullan�c� G�r�nt�le');

//base_footer.php
DEFINE('_FOOTER',' (by <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnson</A> and the <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','Kullan�c� ge�erli de�il ya da parolan�z yanl��!<br>L�tfen tekrar deneyin');

// base_main.php
DEFINE('_MOSTRECENT','En sondaki ');
DEFINE('_MOSTFREQUENT','En s�k ');
DEFINE('_ALERTS',' Uyar�lar:');
DEFINE('_ADDRESSES',' Adresler');
DEFINE('_ANYPROTO','herhangibir protokol');
DEFINE('_UNI','benzersiz');
DEFINE('_LISTING','listeleme');
DEFINE('_TALERTS','Bug�n\'�n uyar�lar�: ');
DEFINE('_SOURCEIP','Kaynak IP');
DEFINE('_DESTIP','Var�� IP');
DEFINE('_L24ALERTS','Son 24 Saatin uyar�lar�: ');
DEFINE('_L72ALERTS','Son 72 Saatin uyar�lar�: ');
DEFINE('_UNIALERTS',' Benzersiz Uyar�lar');
DEFINE('_LSOURCEPORTS','Son Kaynak Portlar�: ');
DEFINE('_LDESTPORTS','Son Var�� Portlar�: ');
DEFINE('_FREGSOURCEP','En S�k Kaynak Portlar�: ');
DEFINE('_FREGDESTP','En S�k Var�� Portlar�: ');
DEFINE('_QUERIED','Sorguland�');
DEFINE('_DATABASE','Veritaban�:');
DEFINE('_SCHEMAV','�ema S�r�m�:');
DEFINE('_TIMEWIN','Zaman Penceresi:');
DEFINE('_NOALERTSDETECT','hi�bir uyar� alg�lanmad�');
DEFINE('_USEALERTDB','Uyar� Veritaban�n� Kullan');
DEFINE('_USEARCHIDB','Ar�iv Veritaban�n� Kullan');
DEFINE('_TRAFFICPROBPRO','Protokole G�re Trafik Profili');

//base_auth.inc.php
DEFINE('_ADDEDSF','Ba�ar�l� Bi�imde Eklendi');
DEFINE('_NOPWDCHANGE','Parolan�z� de�i�tirmek olanaks�z: ');
DEFINE('_NOUSER','Kullan�c� ge�erli de�il!');
DEFINE('_OLDPWD','Girilen Eski parola kay�tlar�m�zla e�le�miyor!');
DEFINE('_PWDCANT','Parolan�z� de�i�tirmek olanaks�z: ');
DEFINE('_PWDDONE','Parolan�z de�i�tirildi!');
DEFINE('_ROLEEXIST','Rol Zaten Var');
DEFINE('_ROLEIDEXIST','Rol ID Zaten Var');
DEFINE('_ROLEADDED','Rol Ba�ar�l� Bi�imde Eklendi');

//base_roleadmin.php
DEFINE('_ROLEADMIN','BASE Rol Y�netimi');
DEFINE('_FRMROLEID','Rol ID:');
DEFINE('_FRMROLENAME','Rol Ad�:');
DEFINE('_FRMROLEDESC','Betimleme:');
DEFINE('_UPDATEROLE','Rol� G�ncelle');

//base_useradmin.php
DEFINE('_USERADMIN','BASE Kullan�c� Y�netimi');
DEFINE('_FRMFULLNAME','T�m Ad:');
DEFINE('_FRMROLE','Rol:');
DEFINE('_FRMUID','Kullan�c� ID:');
DEFINE('_SUBMITQUERY','Sorguyu Sun');
DEFINE('_UPDATEUSER','Kullan�c�y� G�ncelle');

//admin/index.php
DEFINE('_BASEADMIN','BASE Y�netimi');
DEFINE('_BASEADMINTEXT','L�tfen soldan bir se�enek se�iniz.');

//base_action.inc.php
DEFINE('_NOACTION','Uyar�larda hi�bir eylem belirlenmemi�');
DEFINE('_INVALIDACT',' ge�ersiz bir eylemdir');
DEFINE('_ERRNOAG','Hi�bir UG belirlenmedi�i i�in uyar�lar� ekleyemedi');
DEFINE('_ERRNOEMAIL','Email adresi belirlenmedi�i i�in uyar�lar� g�nderemedi');
DEFINE('_ACTION','EYLEM');
DEFINE('_CONTEXT','ba�lam');
DEFINE('_ADDAGID','UG\'na EKLE (ID yoluyla)');
DEFINE('_ADDAG','Yeni-UG-EKLE');
DEFINE('_ADDAGNAME','UG\'na EKLE (Ad yoluyla)');
DEFINE('_CREATEAG','UG Yarat (Ad yoluyla)');
DEFINE('_CLEARAG','UG\'dan Temizle');
DEFINE('_DELETEALERT','Uyar�(lar�) sil');
DEFINE('_EMAILALERTSFULL','Uyar�(lar�) Email\'e g�nder (t�m)');
DEFINE('_EMAILALERTSSUMM','Uyar�(lar�) Email\'e g�nder (�zet)');
DEFINE('_EMAILALERTSCSV','Uyar�(lar�) Email\'e g�nder (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Uyar�(lar�) ar�ivle (kopyala)');
DEFINE('_ARCHIVEALERTSMOVE','Uyar�(lar�) ar�ivle (ta��)');
DEFINE('_IGNORED','Yoksay�ld� ');
DEFINE('_DUPALERTS',' uyar�(lar�) �o�alt');
DEFINE('_ALERTSPARA',' uyar�(lar)');
DEFINE('_NOALERTSSELECT','Hi�bir uyar� se�ilmemi� ya da');
DEFINE('_NOTSUCCESSFUL','ba�ar�l� de�ildi');
DEFINE('_ERRUNKAGID','Bilinmeyen UG ID belirlenmi� (UG muhtemelen ge�erli de�il)');
DEFINE('_ERRREMOVEFAIL','Yeni UG\'nu ��karmak ba�ar�s�z oldu');
DEFINE('_GENBASE','BASE taraf�ndan �retildi');
DEFINE('_ERRNOEMAILEXP','DI�ARI AKTARIM HATASI: D��ar� aktar�lm�� uyar�lar� g�nderemedi');
DEFINE('_ERRNOEMAILPHP','PHP\'deki mail yap�land�rmas�n� kontrol et.');
DEFINE('_ERRDELALERT','Uyar� Silme Hatas�');
DEFINE('_ERRARCHIVE','Ar�iv hatas�:');
DEFINE('_ERRMAILNORECP','MAIL HATASI: Al�c� Belirlenmemi�');

//base_cache.inc.php
DEFINE('_ADDED','Ekledi ');
DEFINE('_HOSTNAMESDNS',' host isimlerini IP DNS �nbelle�ine');
DEFINE('_HOSTNAMESWHOIS',' host isimlerini Whois �nbelle�ine');
DEFINE('_ERRCACHENULL','�nbelle�e Alma HATASI: NULL olay s�ras� bulundu?');
DEFINE('_ERRCACHEERROR','OLAYI �NBELLE�E ALMA HATASI:');
DEFINE('_ERRCACHEUPDATE','Olay �nbelle�ini g�ncelleyemedi');
DEFINE('_ALERTSCACHE',' uyar�(lar�) Uyar� �nbelle�ine');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','SQL iz dosyas�n� a�mak olanaks�z');
DEFINE('_ERRSQLCONNECT','DB ba�lant� hatas� :');
DEFINE('_ERRSQLCONNECTINFO','<P><I>base_conf.php</I> dosyas�ndaki DB ba�lant� de�i�kenlerini kontrol edin.  
              <PRE>
               = $alert_dbname   : uyar�lar�n depoland��� MySQL veritaban� ad� 
               = $alert_host     : veritaban�n�n depoland��� host
               = $alert_port     : veritaban�n�n depoland��� port
               = $alert_user     : veritaban� i�indeki kullan�c�ad�
               = $alert_password : kullan�c�ad� i�in parola
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','DB (p)ba�lant� hatas� :');
DEFINE('_ERRSQLDB','Veritaban� HATASI:');
DEFINE('_DBALCHECK','DB soyutlama kitapl��� kontrol ediliyor');
DEFINE('_ERRSQLDBALLOAD1','<P><B>DB soyutlama kitapl��� y�kleme hatas�: </B> from ');
DEFINE('_ERRSQLDBALLOAD2','<P><CODE>base_conf.php</CODE> dosyas�ndaki <CODE>$DBlib_path</CODE> DB soyutlama kitapl��� de�i�kenini kontrol edin 
            <P>
            Y�r�rl�kte kullan�lan temel veritaban� kitapl��� ADODB\'dir ten indirilebilir
            ');
DEFINE('_ERRSQLDBTYPE','Ge�ersiz Veritaban� Tipi Belirlenmi�');
DEFINE('_ERRSQLDBTYPEINFO1','<CODE>base_conf.php</CODE> dosyas�ndaki <CODE>\$DBtype</CODE> de�i�keni tan�mlanmam�� veritaban� tipinde ayarlanm�� ');
DEFINE('_ERRSQLDBTYPEINFO2','Yaln�zca a�a��daki veritabanlar� desteklenmektedir: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','BASE ONARILAMAZ HATA:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Y�klendi');
DEFINE('_SECONDS','saniyede');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Adresi ��zmek olanaks�z');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Sorgu Sonu�lar� Sayfa Ba�l��� ��k���');

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','Bilinmeyen �mza�smi');
DEFINE('_ERRSIGPROIRITYUNK','Bilinmeyen �mza�nceli�i');
DEFINE('_UNCLASS','s�n�fland�r�lmam��');

//base_state_citems.inc.php
DEFINE('_DENCODED','veri �ifrelenmi�');
DEFINE('_NODENCODED','(veri d�n��t�rme yok, DB yerel �ifrelemedeki �l��t san�l�yor)');
DEFINE('_SHORTJAN','Oca');
DEFINE('_SHORTFEB','�ub');
DEFINE('_SHORTMAR','Mar');
DEFINE('_SHORTAPR','Nis');
DEFINE('_SHORTMAY','May');
DEFINE('_SHORTJUN','Haz');
DEFINE('_SHORTJLY','Tem');
DEFINE('_SHORTAUG','A�u');
DEFINE('_SHORTSEP','Eyl');
DEFINE('_SHORTOCT','Eki');
DEFINE('_SHORTNOV','Kas');
DEFINE('_SHORTDEC','Ara');
DEFINE('_DISPSIG','{ imza }');
DEFINE('_DISPANYCLASS','{ herhangibir S�n�fland�rma }');
DEFINE('_DISPANYPRIO','{ herhangibir �ncelik }');
DEFINE('_DISPANYSENSOR','{ herhangibir Sensor }');
DEFINE('_DISPADDRESS','{ adres }');
DEFINE('_DISPFIELD','{ alan }');
DEFINE('_DISPPORT','{ port }');
DEFINE('_DISPENCODING','{ �ifreleme }');
DEFINE('_DISPCONVERT2','{ D�n��t�r }');
DEFINE('_DISPANYAG','{ herhangibir Uyar� Grubu }');
DEFINE('_DISPPAYLOAD','{ payload }');
DEFINE('_DISPFLAGS','{ bayraklar }');
DEFINE('_SIGEXACTLY','tam olarak');
DEFINE('_SIGROUGHLY','yakla��k olarak');
DEFINE('_SIGCLASS','�mza S�n�fland�rma');
DEFINE('_SIGPRIO','�mza �nceli�i');
DEFINE('_SHORTSOURCE','Kaynak');
DEFINE('_SHORTDEST','Var��');
DEFINE('_SHORTSOURCEORDEST','Kaynak ya da Var��');
DEFINE('_NOLAYER4','4.katman yok');
DEFINE('_INPUTCRTENC','Girdi �l��t� �ifreleme Tipi');
DEFINE('_CONVERT2WS','D�n��t�r (ararken)');

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','PHP HATASI: �zel (kullan�c�) bir PHP oturumu saptand�. Ancak, BASE a��k�a bu �zel i�leyiciyi kullanmak �zere ayarlanmam��. <CODE>base_conf.php</CODE> dosyas�nda <CODE>use_user_session=1</CODE> olarak ayarlay�n');
DEFINE('_PHPERRORCSESSIONCODE','PHP HATASI: �zel (kullan�c�) bir PHP oturum i�leyicisi yap�land�r�lm��, fakat <CODE>user_session_path</CODE> \'teki belirlenmi� i�leyici kodu ge�ersiz.');
DEFINE('_PHPERRORCSESSIONVAR','PHP HATASI: �zel (kullan�c�) bir PHP oturum i�leyicisi yap�land�r�lm��, fakat bu i�leyicinin ger�ekle�tirilmesi BASE\'de belirlenmemi�. E�er �zel bir oturum i�leyici isteniyorsa, <CODE>base_conf.php</CODE> dosyas�ndaki <CODE>user_session_path</CODE> de�i�kenini ayarlay�n.');
DEFINE('_PHPSESSREG','Oturum Kaydedildi');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Kald�r�l�yor');
DEFINE('_FROMCRIT','�l��tten');
DEFINE('_ERRCRITELEM','Ge�ersiz �l��t ��esi');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Ge�erli Konservelenmi� Sorgu Listesi');
DEFINE('_DISPLAYING','G�r�nt�leniyor');
DEFINE('_DISPLAYINGTOTAL','%d-%d uyar�lar� g�r�nt�leniyor, %d toplamda');
DEFINE('_NOALERTS','Hi�bir Uyar� bulunamad�.');
DEFINE('_QUERYRESULTS','Sorgu Sonu�lar�');
DEFINE('_QUERYSTATE','Sorgu Durumu');
DEFINE('_DISPACTION','{ eylem }');

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','Belirtilen UG ad aramas� ge�ersiz.  Tekrar deneyin!');
DEFINE('_ERRAGNAMEEXIST','Belirtilen UG yok.');
DEFINE('_ERRAGIDSEARCH','Belirtilen UG ID aramas� ge�ersiz.  Tekrar deneyin!');
DEFINE('_ERRAGLOOKUP','UG ID arama Hatas�');
DEFINE('_ERRAGINSERT','Yeni UG Ekleme Hatas�');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Uyar� Grubu (UG) Bak�m�');
DEFINE('_ERRAGUPDATE','UG g�ncelleme Hatas�');
DEFINE('_ERRAGPACKETLIST','UG i�in paket listesi silme Hatas�:');
DEFINE('_ERRAGDELETE','UG silme Hatas�');
DEFINE('_AGDELETE','Ba�ar�l� bi�imde S�L�ND�');
DEFINE('_AGDELETEINFO','bilgi silindi');
DEFINE('_ERRAGSEARCHINV','Girilen arama �l��t� ge�ersiz.  Tekrar deneyin!');
DEFINE('_ERRAGSEARCHNOTFOUND','Bu �l��te g�re UG bulunamad�.');
DEFINE('_NOALERTGOUPS','Hi� Uyar� Grubu yok');
DEFINE('_NUMALERTS','# Uyar�lar');
DEFINE('_ACTIONS','Eylemler');
DEFINE('_NOTASSIGN','hen�z atanmam��');
DEFINE('_SAVECHANGES','De�i�iklikleri Kaydet');
DEFINE('_CONFIRMDELETE','Silmeyi Onayla');
DEFINE('_CONFIRMCLEAR','Temizlemeyi Onayla');

//base_common.php
DEFINE('_PORTSCAN','Portscan Trafi�i');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','INDEX YARATMAK Olanaks�z');
DEFINE('_DBINDEXCREATE','Ba�ar�l� bi�imde INDEX yarat�ld�');
DEFINE('_ERRSNORTVER','Eski bir s�r�m olabilir.  Sadece Snort 1.7-beta0 ve sonraki s�r�mler taraf�ndan yarat�lan uyar� veritabanlar� desteklenmektedir');
DEFINE('_ERRSNORTVER1','temel veritaban�');
DEFINE('_ERRSNORTVER2','eksik/ge�ersiz g�r�nmektedir');
DEFINE('_ERRDBSTRUCT1','veritaban� s�r�m� ge�erli, fakat BASE DB yap�s�');
DEFINE('_ERRDBSTRUCT2','sunulu de�il. <A HREF="base_db_setup.php">Setup sayfas�n�</A> kullanarak DB\'i yap�land�r�n ve optimize edin.');
DEFINE('_ERRPHPERROR','PHP HATASI');
DEFINE('_ERRPHPERROR1','Uyumsuz s�r�m');
DEFINE('_ERRVERSION','S�r�m�');
DEFINE('_ERRPHPERROR2',' PHP\'nin �ok eski.  L�tfen 4.0.4 veya sonraki bir s�r�me y�kseltin');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP in�as� eksik</B>: <FONT>uyar� veritaban�n� okumak i�in gerekli 
               �nko�ul Mysql deste�i PHP i�ine in�a edilmemi�.  
               L�tfen gerekli kitapl�k ile birlikte PHP\'yi yeniden derleyin (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP in�as� eksik</B>: <FONT>uyar� veritaban�n� okumak i�in gerekli 
               �nko�ul PostgreSQL deste�i PHP i�ine in�a edilmemi�.  
               L�tfen gerekli kitapl�k ile birlikte PHP\'yi yeniden derleyin (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP in�as� eksik</B>: <FONT>uyar� veritaban�n� okumak i�in gerekli 
                   �nko�ul MS SQL Server deste�i PHP i�ine in�a edilmemi�.  
                   L�tfen gerekli kitapl�k ile birlikte PHP\'yi yeniden derleyin (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP in�as� eksik</B>: <FONT>uyar� veritaban�n� okumak i�in gerekli 
                   �nko�ul Oracle deste�i PHP i�ine in�a edilmemi�.  
                   L�tfen gerekli kitapl�k ile birlikte PHP\'yi yeniden derleyin (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Grafik Ba�l���:');
DEFINE('_CHARTTYPE','Grafik Tipi:');
DEFINE('_CHARTTYPES','{ grafik tipi }');
DEFINE('_CHARTPERIOD','Grafik D�nemi:');
DEFINE('_PERIODNO','d�nem yok');
DEFINE('_PERIODWEEK','7 (bir hafta)');
DEFINE('_PERIODDAY','24 (b�t�n g�n)');
DEFINE('_PERIOD168','168 (24x7)');
DEFINE('_CHARTSIZE','Boyut: (en x y�kseklik)');
DEFINE('_PLOTMARGINS','�izim Bo�luklar�: (sol x sa� x �st x alt)');
DEFINE('_PLOTTYPE','�izim tipi:');
DEFINE('_TYPEBAR','�ubuk');
DEFINE('_TYPELINE','�izgi');
DEFINE('_TYPEPIE','pasta');
DEFINE('_CHARTHOUR','{sat}');
DEFINE('_CHARTDAY','{g�n}');
DEFINE('_CHARTMONTH','{ay}');
DEFINE('_GRAPHALERTS','�izge Uyar�lar�');
DEFINE('_AXISCONTROLS','X / Y EKSEN KONTROLLER�');
DEFINE('_CHRTTYPEHOUR','Zaman (saat) vs. Uyar� Say�s�');
DEFINE('_CHRTTYPEDAY','Zaman (g�n) vs. Uyar� Say�s�');
DEFINE('_CHRTTYPEWEEK','Zaman (hafta) vs. Uyar� Say�s�');
DEFINE('_CHRTTYPEMONTH','Zaman (ay) vs. Uyar� Say�s�');
DEFINE('_CHRTTYPEYEAR','Zaman (y�l) vs. Uyar� Say�s�');
DEFINE('_CHRTTYPESRCIP','Kaynak IP adresi vs. Uyar� Say�s�');
DEFINE('_CHRTTYPEDSTIP','Var�� IP adresi vs. Uyar� Say�s�');
DEFINE('_CHRTTYPEDSTUDP','Var�� UDP Portu vs. Uyar� Say�s�');
DEFINE('_CHRTTYPESRCUDP','Kynak UDP Portu vs. Uyar� Say�s�');
DEFINE('_CHRTTYPEDSTPORT','Var�� TCP Portu vs. Uyar� Say�s�');
DEFINE('_CHRTTYPESRCPORT','Kaynak TCP Portu vs. Uyar� Say�s�');
DEFINE('_CHRTTYPESIG','�mza S�n�flamas� vs. Uyar� Say�s�');
DEFINE('_CHRTTYPESENSOR','Sensor vs. Uyar� Say�s�');
DEFINE('_CHRTBEGIN','Grafik Ba�lang�c�:');
DEFINE('_CHRTEND','Grafik Sonu:');
DEFINE('_CHRTDS','Veri Kayna��:');
DEFINE('_CHRTX','X Ekseni');
DEFINE('_CHRTY','Y Ekseni');
DEFINE('_CHRTMINTRESH','En D���k E�ik De�eri');
DEFINE('_CHRTROTAXISLABEL','Eksen Etiketlerini D�nd�r (90 derece)');
DEFINE('_CHRTSHOWX','X-ekseni �zgara-�izgilerini g�ster');
DEFINE('_CHRTDISPLABELX','Her bir X-ekseni etiketini g�r�nt�le');
DEFINE('_CHRTDATAPOINTS','veri g�stergeleri');
DEFINE('_CHRTYLOG','Logaritmik Y-ekseni');
DEFINE('_CHRTYGRID','Y-ekseni �zgara-�izgilerini g�ster');

//base_graph_main.php
DEFINE('_CHRTTITLE','BASE Grafik');
DEFINE('_ERRCHRTNOTYPE','Hi�bir grafik tipi belirtilmemi�');
DEFINE('_ERRNOAGSPEC','Hi�bir UG belirtilmemi�.  T�m uyar�lar� kullan�yor.');
DEFINE('_CHRTDATAIMPORT','Veri aktar�m�n� ba�lat�yor');
DEFINE('_CHRTTIMEVNUMBER','Zaman vs. Uyar� Say�s�');
DEFINE('_CHRTTIME','Zaman');
DEFINE('_CHRTALERTOCCUR','Uyar� Meydana Geliyor');
DEFINE('_CHRTSIPNUMBER','Kaynak IP vs. Uyar� Say�s�');
DEFINE('_CHRTSIP','Kaynak IP Adresi');
DEFINE('_CHRTDIPALERTS','Var�� IP vs. Uyar� Say�s�');
DEFINE('_CHRTDIP','Var�� IP Adresi');
DEFINE('_CHRTUDPPORTNUMBER','UDP Portu (Var��) vs. Uyar� Say�s�');
DEFINE('_CHRTDUDPPORT','Var�� UDP Portu');
DEFINE('_CHRTSUDPPORTNUMBER','UDP Portu (Kaynak) vs. Uyar� Say�s�');
DEFINE('_CHRTSUDPPORT','Kaynak UDP Portu');
DEFINE('_CHRTPORTDESTNUMBER','TCP Portu (Var��) vs. Uyar� Say�s�');
DEFINE('_CHRTPORTDEST','Var�� TCP Portu');
DEFINE('_CHRTPORTSRCNUMBER','TCP Portu (Kaynak) vs. Uyar� Say�s�');
DEFINE('_CHRTPORTSRC','Kaynak TCP Portu');
DEFINE('_CHRTSIGNUMBER','�mza S�n�flamas� vs. Uyar� Say�s�');
DEFINE('_CHRTCLASS','S�n�flama');
DEFINE('_CHRTSENSORNUMBER','Sensor vs. Uyar� Say�s�');
DEFINE('_CHRTHANDLEPERIOD','��leme D�nemi, e�er gerekliyse');
DEFINE('_CHRTDUMP','Veriyi bo�alt�yor ... (her birini yaz�yor');
DEFINE('_CHRTDRAW','Grafi�i �iziyor');
DEFINE('_ERRCHRTNODATAPOINTS','�izecek hi� veri g�stergesi yok');
DEFINE('_GRAPHALERTDATA','Grafik Uyar� Verisi');

//base_maintenance.php
DEFINE('_MAINTTITLE','Bak�m');
DEFINE('_MNTPHP','PHP �n�as�:');
DEFINE('_MNTCLIENT','�STEMC�:');
DEFINE('_MNTSERVER','SUNUCU:');
DEFINE('_MNTSERVERHW','SUNUCU HW:');
DEFINE('_MNTPHPVER','PHP S�R�M�:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','PHP G�nl�kleme d�zeyi:');
DEFINE('_MNTPHPMODS','Y�kl� Mod�ller:');
DEFINE('_MNTDBTYPE','DB Tipi:');
DEFINE('_MNTDBALV','DB Soyutlama S�r�m�:');
DEFINE('_MNTDBALERTNAME','UYARI DB Ad�:');
DEFINE('_MNTDBARCHNAME','AR��V DB Ad�:');
DEFINE('_MNTAIC','Uyar� Bilgi �nbelle�i:');
DEFINE('_MNTAICTE','Toplam Olaylar:');
DEFINE('_MNTAICCE','�nbellekteki Olaylar:');
DEFINE('_MNTIPAC','IP Adres �nbelle�i');
DEFINE('_MNTIPACUSIP','Benzersiz Kaynak IP:');
DEFINE('_MNTIPACDNSC','DNS �nbelle�e al�nd�:');
DEFINE('_MNTIPACWC','Whois �nbelle�e al�nd�:');
DEFINE('_MNTIPACUDIP','Benzersiz Var�� IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Ge�ersiz (sid,cid) �ift');
DEFINE('_QAALERTDELET','Uyar� S�L�ND�');
DEFINE('_QATRIGGERSIG','Tetiklenmi� �mza');
DEFINE('_QANORMALD','Normal G�r�nt�');
DEFINE('_QAPLAIND','D�z G�r�nt�');
DEFINE('_QANOPAYLOAD','H�zl� g�nl�kleme kullan�ld� bu y�zden payload at�ld�');

//base_qry_common.php
DEFINE('_QCSIG','imza');
DEFINE('_QCIPADDR','IP adresleri');
DEFINE('_QCIPFIELDS','IP alanlar�');
DEFINE('_QCTCPPORTS','TCP portlar�');
DEFINE('_QCTCPFLAGS','TCP bayraklar�');
DEFINE('_QCTCPFIELD','TCP alanlar�');
DEFINE('_QCUDPPORTS','UDP portlar�');
DEFINE('_QCUDPFIELDS','UDP alanlar�');
DEFINE('_QCICMPFIELDS','ICMP alanlar�');
DEFINE('_QCDATA','Veri');
DEFINE('_QCERRCRITWARN','�l��t uyar�s�:');
DEFINE('_QCERRVALUE','de�eri');
DEFINE('_QCERRFIELD','alan�');
DEFINE('_QCERROPER','i�letmeni');
DEFINE('_QCERRDATETIME','tarih/zaman de�eri');
DEFINE('_QCERRPAYLOAD','payload de�eri');
DEFINE('_QCERRIP','IP adresi');
DEFINE('_QCERRIPTYPE','Tipin IP adresi');
DEFINE('_QCERRSPECFIELD',' bir protokol alan� i�in girildi, fakat �zel alan belirlenmemi�.');
DEFINE('_QCERRSPECVALUE','onun bir �l��t olmas� gerekti�ini g�stermek �zere se�ilmi�, fakat hangisiyle e�le�ece�ini g�steren hi�bir de�er belirlenmemi�.');
DEFINE('_QCERRBOOLEAN','Aralar�nda bir boolen i�leci olmadan (�rne�in; VE, YA DA) �oklu Protokol Alan �l��t� girildi.');
DEFINE('_QCERRDATEVALUE','baz� tarih/zaman �l��t�n�n e�le�mesi gerekti�ini g�stermek �zere se�ilmi�, fakat hi�bir de�er belirlenmemi�.');
DEFINE('_QCERRINVHOUR','(Ge�ersiz Saat) Belirtilen zamana uygun hi�bir tarih girilmemi�.');
DEFINE('_QCERRDATECRIT','baz� tarih/zaman �l��t�n�n e�le�mesi gerekti�ini g�stermek �zere se�ilmi�, fakat hi�bir de�er belirlenmemi�.');
DEFINE('_QCERROPERSELECT','girilmi� fakat hi�bir i�letici se�ilmemi�.');
DEFINE('_QCERRDATEBOOL','Aralar�nda bir boolen i�leci olmadan (�rne�in; VE, YA DA) �oklu Tarih/Zaman �l��t� girildi.');
DEFINE('_QCERRPAYCRITOPER','bir payload �l��t alan� i�in girilmi�, fakat bir i�letici (�rne�in; sahip, sahip de�il) belirtilmemi�.');
DEFINE('_QCERRPAYCRITVALUE','payload\'�n bir �l��t olmas� gerekti�ini g�stermek �zere se�ilmi�, fakat hangisiyle e�le�ece�ini g�steren hi�bir de�er belirlenmemi�.');
DEFINE('_QCERRPAYBOOL','Aralar�nda bir boolen i�leci olmadan (�rne�in; VE, YA DA) �oklu Veri payload �l��t� girildi.');
DEFINE('_QCMETACRIT','Meta �l��t�');
DEFINE('_QCIPCRIT','IP �l��t�');
DEFINE('_QCPAYCRIT','Payload �l��t�');
DEFINE('_QCTCPCRIT','TCP �l��t�');
DEFINE('_QCUDPCRIT','UDP �l��t�');
DEFINE('_QCICMPCRIT','ICMP �l��t�');
DEFINE('_QCLAYER4CRIT','4. Katman �l��t�');
DEFINE('_QCERRINVIPCRIT','Ge�ersiz IP adres �l��t�');
DEFINE('_QCERRCRITADDRESSTYPE','bir �l��t de�eri olmas� i�in girilmi�, fakat adresin tipi (�rne�in; kaynak, var��) belirlenmemi�.');
DEFINE('_QCERRCRITIPADDRESSNONE','bir IP adresinin bir �l��t olmas� gerekti�ini g�steriyor, fakat hangisiyle e�le�ece�ini g�steren hi�bir adres belirlenmemi�.');
DEFINE('_QCERRCRITIPADDRESSNONE1','se�ilmi� (#');
DEFINE('_QCERRCRITIPIPBOOL','IP �l��t� aras�nda bir boolen i�leci olmadan (�rne�in; VE, YA DA) �oklu IP adres �l��t� girildi');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','S�ralama d�zeni');
DEFINE('_QFRMSORTNONE','hi�biri');
DEFINE('_QFRMTIMEA','zaman damgas� (artan)');
DEFINE('_QFRMTIMED','zaman damgas� (azalan)');
DEFINE('_QFRMSIG','imza');
DEFINE('_QFRMSIP','kaynak IP');
DEFINE('_QFRMDIP','var�� IP');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','�statistik �zeti');
DEFINE('_QSCTIMEPROF','Zaman profili');
DEFINE('_QSCOFALERTS','uyar�lar�n');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','Uyar� Listeleme');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Kategoriler:');
DEFINE('_SCSENSORTOTAL','Sensorler/Toplam:');
DEFINE('_SCTOTALNUMALERTS','Toplam Uyar� Say�s�:');
DEFINE('_SCSRCIP','Kaynak IP adresi:');
DEFINE('_SCDSTIP','Var�� IP adresi:');
DEFINE('_SCUNILINKS','Benzersiz IP ba�lant�lar�');
DEFINE('_SCSRCPORTS','Kaynak Portlar�: ');
DEFINE('_SCDSTPORTS','Var�� Portlar�: ');
DEFINE('_SCSENSORS','Sensorler');
DEFINE('_SCCLASS','s�n�flamalar');
DEFINE('_SCUNIADDRESS','Benzersiz adresler: ');
DEFINE('_SCSOURCE','Kaynak');
DEFINE('_SCDEST','Var��');
DEFINE('_SCPORT','Port');

//base_stat_ipaddr.php
DEFINE('_PSEVENTERR','PORTSCAN OLAY HATASI: ');
DEFINE('_PSEVENTERRNOFILE','$portscan_file de�i�keninde hi�bir dosya belirtilmemi�');
DEFINE('_PSEVENTERROPENFILE','Portscan olay dosyas�n� a�mak olanaks�z');
DEFINE('_PSDATETIME','Tarih/Zaman');
DEFINE('_PSSRCIP','Kaynak IP');
DEFINE('_PSDSTIP','Var�� IP');
DEFINE('_PSSRCPORT','Kaynak Portu');
DEFINE('_PSDSTPORT','Var�� Portu');
DEFINE('_PSTCPFLAGS','TCP Bayraklar�');
DEFINE('_PSTOTALOCC','Toplam<BR> Olaylar');
DEFINE('_PSNUMSENSORS','Sensor Say�s�');
DEFINE('_PSFIRSTOCC','�lk<BR> Ger�ekle�en Olay');
DEFINE('_PSLASTOCC','Son<BR> Ger�ekle�en Olay');
DEFINE('_PSUNIALERTS','Benzersiz Uyar�lar');
DEFINE('_PSPORTSCANEVE','Portscan Olaylar�');
DEFINE('_PSREGWHOIS','Kay�t bak��� (whois)');
DEFINE('_PSNODNS','hi� DNS ��z�n�rl��� denenmedi');
DEFINE('_PSNUMSENSORSBR','Sensor <BR>Say�s�');
DEFINE('_PSOCCASSRC','Kaynak olarak <BR>Ortaya ��kanlar');
DEFINE('_PSOCCASDST','Var�� olarak <BR>Ortaya ��kanlar');
DEFINE('_PSWHOISINFO','Whois Bilgisi');
DEFINE('_PSTOTALHOSTS','Toplam Taranan Hostlar');
DEFINE('_PSDETECTAMONG','%d benzersiz uyar� saptand�, %d uyar� aras�nda, %s \'de');
DEFINE('_PSALLALERTSAS','t�m uyar�larla birlikte %s/%s olarak');
DEFINE('_PSSHOW','g�ster');
DEFINE('_PSEXTERNAL','d��');

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP Ba�lant�lar�');
DEFINE('_SIPLSOURCEFGDN','Kaynak FQDN');
DEFINE('_SIPLDESTFGDN','Var�� FQDN');
DEFINE('_SIPLDIRECTION','Y�n');
DEFINE('_SIPLPROTO','Protokol');
DEFINE('_SIPLUNIDSTPORTS','Benzersiz Var�� Portlar�');
DEFINE('_SIPLUNIEVENTS','Benzersiz Olaylar');
DEFINE('_SIPLTOTALEVENTS','Toplam Olaylar');

//base_stat_ports.php
DEFINE('_UNIQ','Benzersiz');
DEFINE('_DSTPS','Var�� Port(lar�)');
DEFINE('_SRCPS','Kaynak Port(lar�)');
DEFINE('_OCCURRENCES','Meydana Geliyor');

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Sensor Listeleme');

//base_stat_time.php
DEFINE('_BSTTITLE','Uyar�lar�n Zaman Profili');
DEFINE('_BSTTIMECRIT','Zaman �l��t�');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>Hi�bir profilleme �l��t� belirlenmemei�!</B>  "saat", "g�n", ya da "ay" �zerine t�klayarak k�melenmi� istatistiklerden taneli olan� se�in.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>Ge�ecek olan zaman parametresi tipi belirlenmemei�!</B>  Tek bir zaman belirtmek i�in "�zerinde", ya da bir aral�k belirtmek i�in "aras�nda" \'dan herhangi birini se�in.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>Hi�bir Y�l parametresi belirtilmemi�!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>Hi�bir Ay parametresi belirtilmemi�!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>Hi�bir G�n parametresi belirtilmemi�!</B></FONT>');
DEFINE('_BSTPROFILEBY','Profil taraf�ndan');
DEFINE('_TIMEON','�zerinde');
DEFINE('_TIMEBETWEEN','aras�nda');
DEFINE('_PROFILEALERT','Profil Uyar�s�');

//base_stat_uaddr.php
DEFINE('_UNISADD','Benzersiz Kaynak Adres(leri)');
DEFINE('_SUASRCIP','Kaynak IP adresi');
DEFINE('_SUAERRCRITADDUNK','�L��T HATASI: bilinmeyen adres tipi -- Var�� adresi oldu�u san�l�yor');
DEFINE('_UNIDADD','Benzersiz Var�� Adres(leri)');
DEFINE('_SUADSTIP','Var�� IP adresi');
DEFINE('_SUAUNIALERTS','Benzersiz Uyar�lar');
DEFINE('_SUASRCADD','Kaynak Adresi');
DEFINE('_SUADSTADD','Var�� Adresi');

//base_user.php
DEFINE('_BASEUSERTITLE','BASE Kullan�c� Ye�lenenleri');
DEFINE('_BASEUSERERRPWD','Parolan�z bo� olamaz ya da iki parola e�le�medi!');
DEFINE('_BASEUSEROLDPWD','Eski Parola:');
DEFINE('_BASEUSERNEWPWD','Yeni Parola:');
DEFINE('_BASEUSERNEWPWDAGAIN','Yeni Parola Tekrar:');

DEFINE('_LOGOUT','Oturumu Kapat');

?>
