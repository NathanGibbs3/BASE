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
$UI_CW_Dst = 'Varýþ';
$UI_CW_Id = 'ID';
$UI_CW_Name = 'Ad';
$UI_CW_Int = 'Arabirim';
$UI_CW_Filter = 'Süzge�';
$UI_CW_Desc = 'Betimleme';
$UI_CW_SucDesc = 'Baþarýlý';
$UI_CW_Sensor = 'Algýlayýcý';
$UI_CW_Sig = 'Ýmza';
$UI_CW_Role = 'Rol';
$UI_CW_Pw = 'Parola';
$UI_CW_Ts = 'Zaman Damgasý';
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
$UI_CW_ML5 = 'Mayýs';
$UI_CW_ML6 = 'Haziran';
$UI_CW_ML7 = 'Temmuz';
$UI_CW_ML8 = 'A�ustos';
$UI_CW_ML9 = 'Eylül';
$UI_CW_ML10 = 'Ekim';
$UI_CW_ML11 = 'Kasým';
$UI_CW_ML12 = 'Aralýk';
$UI_CW_Last = 'Son';
$UI_CW_First = 'Ýlk';
$UI_CW_Total = 'Toplam';
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
$UI_AD_ASD = 'Se�ilir Kýlýnmýþ';

//common phrases
DEFINE('_ALERT','Uyarý');
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
DEFINE('_YEAR','Yýl');
DEFINE('_ALERTGROUP','Uyarý Grubu');
DEFINE('_ALERTTIME','Uyarý Zamaný');
DEFINE('_CONTAINS','kapsar');
DEFINE('_DOESNTCONTAIN','kapsamaz');
DEFINE('_SOURCEPORT','kaynak portu');
DEFINE('_DESTPORT','varýþ portu');
DEFINE('_HAS','sahip');
DEFINE('_HASNOT','sahip de�il');
DEFINE('_PORT','Port');
DEFINE('_FLAGS','Bayraklar');
DEFINE('_MISC','Misc');
DEFINE('_BACK','Geri');
DEFINE('_DISPYEAR','{ yýl }');
DEFINE('_DISPMONTH','{ ay }');
DEFINE('_DISPHOUR','{ saat }');
DEFINE('_DISPDAY','{ gün }');
DEFINE('_DISPTIME','{ zaman }');
DEFINE('_ADDADDRESS','Adres EKLE');
DEFINE('_ADDIPFIELD','IP Alaný EKLE');
DEFINE('_ADDTIME','ZAMAN EKLE');
DEFINE('_ADDTCPPORT','TCP Portu EKLE');
DEFINE('_ADDTCPFIELD','TCP Alaný EKLE');
DEFINE('_ADDUDPPORT','UDP Portu EKLE');
DEFINE('_ADDUDPFIELD','UDP Alaný EKLE');
DEFINE('_ADDICMPFIELD','ICMP Alaný EKLE');
DEFINE('_ADDPAYLOAD','Payload EKLE');
DEFINE('_MOSTFREQALERTS','En Sýk Uyarýlar');
DEFINE('_MOSTFREQPORTS','En Sýk Portlar');
DEFINE('_MOSTFREQADDRS','En Sýk IP adresleri');
DEFINE('_LASTALERTS',$UI_CW_Last.' Uyarýlar');
DEFINE('_LASTPORTS',$UI_CW_Last.' Portlar');
DEFINE('_LASTTCP',$UI_CW_Last.' TCP Uyarýlarý');
DEFINE('_LASTUDP',$UI_CW_Last.' UDP Uyarýlarý');
DEFINE('_LASTICMP',$UI_CW_Last.' ICMP Uyarýlarý');
DEFINE('_QUERYDB','Sorgu DB');
DEFINE('_QUERYDBP','Sorgu+DB'); //_QUERYDB 'ye eþit, boþluklar '+' lardýr. 
                                //Bunun gibi bir þey olmasý gerekli: DEFINE('_QUERYDBP',str_replace(" ", "+", _QUERYDB));
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
DEFINE('_AGMAINT','Uyarý Grubu Bakýmý');
DEFINE('_USERPREF','Kullanýcý Ye�lenenleri');
DEFINE('_CACHE','Önbellek & Durum');
DEFINE('_ADMIN','Yönetim');
DEFINE('_GALERTD','�izge Uyarý Verisi');
DEFINE('_GALERTDT','�izge Uyarýsý Algýlama Zamaný');
DEFINE('_USERMAN','Kullanýcý Yönetimi');
DEFINE('_LISTU','Kullanýcýlarý Listele');
DEFINE('_CREATEU','Bir Kullanýcý Yarat');
DEFINE('_ROLEMAN',"$UI_CW_Role Yönetimi");
DEFINE('_LISTR',$UI_CW_Role.'leri Listele');
DEFINE('_CREATER',"Bir $UI_CW_Role Yarat");
DEFINE('_LISTALL','Hepsini Listele');
DEFINE('_CREATE','Yarat');
DEFINE('_VIEW','Görünüm');
DEFINE('_CLEAR','Temizle');
DEFINE('_LISTGROUPS','Gruplarý Listele');
DEFINE('_CREATEGROUPS','Grup Yarat');
DEFINE('_VIEWGROUPS','Grup Görüntüle');
DEFINE('_EDITGROUPS','Grup Düzenle');
DEFINE('_DELETEGROUPS','Grup Sil');
DEFINE('_CLEARGROUPS','Grup Temizle');
DEFINE('_CHNGPWD',"$UI_CW_Pw De�iþtir");
DEFINE('_DISPLAYU','Kullanýcý Görüntüle');

//base_footer.php
DEFINE('_FOOTER',' (by <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnson</A> and the <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','Kullanýcý ge�erli de�il ya da '.strtolower($UI_CW_Pw).'nýz yanlýþ!<br>Lütfen tekrar deneyin');

// base_main.php
DEFINE('_MOSTRECENT','En sondaki ');
DEFINE('_MOSTFREQUENT','En sýk ');
DEFINE('_ALERTS',' Uyarýlar:');
DEFINE('_ADDRESSES',' Adresler');
DEFINE('_ANYPROTO','herhangibir protokol');
DEFINE('_UNI','benzersiz');
DEFINE('_LISTING','listeleme');
DEFINE('_TALERTS','Bugün\'ün uyarýlarý: ');
DEFINE('_SOURCEIP','Kaynak IP');
DEFINE('_DESTIP','Varýþ IP');
DEFINE('_L24ALERTS',$UI_CW_Last.' 24 Saatin uyarýlarý: ');
DEFINE('_L72ALERTS',$UI_CW_Last.' 72 Saatin uyarýlarý: ');
DEFINE('_UNIALERTS',' Benzersiz Uyarýlar');
DEFINE('_LSOURCEPORTS',$UI_CW_Last.' Kaynak Portlarý: ');
DEFINE('_LDESTPORTS',$UI_CW_Last.' Varýþ Portlarý: ');
DEFINE('_FREGSOURCEP','En Sýk Kaynak Portlarý: ');
DEFINE('_FREGDESTP','En Sýk Varýþ Portlarý: ');
DEFINE('_QUERIED','Sorgulandý');
DEFINE('_DATABASE','Veritabaný:');
DEFINE('_SCHEMAV','�ema Sürümü:');
DEFINE('_TIMEWIN','Zaman Penceresi:');
DEFINE('_NOALERTSDETECT','hi�bir uyarý algýlanmadý');
DEFINE('_USEALERTDB','Uyarý Veritabanýný Kullan');
DEFINE('_USEARCHIDB','Arþiv Veritabanýný Kullan');
DEFINE('_TRAFFICPROBPRO','Protokole Göre Trafik Profili');

//base_auth.inc.php
DEFINE('_ADDEDSF','Baþarýlý Bi�imde Eklendi');
DEFINE('_NOPWDCHANGE',$UI_CW_Pw.'nýzý de�iþtirmek olanaksýz: ');
DEFINE('_NOUSER','Kullanýcý ge�erli de�il!');
DEFINE('_OLDPWD','Girilen Eski '.strtolower($UI_CW_Pw).' kayýtlarýmýzla eþleþmiyor!');
DEFINE('_PWDCANT',$UI_CW_Pw.'nýzý de�iþtirmek olanaksýz: ');
DEFINE('_PWDDONE',$UI_CW_Pw.'nýz de�iþtirildi!');
DEFINE('_ROLEEXIST',"$UI_CW_Role Zaten Var");
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." Zaten Var");
DEFINE('_ROLEADDED',"$UI_CW_Role Baþarýlý Bi�imde Eklendi");

//base_roleadmin.php
DEFINE('_ROLEADMIN',"BASE $UI_CW_Role Yönetimi");
DEFINE('_FRMROLENAME',"$UI_CW_Role Adý:");
DEFINE('_UPDATEROLE','Rolü Güncelle');

//base_useradmin.php
DEFINE('_USERADMIN','BASE Kullanýcý Yönetimi');
DEFINE('_FRMFULLNAME','Tüm Ad:');
DEFINE('_FRMUID','Kullanýcý ID:');
DEFINE('_SUBMITQUERY','Sorguyu Sun');
DEFINE('_UPDATEUSER','Kullanýcýyý Güncelle');

//admin/index.php
DEFINE('_BASEADMIN','BASE Yönetimi');
DEFINE('_BASEADMINTEXT','Lütfen soldan bir se�enek se�iniz.');

//base_action.inc.php
DEFINE('_NOACTION','Uyarýlarda hi�bir eylem belirlenmemiþ');
DEFINE('_INVALIDACT',' ge�ersiz bir eylemdir');
DEFINE('_ERRNOAG','Hi�bir UG belirlenmedi�i i�in uyarýlarý ekleyemedi');
DEFINE('_ERRNOEMAIL','Email adresi belirlenmedi�i i�in uyarýlarý gönderemedi');
DEFINE('_ACTION','EYLEM');
DEFINE('_CONTEXT','ba�lam');
DEFINE('_ADDAGID','UG\'na EKLE (ID yoluyla)');
DEFINE('_ADDAG','Yeni-UG-EKLE');
DEFINE('_ADDAGNAME','UG\'na EKLE (Ad yoluyla)');
DEFINE('_CREATEAG','UG Yarat (Ad yoluyla)');
DEFINE('_CLEARAG','UG\'dan Temizle');
DEFINE('_DELETEALERT','Uyarý(larý) sil');
DEFINE('_EMAILALERTSFULL','Uyarý(larý) Email\'e gönder (tüm)');
DEFINE('_EMAILALERTSSUMM','Uyarý(larý) Email\'e gönder (özet)');
DEFINE('_EMAILALERTSCSV','Uyarý(larý) Email\'e gönder (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Uyarý(larý) arþivle (kopyala)');
DEFINE('_ARCHIVEALERTSMOVE','Uyarý(larý) arþivle (taþý)');
DEFINE('_IGNORED','Yoksayýldý ');
DEFINE('_DUPALERTS',' uyarý(larý) �o�alt');
DEFINE('_ALERTSPARA',' uyarý(lar)');
DEFINE('_NOALERTSSELECT','Hi�bir uyarý se�ilmemiþ ya da');
DEFINE('_NOTSUCCESSFUL','baþarýlý de�ildi');
DEFINE('_ERRUNKAGID','Bilinmeyen UG ID belirlenmiþ (UG muhtemelen ge�erli de�il)');
DEFINE('_ERRREMOVEFAIL','Yeni UG\'nu �ýkarmak baþarýsýz oldu');
DEFINE('_GENBASE','BASE tarafýndan �retildi');
DEFINE('_ERRNOEMAILEXP','DI�ARI AKTARIM HATASI: Dýþarý aktarýlmýþ uyarýlarý gönderemedi');
DEFINE('_ERRNOEMAILPHP','PHP\'deki mail yapýlandýrmasýný kontrol et.');
DEFINE('_ERRDELALERT','Uyarý Silme Hatasý');
DEFINE('_ERRARCHIVE','Arþiv hatasý:');
DEFINE('_ERRMAILNORECP','MAIL HATASI: Alýcý Belirlenmemiþ');

//base_cache.inc.php
DEFINE('_ADDED','Ekledi ');
DEFINE('_HOSTNAMESDNS',' host isimlerini IP DNS önbelle�ine');
DEFINE('_HOSTNAMESWHOIS',' host isimlerini Whois önbelle�ine');
DEFINE('_ERRCACHENULL','Önbelle�e Alma HATASI: NULL '.$UI_CW_Event.' sýrasý bulundu?');
DEFINE('_ERRCACHEERROR',$UI_CW_Event.'I ÖNBELLE�E ALMA HATASI:');
DEFINE('_ERRCACHEUPDATE',$UI_CW_Event.' önbelle�ini güncelleyemedi');
DEFINE('_ALERTSCACHE',' uyarý(larý) Uyarý önbelle�ine');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','SQL iz dosyasýný a�mak olanaksýz');
DEFINE('_ERRSQLCONNECT','DB ba�lantý hatasý :');
DEFINE('_ERRSQLCONNECTINFO','<P><I>base_conf.php</I> dosyasýndaki DB ba�lantý de�iþkenlerini kontrol edin.  
              <PRE>
               = $alert_dbname   : uyarýlarýn depolandý�ý MySQL veritabaný adý 
               = $alert_host     : veritabanýnýn depolandý�ý host
               = $alert_port     : veritabanýnýn depolandý�ý port
               = $alert_user     : veritabaný i�indeki kullanýcýadý
               = $alert_password : kullanýcýadý i�in '.strtolower($UI_CW_Pw).'
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','DB (p)ba�lantý hatasý :');
DEFINE('_ERRSQLDB','Veritabaný HATASI:');
DEFINE('_DBALCHECK','DB soyutlama kitaplý�ý kontrol ediliyor');
DEFINE('_ERRSQLDBALLOAD1','<P><B>DB soyutlama kitaplý�ý yükleme hatasý: </B> from ');
DEFINE('_ERRSQLDBALLOAD2','<P><CODE>base_conf.php</CODE> dosyasýndaki <CODE>$DBlib_path</CODE> DB soyutlama kitaplý�ý de�iþkenini kontrol edin 
            <P>
            Yürürlükte kullanýlan temel veritabaný kitaplý�ý ADODB\'dir ten indirilebilir
            ');
DEFINE('_ERRSQLDBTYPE','Ge�ersiz Veritabaný '.$UI_CW_Type.' Belirlenmiþ');
DEFINE('_ERRSQLDBTYPEINFO1','<CODE>base_conf.php</CODE> dosyasýndaki <CODE>\$DBtype</CODE> de�iþkeni tanýmlanmamýþ veritabaný '.$UI_CW_Type.'nde ayarlanmýþ ');
DEFINE('_ERRSQLDBTYPEINFO2','Yalnýzca aþa�ýdaki veritabanlarý desteklenmektedir: <PRE>
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
DEFINE('_ERRRESOLVEADDRESS','Adresi �özmek olanaksýz');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Sorgu Sonu�larý Sayfa Baþlý�ý �ýkýþý');

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','Bilinmeyen ÝmzaÝsmi');
DEFINE('_ERRSIGPROIRITYUNK','Bilinmeyen ÝmzaÖnceli�i');
DEFINE('_UNCLASS','sýnýflandýrýlmamýþ');

//base_state_citems.inc.php
DEFINE('_DENCODED','veri þifrelenmiþ');
DEFINE('_NODENCODED','(veri dönüþtürme yok, DB yerel þifrelemedeki öl�üt sanýlýyor)');
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
DEFINE('_DISPANYCLASS','{ herhangibir Sýnýflandýrma }');
DEFINE('_DISPANYPRIO','{ herhangibir Öncelik }');
DEFINE('_DISPANYSENSOR','{ herhangibir Sensor }');
DEFINE('_DISPADDRESS','{ adres }');
DEFINE('_DISPFIELD','{ alan }');
DEFINE('_DISPPORT','{ port }');
DEFINE('_DISPENCODING','{ þifreleme }');
DEFINE('_DISPCONVERT2','{ Dönüþtür }');
DEFINE('_DISPANYAG','{ herhangibir Uyarý Grubu }');
DEFINE('_DISPPAYLOAD','{ payload }');
DEFINE('_DISPFLAGS','{ bayraklar }');
DEFINE('_SIGEXACTLY','tam olarak');
DEFINE('_SIGROUGHLY','yaklaþýk olarak');
DEFINE('_SIGCLASS','Ýmza Sýnýflandýrma');
DEFINE('_SIGPRIO','Ýmza Önceli�i');
DEFINE('_SHORTSOURCE','Kaynak');
DEFINE('_SHORTDEST','Varýþ');
DEFINE('_SHORTSOURCEORDEST','Kaynak ya da Varýþ');
DEFINE('_NOLAYER4','4.katman yok');
DEFINE('_INPUTCRTENC','Girdi Öl�ütü �ifreleme '.$UI_CW_Type);
DEFINE('_CONVERT2WS','Dönüþtür (ararken)');

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','PHP HATASI: Özel (kullanýcý) bir PHP oturumu saptandý. Ancak, BASE a�ýk�a bu özel iþleyiciyi kullanmak üzere ayarlanmamýþ. <CODE>base_conf.php</CODE> dosyasýnda <CODE>use_user_session=1</CODE> olarak ayarlayýn');
DEFINE('_PHPERRORCSESSIONCODE','PHP HATASI: Özel (kullanýcý) bir PHP oturum iþleyicisi yapýlandýrýlmýþ, fakat <CODE>user_session_path</CODE> \'teki belirlenmiþ iþleyici kodu ge�ersiz.');
DEFINE('_PHPERRORCSESSIONVAR','PHP HATASI: Özel (kullanýcý) bir PHP oturum iþleyicisi yapýlandýrýlmýþ, fakat bu iþleyicinin ger�ekleþtirilmesi BASE\'de belirlenmemiþ. E�er özel bir oturum iþleyici isteniyorsa, <CODE>base_conf.php</CODE> dosyasýndaki <CODE>user_session_path</CODE> de�iþkenini ayarlayýn.');
DEFINE('_PHPSESSREG','Oturum Kaydedildi');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Kaldýrýlýyor');
DEFINE('_FROMCRIT','öl�ütten');
DEFINE('_ERRCRITELEM','Ge�ersiz öl�üt ö�esi');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Ge�erli Konservelenmiþ Sorgu Listesi');
DEFINE('_DISPLAYING','Görüntüleniyor');
DEFINE('_DISPLAYINGTOTAL','%d-%d uyarýlarý görüntüleniyor, %d '.$UI_CW_Total);
DEFINE('_NOALERTS','Hi�bir Uyarý bulunamadý.');
DEFINE('_QUERYRESULTS','Sorgu Sonu�larý');
DEFINE('_QUERYSTATE','Sorgu Durumu');
DEFINE('_DISPACTION','{ eylem }');

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','Belirtilen UG ad aramasý ge�ersiz.  Tekrar deneyin!');
DEFINE('_ERRAGNAMEEXIST','Belirtilen UG yok.');
DEFINE('_ERRAGIDSEARCH','Belirtilen UG ID aramasý ge�ersiz.  Tekrar deneyin!');
DEFINE('_ERRAGLOOKUP','UG ID arama Hatasý');
DEFINE('_ERRAGINSERT','Yeni UG Ekleme Hatasý');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Uyarý Grubu (UG) Bakýmý');
DEFINE('_ERRAGUPDATE','UG güncelleme Hatasý');
DEFINE('_ERRAGPACKETLIST','UG i�in paket listesi silme Hatasý:');
DEFINE('_ERRAGDELETE','UG silme Hatasý');
DEFINE('_AGDELETE','Baþarýlý bi�imde SÝLÝNDÝ');
DEFINE('_AGDELETEINFO','bilgi silindi');
DEFINE('_ERRAGSEARCHINV','Girilen arama öl�ütü ge�ersiz.  Tekrar deneyin!');
DEFINE('_ERRAGSEARCHNOTFOUND','Bu öl�üte göre UG bulunamadý.');
DEFINE('_NOALERTGOUPS','Hi� Uyarý Grubu yok');
DEFINE('_NUMALERTS','# Uyarýlar');
DEFINE('_ACTIONS','Eylemler');
DEFINE('_NOTASSIGN','henüz atanmamýþ');
DEFINE('_SAVECHANGES','De�iþiklikleri Kaydet');
DEFINE('_CONFIRMDELETE','Silmeyi Onayla');
DEFINE('_CONFIRMCLEAR','Temizlemeyi Onayla');

//base_common.php
DEFINE('_PORTSCAN','Portscan Trafi�i');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','INDEX YARATMAK Olanaksýz');
DEFINE('_DBINDEXCREATE','Baþarýlý bi�imde INDEX yaratýldý');
DEFINE('_ERRSNORTVER','Eski bir sürüm olabilir.  Sadece Snort 1.7-beta0 ve sonraki sürümler tarafýndan yaratýlan uyarý veritabanlarý desteklenmektedir');
DEFINE('_ERRSNORTVER1','temel veritabaný');
DEFINE('_ERRSNORTVER2','eksik/ge�ersiz görünmektedir');
DEFINE('_ERRDBSTRUCT1','veritabaný sürümü ge�erli, fakat BASE DB yapýsý');
DEFINE('_ERRDBSTRUCT2','sunulu de�il. <A HREF="base_db_setup.php">Setup sayfasýný</A> kullanarak DB\'i yapýlandýrýn ve optimize edin.');
DEFINE('_ERRPHPERROR','PHP HATASI');
DEFINE('_ERRPHPERROR1','Uyumsuz sürüm');
DEFINE('_ERRVERSION','Sürümü');
DEFINE('_ERRPHPERROR2',' PHP\'nin �ok eski.  Lütfen 4.0.4 veya sonraki bir sürüme yükseltin');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP inþasý eksik</B>: <FONT>uyarý veritabanýný okumak i�in gerekli 
               önkoþul Mysql deste�i PHP i�ine inþa edilmemiþ.  
               Lütfen gerekli kitaplýk ile birlikte PHP\'yi yeniden derleyin (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP inþasý eksik</B>: <FONT>uyarý veritabanýný okumak i�in gerekli 
               önkoþul PostgreSQL deste�i PHP i�ine inþa edilmemiþ.  
               Lütfen gerekli kitaplýk ile birlikte PHP\'yi yeniden derleyin (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>PHP inþasý eksik</B>: <FONT>uyarý veritabanýný okumak i�in gerekli 
                   önkoþul MS SQL Server deste�i PHP i�ine inþa edilmemiþ.  
                   Lütfen gerekli kitaplýk ile birlikte PHP\'yi yeniden derleyin (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>PHP inþasý eksik</B>: <FONT>uyarý veritabanýný okumak i�in gerekli 
                   önkoþul Oracle deste�i PHP i�ine inþa edilmemiþ.  
                   Lütfen gerekli kitaplýk ile birlikte PHP\'yi yeniden derleyin (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Grafik Baþlý�ý:');
DEFINE('_CHARTTYPE','Grafik '.$UI_CW_Type.':');
DEFINE('_CHARTTYPES','{ grafik '.$UI_CW_Type.' }');
DEFINE('_CHARTPERIOD','Grafik Dönemi:');
DEFINE('_PERIODNO','dönem yok');
DEFINE('_PERIODWEEK','7 (bir hafta)');
DEFINE('_PERIODDAY','24 (bütün gün)');
DEFINE('_PERIOD168','168 (24x7)');
DEFINE('_CHARTSIZE','Boyut: (en x yükseklik)');
DEFINE('_PLOTMARGINS','�izim Boþluklarý: (sol x sa� x üst x alt)');
DEFINE('_PLOTTYPE','�izim '.$UI_CW_Type.':');
DEFINE('_TYPEBAR','�ubuk');
DEFINE('_TYPELINE','�izgi');
DEFINE('_TYPEPIE','pasta');
DEFINE('_CHARTHOUR','{sat}');
DEFINE('_CHARTDAY','{gün}');
DEFINE('_CHARTMONTH','{ay}');
DEFINE('_GRAPHALERTS','�izge Uyarýlarý');
DEFINE('_AXISCONTROLS','X / Y EKSEN KONTROLLERÝ');
DEFINE('_CHRTTYPEHOUR','Zaman (saat) vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPEDAY','Zaman (gün) vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPEWEEK','Zaman (hafta) vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPEMONTH','Zaman (ay) vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPEYEAR','Zaman (yýl) vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPESRCIP','Kaynak IP adresi vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPEDSTIP','Varýþ IP adresi vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPEDSTUDP','Varýþ UDP Portu vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPESRCUDP','Kynak UDP Portu vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPEDSTPORT','Varýþ TCP Portu vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPESRCPORT','Kaynak TCP Portu vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPESIG','Ýmza Sýnýflamasý vs. Uyarý Sayýsý');
DEFINE('_CHRTTYPESENSOR','Sensor vs. Uyarý Sayýsý');
DEFINE('_CHRTBEGIN','Grafik Baþlangýcý:');
DEFINE('_CHRTEND','Grafik Sonu:');
DEFINE('_CHRTDS','Veri Kayna�ý:');
DEFINE('_CHRTX','X Ekseni');
DEFINE('_CHRTY','Y Ekseni');
DEFINE('_CHRTMINTRESH','En Düþük Eþik De�eri');
DEFINE('_CHRTROTAXISLABEL','Eksen Etiketlerini Döndür (90 derece)');
DEFINE('_CHRTSHOWX','X-ekseni ýzgara-�izgilerini göster');
DEFINE('_CHRTDISPLABELX','Her bir X-ekseni etiketini görüntüle');
DEFINE('_CHRTDATAPOINTS','veri göstergeleri');
DEFINE('_CHRTYLOG','Logaritmik Y-ekseni');
DEFINE('_CHRTYGRID','Y-ekseni ýzgara-�izgilerini göster');

//base_graph_main.php
DEFINE('_CHRTTITLE','BASE Grafik');
DEFINE('_ERRCHRTNOTYPE','Hi�bir grafik '.$UI_CW_Type.' belirtilmemiþ');
DEFINE('_ERRNOAGSPEC','Hi�bir UG belirtilmemiþ.  Tüm uyarýlarý kullanýyor.');
DEFINE('_CHRTDATAIMPORT','Veri aktarýmýný baþlatýyor');
DEFINE('_CHRTTIMEVNUMBER','Zaman vs. Uyarý Sayýsý');
DEFINE('_CHRTTIME','Zaman');
DEFINE('_CHRTALERTOCCUR','Uyarý Meydana Geliyor');
DEFINE('_CHRTSIPNUMBER','Kaynak IP vs. Uyarý Sayýsý');
DEFINE('_CHRTSIP','Kaynak IP Adresi');
DEFINE('_CHRTDIPALERTS','Varýþ IP vs. Uyarý Sayýsý');
DEFINE('_CHRTDIP','Varýþ IP Adresi');
DEFINE('_CHRTUDPPORTNUMBER','UDP Portu (Varýþ) vs. Uyarý Sayýsý');
DEFINE('_CHRTDUDPPORT','Varýþ UDP Portu');
DEFINE('_CHRTSUDPPORTNUMBER','UDP Portu (Kaynak) vs. Uyarý Sayýsý');
DEFINE('_CHRTSUDPPORT','Kaynak UDP Portu');
DEFINE('_CHRTPORTDESTNUMBER','TCP Portu (Varýþ) vs. Uyarý Sayýsý');
DEFINE('_CHRTPORTDEST','Varýþ TCP Portu');
DEFINE('_CHRTPORTSRCNUMBER','TCP Portu (Kaynak) vs. Uyarý Sayýsý');
DEFINE('_CHRTPORTSRC','Kaynak TCP Portu');
DEFINE('_CHRTSIGNUMBER','Ýmza Sýnýflamasý vs. Uyarý Sayýsý');
DEFINE('_CHRTCLASS','Sýnýflama');
DEFINE('_CHRTSENSORNUMBER','Sensor vs. Uyarý Sayýsý');
DEFINE('_CHRTHANDLEPERIOD','Ýþleme Dönemi, e�er gerekliyse');
DEFINE('_CHRTDUMP','Veriyi boþaltýyor ... (her birini yazýyor');
DEFINE('_CHRTDRAW','Grafi�i �iziyor');
DEFINE('_ERRCHRTNODATAPOINTS','�izecek hi� veri göstergesi yok');
DEFINE('_GRAPHALERTDATA','Grafik Uyarý Verisi');

//base_maintenance.php
DEFINE('_MAINTTITLE','Bakým');
DEFINE('_MNTPHP','PHP Ýnþasý:');
DEFINE('_MNTCLIENT','ÝSTEMCÝ:');
DEFINE('_MNTSERVER','SUNUCU:');
DEFINE('_MNTSERVERHW','SUNUCU HW:');
DEFINE('_MNTPHPVER','PHP S�R�M�:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','PHP Günlükleme düzeyi:');
DEFINE('_MNTPHPMODS','Yüklü Modüller:');
DEFINE('_MNTDBTYPE','DB '.$UI_CW_Type.':');
DEFINE('_MNTDBALV','DB Soyutlama Sürümü:');
DEFINE('_MNTDBALERTNAME','UYARI DB Adý:');
DEFINE('_MNTDBARCHNAME','AR�ÝV DB Adý:');
DEFINE('_MNTAIC','Uyarý Bilgi Önbelle�i:');
DEFINE('_MNTAICTE',$UI_CW_Total.' '.$UI_CW_Event.'lar:');
DEFINE('_MNTAICCE','Önbellekteki '.$UI_CW_Event.'lar:');
DEFINE('_MNTIPAC','IP Adres Önbelle�i');
DEFINE('_MNTIPACUSIP','Benzersiz Kaynak IP:');
DEFINE('_MNTIPACDNSC','DNS Önbelle�e alýndý:');
DEFINE('_MNTIPACWC','Whois Önbelle�e alýndý:');
DEFINE('_MNTIPACUDIP','Benzersiz Varýþ IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Ge�ersiz (sid,cid) �ift');
DEFINE('_QAALERTDELET','Uyarý SÝLÝNDÝ');
DEFINE('_QATRIGGERSIG','Tetiklenmiþ Ýmza');
DEFINE('_QANORMALD','Normal Görüntü');
DEFINE('_QAPLAIND','Düz Görüntü');
DEFINE('_QANOPAYLOAD','Hýzlý günlükleme kullanýldý bu yüzden payload atýldý');

//base_qry_common.php
DEFINE('_QCSIG','imza');
DEFINE('_QCIPADDR','IP adresleri');
DEFINE('_QCIPFIELDS','IP alanlarý');
DEFINE('_QCTCPPORTS','TCP portlarý');
DEFINE('_QCTCPFLAGS','TCP bayraklarý');
DEFINE('_QCTCPFIELD','TCP alanlarý');
DEFINE('_QCUDPPORTS','UDP portlarý');
DEFINE('_QCUDPFIELDS','UDP alanlarý');
DEFINE('_QCICMPFIELDS','ICMP alanlarý');
DEFINE('_QCDATA','Veri');
DEFINE('_QCERRCRITWARN','Öl�üt uyarýsý:');
DEFINE('_QCERRVALUE','de�eri');
DEFINE('_QCERRFIELD','alaný');
DEFINE('_QCERROPER','iþletmeni');
DEFINE('_QCERRDATETIME','tarih/zaman de�eri');
DEFINE('_QCERRPAYLOAD','payload de�eri');
DEFINE('_QCERRIP','IP adresi');
DEFINE('_QCERRIPTYPE',$UI_CW_Type.'n IP adresi');
DEFINE('_QCERRSPECFIELD',' bir protokol alaný i�in girildi, fakat özel alan belirlenmemiþ.');
DEFINE('_QCERRSPECVALUE','onun bir öl�üt olmasý gerekti�ini göstermek üzere se�ilmiþ, fakat hangisiyle eþleþece�ini gösteren hi�bir de�er belirlenmemiþ.');
DEFINE('_QCERRBOOLEAN','Aralarýnda bir boolen iþleci olmadan (örne�in; VE, YA DA) �oklu Protokol Alan öl�ütü girildi.');
DEFINE('_QCERRDATEVALUE','bazý tarih/zaman öl�ütünün eþleþmesi gerekti�ini göstermek üzere se�ilmiþ, fakat hi�bir de�er belirlenmemiþ.');
DEFINE('_QCERRINVHOUR','(Ge�ersiz Saat) Belirtilen zamana uygun hi�bir tarih girilmemiþ.');
DEFINE('_QCERRDATECRIT','bazý tarih/zaman öl�ütünün eþleþmesi gerekti�ini göstermek üzere se�ilmiþ, fakat hi�bir de�er belirlenmemiþ.');
DEFINE('_QCERROPERSELECT','girilmiþ fakat hi�bir iþletici se�ilmemiþ.');
DEFINE('_QCERRDATEBOOL','Aralarýnda bir boolen iþleci olmadan (örne�in; VE, YA DA) �oklu Tarih/Zaman öl�ütü girildi.');
DEFINE('_QCERRPAYCRITOPER','bir payload öl�üt alaný i�in girilmiþ, fakat bir iþletici (örne�in; sahip, sahip de�il) belirtilmemiþ.');
DEFINE('_QCERRPAYCRITVALUE','payload\'ýn bir öl�üt olmasý gerekti�ini göstermek üzere se�ilmiþ, fakat hangisiyle eþleþece�ini gösteren hi�bir de�er belirlenmemiþ.');
DEFINE('_QCERRPAYBOOL','Aralarýnda bir boolen iþleci olmadan (örne�in; VE, YA DA) �oklu Veri payload öl�ütü girildi.');
DEFINE('_QCMETACRIT','Meta Öl�ütü');
DEFINE('_QCIPCRIT','IP Öl�ütü');
DEFINE('_QCPAYCRIT','Payload Öl�ütü');
DEFINE('_QCTCPCRIT','TCP Öl�ütü');
DEFINE('_QCUDPCRIT','UDP Öl�ütü');
DEFINE('_QCICMPCRIT','ICMP Öl�ütü');
DEFINE('_QCLAYER4CRIT','4. Katman Öl�ütü');
DEFINE('_QCERRINVIPCRIT','Ge�ersiz IP adres öl�ütü');
DEFINE('_QCERRCRITADDRESSTYPE','bir öl�üt de�eri olmasý i�in girilmiþ, fakat adresin '.$UI_CW_Type.' (örne�in; kaynak, varýþ) belirlenmemiþ.');
DEFINE('_QCERRCRITIPADDRESSNONE','bir IP adresinin bir öl�üt olmasý gerekti�ini gösteriyor, fakat hangisiyle eþleþece�ini gösteren hi�bir adres belirlenmemiþ.');
DEFINE('_QCERRCRITIPADDRESSNONE1','se�ilmiþ (#');
DEFINE('_QCERRCRITIPIPBOOL','IP Öl�ütü arasýnda bir boolen iþleci olmadan (örne�in; VE, YA DA) �oklu IP adres öl�ütü girildi');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Sýralama düzeni');
DEFINE('_QFRMSORTNONE','hi�biri');
DEFINE('_QFRMTIMEA','zaman damgasý (artan)');
DEFINE('_QFRMTIMED','zaman damgasý (azalan)');
DEFINE('_QFRMSIG','imza');
DEFINE('_QFRMSIP','kaynak IP');
DEFINE('_QFRMDIP','varýþ IP');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Ýstatistik Özeti');
DEFINE('_QSCTIMEPROF','Zaman profili');
DEFINE('_QSCOFALERTS','uyarýlarýn');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','Uyarý Listeleme');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Kategoriler:');
DEFINE('_SCSENSORTOTAL','Sensorler/'.$UI_CW_Total.':');
DEFINE('_SCTOTALNUMALERTS',$UI_CW_Total.' Uyarý Sayýsý:');
DEFINE('_SCSRCIP','Kaynak IP adresi:');
DEFINE('_SCDSTIP','Varýþ IP adresi:');
DEFINE('_SCUNILINKS','Benzersiz IP ba�lantýlarý');
DEFINE('_SCSRCPORTS','Kaynak Portlarý: ');
DEFINE('_SCDSTPORTS','Varýþ Portlarý: ');
DEFINE('_SCSENSORS','Sensorler');
DEFINE('_SCCLASS','sýnýflamalar');
DEFINE('_SCUNIADDRESS','Benzersiz adresler: ');
DEFINE('_SCSOURCE','Kaynak');
DEFINE('_SCDEST','Varýþ');
DEFINE('_SCPORT','Port');

//base_stat_ipaddr.php
DEFINE('_PSEVENTERR','PORTSCAN '.$UI_CW_Event.' HATASI: ');
DEFINE('_PSEVENTERRNOFILE','$portscan_file de�iþkeninde hi�bir dosya belirtilmemiþ');
DEFINE('_PSEVENTERROPENFILE','Portscan '.$UI_CW_Event.' dosyasýný a�mak olanaksýz');
DEFINE('_PSDATETIME','Tarih/Zaman');
DEFINE('_PSSRCIP','Kaynak IP');
DEFINE('_PSDSTIP','Varýþ IP');
DEFINE('_PSSRCPORT','Kaynak Portu');
DEFINE('_PSDSTPORT','Varýþ Portu');
DEFINE('_PSTCPFLAGS','TCP Bayraklarý');
DEFINE('_PSTOTALOCC',$UI_CW_Total.'<BR> '.$UI_CW_Event.'lar');
DEFINE('_PSNUMSENSORS','Sensor Sayýsý');
DEFINE('_PSFIRSTOCC','Ýlk<BR> Ger�ekleþen '.$UI_CW_Event);
DEFINE('_PSLASTOCC',$UI_CW_Last.'<BR> Ger�ekleþen '.$UI_CW_Event);
DEFINE('_PSUNIALERTS','Benzersiz Uyarýlar');
DEFINE('_PSPORTSCANEVE','Portscan '.$UI_CW_Event.'larý');
DEFINE('_PSREGWHOIS','Kayýt bakýþý (whois)');
DEFINE('_PSNODNS','hi� DNS �özünürlü�ü denenmedi');
DEFINE('_PSNUMSENSORSBR','Sensor <BR>Sayýsý');
DEFINE('_PSOCCASSRC','Kaynak olarak <BR>Ortaya �ýkanlar');
DEFINE('_PSOCCASDST','Varýþ olarak <BR>Ortaya �ýkanlar');
DEFINE('_PSWHOISINFO','Whois Bilgisi');
DEFINE('_PSTOTALHOSTS',$UI_CW_Total.' Taranan Hostlar');
DEFINE('_PSDETECTAMONG','%d benzersiz uyarý saptandý, %d uyarý arasýnda, %s \'de');
DEFINE('_PSALLALERTSAS','tüm uyarýlarla birlikte %s/%s olarak');
DEFINE('_PSSHOW','göster');
DEFINE('_PSEXTERNAL','dýþ');

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP Ba�lantýlarý');
DEFINE('_SIPLSOURCEFGDN','Kaynak FQDN');
DEFINE('_SIPLDESTFGDN','Varýþ FQDN');
DEFINE('_SIPLDIRECTION','Yön');
DEFINE('_SIPLPROTO','Protokol');
DEFINE('_SIPLUNIDSTPORTS','Benzersiz Varýþ Portlarý');
DEFINE('_SIPLUNIEVENTS','Benzersiz '.$UI_CW_Event.'lar');
DEFINE('_SIPLTOTALEVENTS',$UI_CW_Total.' '.$UI_CW_Event.'lar');

//base_stat_ports.php
DEFINE('_UNIQ','Benzersiz');
DEFINE('_DSTPS','Varýþ Port(larý)');
DEFINE('_SRCPS','Kaynak Port(larý)');
DEFINE('_OCCURRENCES','Meydana Geliyor');

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Sensor Listeleme');

//base_stat_time.php
DEFINE('_BSTTITLE','Uyarýlarýn Zaman Profili');
DEFINE('_BSTTIMECRIT','Zaman Öl�ütü');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>Hi�bir profilleme öl�ütü belirlenmemeiþ!</B>  "saat", "gün", ya da "ay" üzerine týklayarak kümelenmiþ istatistiklerden taneli olaný se�in.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>Ge�ecek olan zaman parametresi '.$UI_CW_Type.' belirlenmemeiþ!</B>  Tek bir zaman belirtmek i�in "üzerinde", ya da bir aralýk belirtmek i�in "arasýnda" \'dan herhangi birini se�in.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>Hi�bir Yýl parametresi belirtilmemiþ!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>Hi�bir Ay parametresi belirtilmemiþ!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>Hi�bir Gün parametresi belirtilmemiþ!</B></FONT>');
DEFINE('_BSTPROFILEBY','Profil tarafýndan');
DEFINE('_TIMEON','üzerinde');
DEFINE('_TIMEBETWEEN','arasýnda');
DEFINE('_PROFILEALERT','Profil Uyarýsý');

//base_stat_uaddr.php
DEFINE('_UNISADD','Benzersiz Kaynak Adres(leri)');
DEFINE('_SUASRCIP','Kaynak IP adresi');
DEFINE('_SUAERRCRITADDUNK','ÖL��T HATASI: bilinmeyen adres '.$UI_CW_Type.' -- Varýþ adresi oldu�u sanýlýyor');
DEFINE('_UNIDADD','Benzersiz Varýþ Adres(leri)');
DEFINE('_SUADSTIP','Varýþ IP adresi');
DEFINE('_SUAUNIALERTS','Benzersiz Uyarýlar');
DEFINE('_SUASRCADD','Kaynak Adresi');
DEFINE('_SUADSTADD','Varýþ Adresi');

//base_user.php
DEFINE('_BASEUSERTITLE','BASE Kullanýcý Ye�lenenleri');
DEFINE('_BASEUSERERRPWD',$UI_CW_Pw.'nýz boþ olamaz ya da iki '.strtolower($UI_CW_Pw).' eþleþmedi!');
DEFINE('_BASEUSEROLDPWD',"Eski $UI_CW_Pw".':');
DEFINE('_BASEUSERNEWPWD',"Yeni $UI_CW_Pw".':');
DEFINE('_BASEUSERNEWPWDAGAIN',"Yeni $UI_CW_Pw Tekrar:");

DEFINE('_LOGOUT','Oturumu Kapat');

?>
