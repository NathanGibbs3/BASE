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

$UI_Spacing = 1; // Inter Character Spacing.
$UI_ILC = 'tr'; // ISO 639-1 Language Code.
$UI_IRC = ''; // Region Code.
// Locales.
$UI_Locales = array( 'tur_TUR.ISO8859-9', 'tur_TUR.utf-8', 'turkish' );
// Time Format - See strftime() syntax.
$UI_Timefmt = '%a %B %d, %Y %H:%M:%S';
// UI Init.
$UI_Charset = 'iso-8859-9';
$UI_Title = 'Basic Analysis and Security Engine';
// Common Words.
$UI_CW_Edit = 'Düzenle';
$UI_CW_Delete = 'Sil';
$UI_CW_Src = 'Kaynak';
$UI_CW_Dst = 'Varıþ';
$UI_CW_Id = 'ID';
$UI_CW_Name = 'Ad';
$UI_CW_Int = 'Arabirim';
$UI_CW_Filter = 'Süzge�';
$UI_CW_Desc = 'Betimleme';
$UI_CW_SucDesc = 'Baþarılı';
$UI_CW_Sensor = 'Algılayıcı';
$UI_CW_Sig = 'Ýmza';
$UI_CW_Role = 'Rol';
$UI_CW_Pw = 'Parola';
$UI_CW_Ts = 'Zaman Damgası';
$UI_CW_Addr = 'Adresi';
$UI_CW_Layer = 'Katman';
$UI_CW_Proto = 'Protokolü';
$UI_CW_Pri = 'Öncelik';
$UI_CW_Event = 'Olay';
$UI_CW_Type = 'Tipi';
$UI_CW_ML1 = 'Ocak';
$UI_CW_ML2 = '�ubat';
$UI_CW_ML3 = 'Mart';
$UI_CW_ML4 = 'Nisan';
$UI_CW_ML5 = 'Mayıs';
$UI_CW_ML6 = 'Haziran';
$UI_CW_ML7 = 'Temmuz';
$UI_CW_ML8 = 'A�ustos';
$UI_CW_ML9 = 'Eylül';
$UI_CW_ML10 = 'Ekim';
$UI_CW_ML11 = 'Kasım';
$UI_CW_ML12 = 'Aralık';
$UI_CW_Last = 'Son';
$UI_CW_First = 'Ýlk';
$UI_CW_Total = 'Toplam';
$UI_CW_Alert = 'Uyarı';
// Common Phrases.
$UI_CP_SrcName = array($UI_CW_Src,$UI_CW_Name);
$UI_CP_DstName = array($UI_CW_Dst,$UI_CW_Name);
$UI_CP_SrcDst = array($UI_CW_Src,'veya',$UI_CW_Dst);
$UI_CP_SrcAddr = array($UI_CW_Src,$UI_CW_Addr);
$UI_CP_DstAddr = array($UI_CW_Dst,$UI_CW_Addr);
$UI_CP_L4P = array('4',$UI_CW_Layer,$UI_CW_Proto);
$UI_CP_ET = array($UI_CW_Event,$UI_CW_Type);
// Authentication Data.
$UI_AD_UND = 'Oturum A�';
$UI_AD_RID = array($UI_CW_Role,$UI_CW_Id);
$UI_AD_ASD = 'Se�ilir Kılınmıþ';

//common phrases
DEFINE('_ADDRESS','Adres');
DEFINE('_UNKNOWN','bilinmeyen');
DEFINE('_AND','VE');
DEFINE('_OR','YA DA');
DEFINE('_IS','is');
DEFINE('_ON','üzerinde');
DEFINE('_IN','i�inde');
DEFINE('_ANY','herhangibir');
DEFINE('_NONE','hi�biri');
DEFINE('_HOUR','Saat');
DEFINE('_DAY','Gün');
DEFINE('_MONTH','Ay');
DEFINE('_YEAR','Yıl');
DEFINE('_ALERTGROUP',$UI_CW_Alert.' Grubu');
DEFINE('_ALERTTIME',$UI_CW_Alert.' Zamanı');
DEFINE('_CONTAINS','kapsar');
DEFINE('_DOESNTCONTAIN','kapsamaz');
DEFINE('_SOURCEPORT','kaynak portu');
DEFINE('_DESTPORT','varıþ portu');
DEFINE('_HAS','sahip');
DEFINE('_HASNOT','sahip de�il');
DEFINE('_PORT','Port');
DEFINE('_FLAGS','Bayraklar');
DEFINE('_MISC','Misc');
DEFINE('_BACK','Geri');
DEFINE('_DISPYEAR','{ yıl }');
DEFINE('_DISPMONTH','{ ay }');
DEFINE('_DISPHOUR','{ saat }');
DEFINE('_DISPDAY','{ gün }');
DEFINE('_DISPTIME','{ zaman }');
DEFINE('_ADDADDRESS','Adres EKLE');
DEFINE('_ADDIPFIELD','IP Alanı EKLE');
DEFINE('_ADDTIME','ZAMAN EKLE');
DEFINE('_ADDTCPPORT','TCP Portu EKLE');
DEFINE('_ADDTCPFIELD','TCP Alanı EKLE');
DEFINE('_ADDUDPPORT','UDP Portu EKLE');
DEFINE('_ADDUDPFIELD','UDP Alanı EKLE');
DEFINE('_ADDICMPFIELD','ICMP Alanı EKLE');
DEFINE('_ADDPAYLOAD','Payload EKLE');
DEFINE('_MOSTFREQALERTS','En Sık '.$UI_CW_Alert.'lar');
DEFINE('_MOSTFREQPORTS','En Sık Portlar');
DEFINE('_MOSTFREQADDRS','En Sık IP adresleri');
DEFINE('_LASTALERTS',$UI_CW_Last.' '.$UI_CW_Alert.'lar');
DEFINE('_LASTPORTS',$UI_CW_Last.' Portlar');
DEFINE('_LASTTCP',$UI_CW_Last.' TCP '.$UI_CW_Alert.'ları');
DEFINE('_LASTUDP',$UI_CW_Last.' UDP '.$UI_CW_Alert.'ları');
DEFINE('_LASTICMP',$UI_CW_Last.' ICMP '.$UI_CW_Alert.'ları');
DEFINE('_QUERYDB','Sorgu DB');
DEFINE('_QUERYDBP','Sorgu+DB'); //_QUERYDB 'ye eþit, boþluklar '+' lardır. 
                                //Bunun gibi bir þey olması gerekli: DEFINE('_QUERYDBP',str_replace(" ", "+", _QUERYDB));
DEFINE('_SELECTED','Se�ilmiþ');
DEFINE('_ALLONSCREEN','HEPSÝ Ekranda');
DEFINE('_ENTIREQUERY','Bütün Sorgu');
DEFINE('_OPTIONS','Se�enekler');
DEFINE('_LENGTH','uzunluk');
DEFINE('_CODE','kod');
DEFINE('_DATA','veri');
DEFINE('_TYPE','tür');
DEFINE('_NEXT','Sonraki');
DEFINE('_PREVIOUS','Önceki');

//Menu items
DEFINE('_HOME','Ev');
DEFINE('_SEARCH','Ara');
DEFINE('_AGMAINT',$UI_CW_Alert.' Grubu Bakımı');
DEFINE('_USERPREF','Kullanıcı Ye�lenenleri');
DEFINE('_CACHE','Önbellek & Durum');
DEFINE('_ADMIN','Yönetim');
DEFINE('_GALERTD','�izge '.$UI_CW_Alert.' Verisi');
DEFINE('_GALERTDT','�izge '.$UI_CW_Alerts.'ı Algılama Zamanı');
DEFINE('_USERMAN','Kullanıcı Yönetimi');
DEFINE('_LISTU','Kullanıcıları Listele');
DEFINE('_CREATEU','Bir Kullanıcı Yarat');
DEFINE('_ROLEMAN',"$UI_CW_Role Yönetimi");
DEFINE('_LISTR',$UI_CW_Role.'leri Listele');
DEFINE('_CREATER',"Bir $UI_CW_Role Yarat");
DEFINE('_LISTALL','Hepsini Listele');
DEFINE('_CREATE','Yarat');
DEFINE('_VIEW','Görünüm');
DEFINE('_CLEAR','Temizle');
DEFINE('_LISTGROUPS','Grupları Listele');
DEFINE('_CREATEGROUPS','Grup Yarat');
DEFINE('_VIEWGROUPS','Grup Görüntüle');
DEFINE('_EDITGROUPS','Grup Düzenle');
DEFINE('_DELETEGROUPS','Grup Sil');
DEFINE('_CLEARGROUPS','Grup Temizle');
DEFINE('_CHNGPWD',"$UI_CW_Pw De�iþtir");
DEFINE('_DISPLAYU','Kullanıcı Görüntüle');

//base_footer.php
DEFINE('_FOOTER','(by <A class="largemenuitem" href="https://github.com/secureideas">Kevin Johnson</A> and the <A class="largemenuitem" href="https://github.com/NathanGibbs3/BASE/wiki/Project-Team">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','Kullanıcı ge�erli de�il ya da '.strtolower($UI_CW_Pw).'nız yanlıþ!<br>Lütfen tekrar deneyin');

// base_main.php
DEFINE('_MOSTRECENT','En sondaki ');
DEFINE('_MOSTFREQUENT','En sık ');
DEFINE('_ALERTS',' '.$UI_CW_Alert.'lar:');
DEFINE('_ADDRESSES',' Adresler');
DEFINE('_ANYPROTO','herhangibir protokol');
DEFINE('_UNI','benzersiz');
DEFINE('_LISTING','listeleme');
DEFINE('_TALERTS','Bugün\'ün '.$UI_CW_Alert.'ları: ');
DEFINE('_SOURCEIP','Kaynak IP');
DEFINE('_DESTIP','Varıþ IP');
DEFINE('_L24ALERTS',$UI_CW_Last.' 24 Saatin '.$UI_CW_Alert.'ları: ');
DEFINE('_L72ALERTS',$UI_CW_Last.' 72 Saatin '.$UI_CW_Alert.'ları: ');
DEFINE('_UNIALERTS',' Benzersiz '.$UI_CW_Alert.'lar');
DEFINE('_LSOURCEPORTS',$UI_CW_Last.' Kaynak Portları: ');
DEFINE('_LDESTPORTS',$UI_CW_Last.' Varıþ Portları: ');
DEFINE('_FREGSOURCEP','En Sık Kaynak Portları: ');
DEFINE('_FREGDESTP','En Sık Varıþ Portları: ');
DEFINE('_QUERIED','Sorgulandı');
DEFINE('_DATABASE','Veritabanı:');
DEFINE('_SCHEMAV','�ema Sürümü:');
DEFINE('_TIMEWIN','Zaman Penceresi:');
DEFINE('_NOALERTSDETECT','hi�bir '.$UI_CW_Alert.' algılanmadı');
DEFINE('_USEALERTDB',$UI_CW_Alert.' Veritabanını Kullan');
DEFINE('_USEARCHIDB','Arþiv Veritabanını Kullan');
DEFINE('_TRAFFICPROBPRO','Protokole Göre Trafik Profili');

//base_auth.inc.php
DEFINE('_ADDEDSF','Baþarılı Bi�imde Eklendi');
DEFINE('_NOPWDCHANGE',$UI_CW_Pw.'nızı de�iþtirmek olanaksız: ');
DEFINE('_NOUSER','Kullanıcı ge�erli de�il!');
DEFINE('_OLDPWD','Girilen Eski '.strtolower($UI_CW_Pw).' kayıtlarımızla eþleþmiyor!');
DEFINE('_PWDCANT',$UI_CW_Pw.'nızı de�iþtirmek olanaksız: ');
DEFINE('_PWDDONE',$UI_CW_Pw.'nız de�iþtirildi!');
DEFINE('_ROLEEXIST',"$UI_CW_Role Zaten Var");
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." Zaten Var");
DEFINE('_ROLEADDED',"$UI_CW_Role Baþarılı Bi�imde Eklendi");

//base_roleadmin.php
DEFINE('_ROLEADMIN',"BASE $UI_CW_Role Yönetimi");
DEFINE('_FRMROLENAME',"$UI_CW_Role Adı:");
DEFINE('_UPDATEROLE','Rolü Güncelle');

//base_useradmin.php
DEFINE('_USERADMIN','BASE Kullanıcı Yönetimi');
DEFINE('_FRMFULLNAME','Tüm Ad:');
DEFINE('_FRMUID','Kullanıcı ID:');
DEFINE('_SUBMITQUERY','Sorguyu Sun');
DEFINE('_UPDATEUSER','Kullanıcıyı Güncelle');

//admin/index.php
DEFINE('_BASEADMIN','BASE Yönetimi');
DEFINE('_BASEADMINTEXT','Lütfen soldan bir se�enek se�iniz.');

//base_action.inc.php
DEFINE('_NOACTION',$UI_CW_Alert.'larda hi�bir eylem belirlenmemiþ');
DEFINE('_INVALIDACT',' ge�ersiz bir eylemdir');
DEFINE('_ERRNOAG','Hi�bir UG belirlenmedi�i i�in '.$UI_CW_Alert.'ları ekleyemedi');
DEFINE('_ERRNOEMAIL','Email adresi belirlenmedi�i i�in '.$UI_CW_Alert.'ları gönderemedi');
DEFINE('_ACTION','EYLEM');
DEFINE('_CONTEXT','ba�lam');
DEFINE('_ADDAGID','UG\'na EKLE (ID yoluyla)');
DEFINE('_ADDAG','Yeni-UG-EKLE');
DEFINE('_ADDAGNAME','UG\'na EKLE (Ad yoluyla)');
DEFINE('_CREATEAG','UG Yarat (Ad yoluyla)');
DEFINE('_CLEARAG','UG\'dan Temizle');
DEFINE('_DELETEALERT',$UI_CW_Alert.'(ları) sil');
DEFINE('_EMAILALERTSFULL',$UI_CW_Alert.'(ları) Email\'e gönder (tüm)');
DEFINE('_EMAILALERTSSUMM',$UI_CW_Alert.'(ları) Email\'e gönder (özet)');
DEFINE('_EMAILALERTSCSV',$UI_CW_Alert.'(ları) Email\'e gönder (csv)');
DEFINE('_ARCHIVEALERTSCOPY',$UI_CW_Alert.'(ları) arþivle (kopyala)');
DEFINE('_ARCHIVEALERTSMOVE',$UI_CW_Alert.'(ları) arþivle (taþı)');
DEFINE('_IGNORED','Yoksayıldı ');
DEFINE('_DUPALERTS',' '.$UI_CW_Alert.'(ları) �o�alt');
DEFINE('_ALERTSPARA',' '.$UI_CW_Alert.'(lar)');
DEFINE('_NOALERTSSELECT','Hi�bir '.$UI_CW_Alert.' se�ilmemiþ ya da');
DEFINE('_NOTSUCCESSFUL','baþarılı de�ildi');
DEFINE('_ERRUNKAGID','Bilinmeyen UG ID belirlenmiþ (UG muhtemelen ge�erli de�il)');
DEFINE('_ERRREMOVEFAIL','Yeni UG\'nu �ıkarmak baþarısız oldu');
DEFINE('_GENBASE','BASE tarafından �retildi');
DEFINE('_ERRNOEMAILEXP','DI�ARI AKTARIM HATASI: Dıþarı aktarılmıþ '.$UI_CW_Alert.'ları gönderemedi');
DEFINE('_ERRNOEMAILPHP','PHP\'deki mail yapılandırmasını kontrol et.');
DEFINE('_ERRDELALERT',$UI_CW_Alert.' Silme Hatası');
DEFINE('_ERRARCHIVE','Arþiv hatası:');
DEFINE('_ERRMAILNORECP','MAIL HATASI: Alıcı Belirlenmemiþ');

//base_cache.inc.php
DEFINE('_ADDED','Ekledi ');
DEFINE('_HOSTNAMESDNS',' host isimlerini IP DNS önbelle�ine');
DEFINE('_HOSTNAMESWHOIS',' host isimlerini Whois önbelle�ine');
DEFINE('_ERRCACHENULL','Önbelle�e Alma HATASI: NULL '.$UI_CW_Event.' sırası bulundu?');
DEFINE('_ERRCACHEERROR',$UI_CW_Event.'I ÖNBELLE�E ALMA HATASI:');
DEFINE('_ERRCACHEUPDATE',$UI_CW_Event.' önbelle�ini güncelleyemedi');
DEFINE('_ALERTSCACHE',' '.$UI_CW_Alert.'(ları) '.$UI_CW_Alert.' önbelle�ine');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','SQL iz dosyasını a�mak olanaksız');
DEFINE('_ERRSQLCONNECT','DB ba�lantı hatası :');
DEFINE('_ERRSQLCONNECTINFO','<P><I>base_conf.php</I> dosyasındaki DB ba�lantı de�iþkenlerini kontrol edin.  
              <PRE>
               = $alert_dbname   : '.$UI_CW_Alert.'ların depolandı�ı MySQL veritabanı adı 
               = $alert_host     : veritabanının depolandı�ı host
               = $alert_port     : veritabanının depolandı�ı port
               = $alert_user     : veritabanı i�indeki kullanıcıadı
               = $alert_password : kullanıcıadı i�in '.strtolower($UI_CW_Pw).'
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','DB (p)ba�lantı hatası :');
DEFINE('_ERRSQLDB','Veritabanı HATASI:');
DEFINE('_DBALCHECK','DB soyutlama kitaplı�ı kontrol ediliyor');
DEFINE('_ERRSQLDBALLOAD1','<P><B>DB soyutlama kitaplı�ı yükleme hatası: </B> from ');
DEFINE('_ERRSQLDBALLOAD2','<P><CODE>base_conf.php</CODE> dosyasındaki <CODE>$DBlib_path</CODE> DB soyutlama kitaplı�ı de�iþkenini kontrol edin 
            <P>
            Yürürlükte kullanılan temel veritabanı kitaplı�ı ADODB\'dir ten indirilebilir
            ');
DEFINE('_ERRSQLDBTYPE','Ge�ersiz Veritabanı '.$UI_CW_Type.' Belirlenmiþ');
DEFINE('_ERRSQLDBTYPEINFO1','<CODE>base_conf.php</CODE> dosyasındaki <CODE>\$DBtype</CODE> de�iþkeni tanımlanmamıþ veritabanı '.$UI_CW_Type.'nde ayarlanmıþ ');
DEFINE('_ERRSQLDBTYPEINFO2','Yalnızca aþa�ıdaki veritabanları desteklenmektedir: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','BASE ONARILAMAZ HATA:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Yüklendi');
DEFINE('_SECONDS','saniyede');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Adresi �özmek olanaksız');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Sorgu Sonu�ları Sayfa Baþlı�ı �ıkıþı');

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','Bilinmeyen ÝmzaÝsmi');
DEFINE('_ERRSIGPROIRITYUNK','Bilinmeyen ÝmzaÖnceli�i');
DEFINE('_UNCLASS','sınıflandırılmamıþ');

//base_state_citems.inc.php
DEFINE('_DENCODED','veri þifrelenmiþ');
DEFINE('_NODENCODED','(veri dönüþtürme yok, DB yerel þifrelemedeki öl�üt sanılıyor)');
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
DEFINE('_DISPANYCLASS','{ herhangibir Sınıflandırma }');
DEFINE('_DISPANYPRIO','{ herhangibir Öncelik }');
DEFINE('_DISPANYSENSOR','{ herhangibir Sensor }');
DEFINE('_DISPADDRESS','{ adres }');
DEFINE('_DISPFIELD','{ alan }');
DEFINE('_DISPPORT','{ port }');
DEFINE('_DISPENCODING','{ þifreleme }');
DEFINE('_DISPCONVERT2','{ Dönüþtür }');
DEFINE('_DISPANYAG','{ herhangibir '.$UI_CW_Alert.' Grubu }');
DEFINE('_DISPPAYLOAD','{ payload }');
DEFINE('_DISPFLAGS','{ bayraklar }');
DEFINE('_SIGEXACTLY','tam olarak');
DEFINE('_SIGROUGHLY','yaklaþık olarak');
DEFINE('_SIGCLASS','Ýmza Sınıflandırma');
DEFINE('_SIGPRIO','Ýmza Önceli�i');
DEFINE('_SHORTSOURCE','Kaynak');
DEFINE('_SHORTDEST','Varıþ');
DEFINE('_SHORTSOURCEORDEST','Kaynak ya da Varıþ');
DEFINE('_NOLAYER4','4.katman yok');
DEFINE('_INPUTCRTENC','Girdi Öl�ütü �ifreleme '.$UI_CW_Type);
DEFINE('_CONVERT2WS','Dönüþtür (ararken)');

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','PHP HATASI: Özel (kullanıcı) bir PHP oturumu saptandı. Ancak, BASE a�ık�a bu özel iþleyiciyi kullanmak üzere ayarlanmamıþ. <CODE>base_conf.php</CODE> dosyasında <CODE>use_user_session=1</CODE> olarak ayarlayın');
DEFINE('_PHPERRORCSESSIONCODE','PHP HATASI: Özel (kullanıcı) bir PHP oturum iþleyicisi yapılandırılmıþ, fakat <CODE>user_session_path</CODE> \'teki belirlenmiþ iþleyici kodu ge�ersiz.');
DEFINE('_PHPERRORCSESSIONVAR','PHP HATASI: Özel (kullanıcı) bir PHP oturum iþleyicisi yapılandırılmıþ, fakat bu iþleyicinin ger�ekleþtirilmesi BASE\'de belirlenmemiþ. E�er özel bir oturum iþleyici isteniyorsa, <CODE>base_conf.php</CODE> dosyasındaki <CODE>user_session_path</CODE> de�iþkenini ayarlayın.');
DEFINE('_PHPSESSREG','Oturum Kaydedildi');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Kaldırılıyor');
DEFINE('_FROMCRIT','öl�ütten');
DEFINE('_ERRCRITELEM','Ge�ersiz öl�üt ö�esi');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Ge�erli Konservelenmiþ Sorgu Listesi');
DEFINE('_DISPLAYING','Görüntüleniyor');
DEFINE('_DISPLAYINGTOTAL','%d-%d '.$UI_CW_Alert.'ları görüntüleniyor, %d '.$UI_CW_Total);
DEFINE('_NOALERTS','Hi�bir '.$UI_CW_Alert.' bulunamadı.');
DEFINE('_QUERYRESULTS','Sorgu Sonu�ları');
DEFINE('_QUERYSTATE','Sorgu Durumu');
DEFINE('_DISPACTION','{ eylem }');

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','Belirtilen UG ad araması ge�ersiz.  Tekrar deneyin!');
DEFINE('_ERRAGNAMEEXIST','Belirtilen UG yok.');
DEFINE('_ERRAGIDSEARCH','Belirtilen UG ID araması ge�ersiz.  Tekrar deneyin!');
DEFINE('_ERRAGLOOKUP','UG ID arama Hatası');
DEFINE('_ERRAGINSERT','Yeni UG Ekleme Hatası');

//base_ag_main.php
DEFINE('_AGMAINTTITLE',$UI_CW_Alert.' Grubu (UG) Bakımı');
DEFINE('_ERRAGUPDATE','UG güncelleme Hatası');
DEFINE('_ERRAGPACKETLIST','UG i�in paket listesi silme Hatası:');
DEFINE('_ERRAGDELETE','UG silme Hatası');
DEFINE('_AGDELETE','Baþarılı bi�imde SÝLÝNDÝ');
DEFINE('_AGDELETEINFO','bilgi silindi');
DEFINE('_ERRAGSEARCHINV','Girilen arama öl�ütü ge�ersiz.  Tekrar deneyin!');
DEFINE('_ERRAGSEARCHNOTFOUND','Bu öl�üte göre UG bulunamadı.');
DEFINE('_NOALERTGOUPS','Hi� '.$UI_CW_Alert.' Grubu yok');
DEFINE('_NUMALERTS','# '.$UI_CW_Alert.'lar');
DEFINE('_ACTIONS','Eylemler');
DEFINE('_NOTASSIGN','henüz atanmamıþ');
DEFINE('_SAVECHANGES','De�iþiklikleri Kaydet');
DEFINE('_CONFIRMDELETE','Silmeyi Onayla');
DEFINE('_CONFIRMCLEAR','Temizlemeyi Onayla');

//base_common.php
DEFINE('_PORTSCAN','Portscan Trafi�i');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','INDEX YARATMAK Olanaksız');
DEFINE('_DBINDEXCREATE','Baþarılı bi�imde INDEX yaratıldı');
DEFINE('_ERRSNORTVER','Eski bir sürüm olabilir.  Sadece Snort 1.7-beta0 ve sonraki sürümler tarafından yaratılan '.$UI_CW_Alert.' veritabanları desteklenmektedir');
DEFINE('_ERRSNORTVER1','temel veritabanı');
DEFINE('_ERRSNORTVER2','eksik/ge�ersiz görünmektedir');
DEFINE('_ERRDBSTRUCT1','veritabanı sürümü ge�erli, fakat BASE DB yapısı');
DEFINE('_ERRDBSTRUCT2','sunulu de�il. <A HREF="base_db_setup.php">Setup sayfasını</A> kullanarak DB\'i yapılandırın ve optimize edin.');
DEFINE('_ERRPHPERROR','PHP HATASI');
DEFINE('_ERRPHPERROR1','Uyumsuz sürüm');
DEFINE('_ERRVERSION','Sürümü');
DEFINE('_ERRPHPERROR2',' PHP\'nin �ok eski.  Lütfen 4.0.4 veya sonraki bir sürüme yükseltin');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP inþası eksik</B>: <FONT>'.$UI_CW_Alert.' veritabanını okumak i�in gerekli 
               önkoþul Mysql deste�i PHP i�ine inþa edilmemiþ.  
               Lütfen gerekli kitaplık ile birlikte PHP\'yi yeniden derleyin (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP inþası eksik</B>: <FONT>'.$UI_CW_Alert.' veritabanını okumak i�in gerekli 
               önkoþul PostgreSQL deste�i PHP i�ine inþa edilmemiþ.  
               Lütfen gerekli kitaplık ile birlikte PHP\'yi yeniden derleyin (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP inþası eksik</B>: <FONT>'.$UI_CW_Alert.' veritabanını okumak i�in gerekli 
                   önkoþul MS SQL Server deste�i PHP i�ine inþa edilmemiþ.  
                   Lütfen gerekli kitaplık ile birlikte PHP\'yi yeniden derleyin (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP inþası eksik</B>: <FONT>'.$UI_CW_Alert.' veritabanını okumak i�in gerekli 
                   önkoþul Oracle deste�i PHP i�ine inþa edilmemiþ.  
                   Lütfen gerekli kitaplık ile birlikte PHP\'yi yeniden derleyin (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Grafik Baþlı�ı:');
DEFINE('_CHARTTYPE','Grafik '.$UI_CW_Type.':');
DEFINE('_CHARTTYPES','{ grafik '.$UI_CW_Type.' }');
DEFINE('_CHARTPERIOD','Grafik Dönemi:');
DEFINE('_PERIODNO','dönem yok');
DEFINE('_PERIODWEEK','7 (bir hafta)');
DEFINE('_PERIODDAY','24 (bütün gün)');
DEFINE('_PERIOD168','168 (24x7)');
DEFINE('_CHARTSIZE','Boyut: (en x yükseklik)');
DEFINE('_PLOTMARGINS','�izim Boþlukları: (sol x sa� x üst x alt)');
DEFINE('_PLOTTYPE','�izim '.$UI_CW_Type.':');
DEFINE('_TYPEBAR','�ubuk');
DEFINE('_TYPELINE','�izgi');
DEFINE('_TYPEPIE','pasta');
DEFINE('_CHARTHOUR','{sat}');
DEFINE('_CHARTDAY','{gün}');
DEFINE('_CHARTMONTH','{ay}');
DEFINE('_GRAPHALERTS','�izge '.$UI_CW_Alert.'ları');
DEFINE('_AXISCONTROLS','X / Y EKSEN KONTROLLERÝ');
DEFINE('_CHRTTYPEHOUR','Zaman (saat) vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPEDAY','Zaman (gün) vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPEWEEK','Zaman (hafta) vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPEMONTH','Zaman (ay) vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPEYEAR','Zaman (yıl) vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPESRCIP','Kaynak IP adresi vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPEDSTIP','Varıþ IP adresi vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPEDSTUDP','Varıþ UDP Portu vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPESRCUDP','Kynak UDP Portu vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPEDSTPORT','Varıþ TCP Portu vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPESRCPORT','Kaynak TCP Portu vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPESIG','Ýmza Sınıflaması vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTYPESENSOR','Sensor vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTBEGIN','Grafik Baþlangıcı:');
DEFINE('_CHRTEND','Grafik Sonu:');
DEFINE('_CHRTDS','Veri Kayna�ı:');
DEFINE('_CHRTX','X Ekseni');
DEFINE('_CHRTY','Y Ekseni');
DEFINE('_CHRTMINTRESH','En Düþük Eþik De�eri');
DEFINE('_CHRTROTAXISLABEL','Eksen Etiketlerini Döndür (90 derece)');
DEFINE('_CHRTSHOWX','X-ekseni ızgara-�izgilerini göster');
DEFINE('_CHRTDISPLABELX','Her bir X-ekseni etiketini görüntüle');
DEFINE('_CHRTDATAPOINTS','veri göstergeleri');
DEFINE('_CHRTYLOG','Logaritmik Y-ekseni');
DEFINE('_CHRTYGRID','Y-ekseni ızgara-�izgilerini göster');

//base_graph_main.php
DEFINE('_CHRTTITLE','BASE Grafik');
DEFINE('_ERRCHRTNOTYPE','Hi�bir grafik '.$UI_CW_Type.' belirtilmemiþ');
DEFINE('_ERRNOAGSPEC','Hi�bir UG belirtilmemiþ.  Tüm '.$UI_CW_Alert.'ları kullanıyor.');
DEFINE('_CHRTDATAIMPORT','Veri aktarımını baþlatıyor');
DEFINE('_CHRTTIMEVNUMBER','Zaman vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTTIME','Zaman');
DEFINE('_CHRTALERTOCCUR',$UI_CW_Alert.' Meydana Geliyor');
DEFINE('_CHRTSIPNUMBER','Kaynak IP vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTSIP','Kaynak IP Adresi');
DEFINE('_CHRTDIPALERTS','Varıþ IP vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTDIP','Varıþ IP Adresi');
DEFINE('_CHRTUDPPORTNUMBER','UDP Portu (Varıþ) vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTDUDPPORT','Varıþ UDP Portu');
DEFINE('_CHRTSUDPPORTNUMBER','UDP Portu (Kaynak) vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTSUDPPORT','Kaynak UDP Portu');
DEFINE('_CHRTPORTDESTNUMBER','TCP Portu (Varıþ) vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTPORTDEST','Varıþ TCP Portu');
DEFINE('_CHRTPORTSRCNUMBER','TCP Portu (Kaynak) vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTPORTSRC','Kaynak TCP Portu');
DEFINE('_CHRTSIGNUMBER','Ýmza Sınıflaması vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTCLASS','Sınıflama');
DEFINE('_CHRTSENSORNUMBER','Sensor vs. '.$UI_CW_Alert.' Sayısı');
DEFINE('_CHRTHANDLEPERIOD','Ýþleme Dönemi, e�er gerekliyse');
DEFINE('_CHRTDUMP','Veriyi boþaltıyor ... (her birini yazıyor');
DEFINE('_CHRTDRAW','Grafi�i �iziyor');
DEFINE('_ERRCHRTNODATAPOINTS','�izecek hi� veri göstergesi yok');
DEFINE('_GRAPHALERTDATA','Grafik '.$UI_CW_Alert.' Verisi');

//base_maintenance.php
DEFINE('_MAINTTITLE','Bakım');
DEFINE('_MNTPHP','PHP Ýnþası:');
DEFINE('_MNTCLIENT','ÝSTEMCÝ:');
DEFINE('_MNTSERVER','SUNUCU:');
DEFINE('_MNTSERVERHW','SUNUCU HW:');
DEFINE('_MNTPHPVER','PHP S�R�M�:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','PHP Günlükleme düzeyi:');
DEFINE('_MNTPHPMODS','Yüklü Modüller:');
DEFINE('_MNTDBTYPE','DB '.$UI_CW_Type.':');
DEFINE('_MNTDBALV','DB Soyutlama Sürümü:');
DEFINE('_MNTDBALERTNAME','UYARI DB Adı:');
DEFINE('_MNTDBARCHNAME','AR�ÝV DB Adı:');
DEFINE('_MNTAIC',$UI_CW_Alert.' Bilgi Önbelle�i:');
DEFINE('_MNTAICTE',$UI_CW_Total.' '.$UI_CW_Event.'lar:');
DEFINE('_MNTAICCE','Önbellekteki '.$UI_CW_Event.'lar:');
DEFINE('_MNTIPAC','IP Adres Önbelle�i');
DEFINE('_MNTIPACUSIP','Benzersiz Kaynak IP:');
DEFINE('_MNTIPACDNSC','DNS Önbelle�e alındı:');
DEFINE('_MNTIPACWC','Whois Önbelle�e alındı:');
DEFINE('_MNTIPACUDIP','Benzersiz Varıþ IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Ge�ersiz (sid,cid) �ift');
DEFINE('_QAALERTDELET',$UI_CW_Alert.' SÝLÝNDÝ');
DEFINE('_QATRIGGERSIG','Tetiklenmiþ Ýmza');
DEFINE('_QANORMALD','Normal Görüntü');
DEFINE('_QAPLAIND','Düz Görüntü');
DEFINE('_QANOPAYLOAD','Hızlı günlükleme kullanıldı bu yüzden payload atıldı');

//base_qry_common.php
DEFINE('_QCSIG','imza');
DEFINE('_QCIPADDR','IP adresleri');
DEFINE('_QCIPFIELDS','IP alanları');
DEFINE('_QCTCPPORTS','TCP portları');
DEFINE('_QCTCPFLAGS','TCP bayrakları');
DEFINE('_QCTCPFIELD','TCP alanları');
DEFINE('_QCUDPPORTS','UDP portları');
DEFINE('_QCUDPFIELDS','UDP alanları');
DEFINE('_QCICMPFIELDS','ICMP alanları');
DEFINE('_QCDATA','Veri');
DEFINE('_QCERRCRITWARN','Öl�üt '.$UI_CW_Alert.'sı:');
DEFINE('_QCERRVALUE','de�eri');
DEFINE('_QCERRFIELD','alanı');
DEFINE('_QCERROPER','iþletmeni');
DEFINE('_QCERRDATETIME','tarih/zaman de�eri');
DEFINE('_QCERRPAYLOAD','payload de�eri');
DEFINE('_QCERRIP','IP adresi');
DEFINE('_QCERRIPTYPE',$UI_CW_Type.'n IP adresi');
DEFINE('_QCERRSPECFIELD',' bir protokol alanı i�in girildi, fakat özel alan belirlenmemiþ.');
DEFINE('_QCERRSPECVALUE','onun bir öl�üt olması gerekti�ini göstermek üzere se�ilmiþ, fakat hangisiyle eþleþece�ini gösteren hi�bir de�er belirlenmemiþ.');
DEFINE('_QCERRBOOLEAN','Aralarında bir boolen iþleci olmadan (örne�in; VE, YA DA) �oklu Protokol Alan öl�ütü girildi.');
DEFINE('_QCERRDATEVALUE','bazı tarih/zaman öl�ütünün eþleþmesi gerekti�ini göstermek üzere se�ilmiþ, fakat hi�bir de�er belirlenmemiþ.');
DEFINE('_QCERRINVHOUR','(Ge�ersiz Saat) Belirtilen zamana uygun hi�bir tarih girilmemiþ.');
DEFINE('_QCERRDATECRIT','bazı tarih/zaman öl�ütünün eþleþmesi gerekti�ini göstermek üzere se�ilmiþ, fakat hi�bir de�er belirlenmemiþ.');
DEFINE('_QCERROPERSELECT','girilmiþ fakat hi�bir iþletici se�ilmemiþ.');
DEFINE('_QCERRDATEBOOL','Aralarında bir boolen iþleci olmadan (örne�in; VE, YA DA) �oklu Tarih/Zaman öl�ütü girildi.');
DEFINE('_QCERRPAYCRITOPER','bir payload öl�üt alanı i�in girilmiþ, fakat bir iþletici (örne�in; sahip, sahip de�il) belirtilmemiþ.');
DEFINE('_QCERRPAYCRITVALUE','payload\'ın bir öl�üt olması gerekti�ini göstermek üzere se�ilmiþ, fakat hangisiyle eþleþece�ini gösteren hi�bir de�er belirlenmemiþ.');
DEFINE('_QCERRPAYBOOL','Aralarında bir boolen iþleci olmadan (örne�in; VE, YA DA) �oklu Veri payload öl�ütü girildi.');
DEFINE('_QCMETACRIT','Meta Öl�ütü');
DEFINE('_QCIPCRIT','IP Öl�ütü');
DEFINE('_QCPAYCRIT','Payload Öl�ütü');
DEFINE('_QCTCPCRIT','TCP Öl�ütü');
DEFINE('_QCUDPCRIT','UDP Öl�ütü');
DEFINE('_QCICMPCRIT','ICMP Öl�ütü');
DEFINE('_QCLAYER4CRIT','4. Katman Öl�ütü');
DEFINE('_QCERRINVIPCRIT','Ge�ersiz IP adres öl�ütü');
DEFINE('_QCERRCRITADDRESSTYPE','bir öl�üt de�eri olması i�in girilmiþ, fakat adresin '.$UI_CW_Type.' (örne�in; kaynak, varıþ) belirlenmemiþ.');
DEFINE('_QCERRCRITIPADDRESSNONE','bir IP adresinin bir öl�üt olması gerekti�ini gösteriyor, fakat hangisiyle eþleþece�ini gösteren hi�bir adres belirlenmemiþ.');
DEFINE('_QCERRCRITIPADDRESSNONE1','se�ilmiþ (#');
DEFINE('_QCERRCRITIPIPBOOL','IP Öl�ütü arasında bir boolen iþleci olmadan (örne�in; VE, YA DA) �oklu IP adres öl�ütü girildi');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Sıralama düzeni');
DEFINE('_QFRMSORTNONE','hi�biri');
DEFINE('_QFRMTIMEA','zaman damgası (artan)');
DEFINE('_QFRMTIMED','zaman damgası (azalan)');
DEFINE('_QFRMSIG','imza');
DEFINE('_QFRMSIP','kaynak IP');
DEFINE('_QFRMDIP','varıþ IP');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Ýstatistik Özeti');
DEFINE('_QSCTIMEPROF','Zaman profili');
DEFINE('_QSCOFALERTS',$UI_CW_Alert.'ların');

//base_stat_alerts.php
DEFINE('_ALERTTITLE',$UI_CW_Alert.' Listeleme');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Kategoriler:');
DEFINE('_SCSENSORTOTAL','Sensorler/'.$UI_CW_Total.':');
DEFINE('_SCTOTALNUMALERTS',$UI_CW_Total.' '.$UI_CW_Alert.' Sayısı:');
DEFINE('_SCSRCIP','Kaynak IP adresi:');
DEFINE('_SCDSTIP','Varıþ IP adresi:');
DEFINE('_SCUNILINKS','Benzersiz IP ba�lantıları');
DEFINE('_SCSRCPORTS','Kaynak Portları: ');
DEFINE('_SCDSTPORTS','Varıþ Portları: ');
DEFINE('_SCSENSORS','Sensorler');
DEFINE('_SCCLASS','sınıflamalar');
DEFINE('_SCUNIADDRESS','Benzersiz adresler: ');
DEFINE('_SCSOURCE','Kaynak');
DEFINE('_SCDEST','Varıþ');
DEFINE('_SCPORT','Port');

//base_stat_ipaddr.php
DEFINE('_PSEVENTERR','PORTSCAN '.$UI_CW_Event.' HATASI: ');
DEFINE('_PSEVENTERRNOFILE','$portscan_file de�iþkeninde hi�bir dosya belirtilmemiþ');
DEFINE('_PSEVENTERROPENFILE','Portscan '.$UI_CW_Event.' dosyasını a�mak olanaksız');
DEFINE('_PSDATETIME','Tarih/Zaman');
DEFINE('_PSSRCIP','Kaynak IP');
DEFINE('_PSDSTIP','Varıþ IP');
DEFINE('_PSSRCPORT','Kaynak Portu');
DEFINE('_PSDSTPORT','Varıþ Portu');
DEFINE('_PSTCPFLAGS','TCP Bayrakları');
DEFINE('_PSTOTALOCC',$UI_CW_Total.'<BR> '.$UI_CW_Event.'lar');
DEFINE('_PSNUMSENSORS','Sensor Sayısı');
DEFINE('_PSFIRSTOCC','Ýlk<BR> Ger�ekleþen '.$UI_CW_Event);
DEFINE('_PSLASTOCC',$UI_CW_Last.'<BR> Ger�ekleþen '.$UI_CW_Event);
DEFINE('_PSUNIALERTS','Benzersiz '.$UI_CW_Alert.'lar');
DEFINE('_PSPORTSCANEVE','Portscan '.$UI_CW_Event.'ları');
DEFINE('_PSREGWHOIS','Kayıt bakıþı (whois)');
DEFINE('_PSNODNS','hi� DNS �özünürlü�ü denenmedi');
DEFINE('_PSNUMSENSORSBR','Sensor <BR>Sayısı');
DEFINE('_PSOCCASSRC','Kaynak olarak <BR>Ortaya �ıkanlar');
DEFINE('_PSOCCASDST','Varıþ olarak <BR>Ortaya �ıkanlar');
DEFINE('_PSWHOISINFO','Whois Bilgisi');
DEFINE('_PSTOTALHOSTS',$UI_CW_Total.' Taranan Hostlar');
DEFINE('_PSDETECTAMONG','%d benzersiz '.$UI_CW_Alert.' saptandı, %d '.$UI_CW_Alert.' arasında, %s \'de');
DEFINE('_PSALLALERTSAS','tüm '.$UI_CW_Alert.'larla birlikte %s/%s olarak');
DEFINE('_PSSHOW','göster');
DEFINE('_PSEXTERNAL','dıþ');

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP Ba�lantıları');
DEFINE('_SIPLSOURCEFGDN','Kaynak FQDN');
DEFINE('_SIPLDESTFGDN','Varıþ FQDN');
DEFINE('_SIPLDIRECTION','Yön');
DEFINE('_SIPLPROTO','Protokol');
DEFINE('_SIPLUNIDSTPORTS','Benzersiz Varıþ Portları');
DEFINE('_SIPLUNIEVENTS','Benzersiz '.$UI_CW_Event.'lar');
DEFINE('_SIPLTOTALEVENTS',$UI_CW_Total.' '.$UI_CW_Event.'lar');

//base_stat_ports.php
DEFINE('_UNIQ','Benzersiz');
DEFINE('_DSTPS','Varıþ Port(ları)');
DEFINE('_SRCPS','Kaynak Port(ları)');
DEFINE('_OCCURRENCES','Meydana Geliyor');

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Sensor Listeleme');

//base_stat_time.php
DEFINE('_BSTTITLE',$UI_CW_Alert.'ların Zaman Profili');
DEFINE('_BSTTIMECRIT','Zaman Öl�ütü');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>Hi�bir profilleme öl�ütü belirlenmemeiþ!</B>  "saat", "gün", ya da "ay" üzerine tıklayarak kümelenmiþ istatistiklerden taneli olanı se�in.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>Ge�ecek olan zaman parametresi '.$UI_CW_Type.' belirlenmemeiþ!</B>  Tek bir zaman belirtmek i�in "üzerinde", ya da bir aralık belirtmek i�in "arasında" \'dan herhangi birini se�in.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>Hi�bir Yıl parametresi belirtilmemiþ!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>Hi�bir Ay parametresi belirtilmemiþ!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>Hi�bir Gün parametresi belirtilmemiþ!</B></FONT>');
DEFINE('_BSTPROFILEBY','Profil tarafından');
DEFINE('_TIMEON','üzerinde');
DEFINE('_TIMEBETWEEN','arasında');
DEFINE('_PROFILEALERT','Profil '.$UI_CW_Alert.'sı');

//base_stat_uaddr.php
DEFINE('_UNISADD','Benzersiz Kaynak Adres(leri)');
DEFINE('_SUASRCIP','Kaynak IP adresi');
DEFINE('_SUAERRCRITADDUNK','ÖL��T HATASI: bilinmeyen adres '.$UI_CW_Type.' -- Varıþ adresi oldu�u sanılıyor');
DEFINE('_UNIDADD','Benzersiz Varıþ Adres(leri)');
DEFINE('_SUADSTIP','Varıþ IP adresi');
DEFINE('_SUAUNIALERTS','Benzersiz '.$UI_CW_Alert.'lar');
DEFINE('_SUASRCADD','Kaynak Adresi');
DEFINE('_SUADSTADD','Varıþ Adresi');

//base_user.php
DEFINE('_BASEUSERTITLE','BASE Kullanıcı Ye�lenenleri');
DEFINE('_BASEUSERERRPWD',$UI_CW_Pw.'nız boþ olamaz ya da iki '.strtolower($UI_CW_Pw).' eþleþmedi!');
DEFINE('_BASEUSEROLDPWD',"Eski $UI_CW_Pw".':');
DEFINE('_BASEUSERNEWPWD',"Yeni $UI_CW_Pw".':');
DEFINE('_BASEUSERNEWPWDAGAIN',"Yeni $UI_CW_Pw Tekrar:");

DEFINE('_LOGOUT','Oturumu Kapat');
?>
