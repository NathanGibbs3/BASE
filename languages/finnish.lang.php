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
** Purpose: Finnish language file for the BASE project. 
** 	It was done using 
** 	the original english language file by Kevin Jonhson. If you're editing this 
** 	file or using it as a basis for a new translation, remember to leave any
** 	variable not translated so that the system will have
** 	something to display.
** 
********************************************************************************
** Authors:
********************************************************************************
** Oiriginal english file:
** Kevin Johnson <kjohnson@secureideas.net>
** Joel Esler <joelesler@users.sourceforge.net>
** Translators:
** Elmo Mäntynen <elmo13@jippii.fi>
********************************************************************************
*/

// Inter Character Spacing.
$UI_Spacing = 1;
// Locales.
$UI_Locales = array( 'eng_ENG.ISO8859-1', 'eng_ENG.utf-8', 'english' );
// Time Format - See strftime() syntax.
$UI_Timefmt = '%a %B %d, %Y %H:%M:%S';
// UI Init.
$UI_Charset = 'iso-8859-1';
$UI_Title = 'Basic Analysis and Security Engine';
// Common Words.
$UI_CW_Edit = 'Muokkaa';
$UI_CW_Delete = 'Poista';
$UI_CW_Src = 'Lähde';
$UI_CW_Dst = 'Kohde';
$UI_CW_Id = 'ID';
$UI_CW_Name = 'Nimi';
$UI_CW_Int = 'Käyttöliittymä';
$UI_CW_Filter = 'Suodatin';
$UI_CW_Desc = 'Kuvaus';
$UI_CW_SucDesc = 'Onnistunut';
$UI_CW_Sensor = 'Sensori';
$UI_CW_Sig = 'Signature';
$UI_CW_Role = 'Role';
$UI_CW_Pw = 'Salasana';
$UI_CW_Ts = 'Aikaleima';
$UI_CW_Addr = 'Osoite';
$UI_CW_Layer = 'Layer';
$UI_CW_Proto = 'Protocol';
$UI_CW_Pri = 'Tärkeysjärjetys';
$UI_CW_Event = 'tapahtuma';
$UI_CW_Type = 'tyyppi';
// Common Phrases.
$UI_CP_SrcName = array('Lähteen',$UI_CW_Name);
$UI_CP_DstName = array('Kohteen',$UI_CW_Name);
$UI_CP_SrcDst = array('Lähteen','nimi');
$UI_CP_SrcAddr = array($UI_CW_Src,$UI_CW_Addr);
$UI_CP_DstAddr = array($UI_CW_Dst,$UI_CW_Addr);
$UI_CP_L4P = array($UI_CW_Layer,'4',$UI_CW_Proto);
$UI_CP_ET = array($UI_CW_Event,$UI_CW_Type);
// Authentication Data.
$UI_AD_UND = 'Login';
$UI_AD_RID = array($UI_CW_Role,$UI_CW_Id);
$UI_AD_ASD = 'Toiminnassa';

//common phrases
DEFINE('_JANUARY','tammikuu');
DEFINE('_FEBRUARY','helmikuu');
DEFINE('_MARCH','maaliskuu');
DEFINE('_APRIL','huhtikuu');
DEFINE('_MAY','toukokuu');
DEFINE('_JUNE','kesäkuu');
DEFINE('_JULY','heinäkuu');
DEFINE('_AUGUST','elokuu');
DEFINE('_SEPTEMBER','syyskuu');
DEFINE('_OCTOBER','lokakuu');
DEFINE('_NOVEMBER','marraskuu');
DEFINE('_DECEMBER','joulukuu');
DEFINE('_LAST','Viimeinen');
DEFINE('_FIRST','First'); //NEW
DEFINE('_TOTAL','Total'); //NEW
DEFINE('_ALERT','Hälytykset');
DEFINE('_ADDRESS','Osoite');
DEFINE('_UNKNOWN','tuntematon');
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

//Menu items
DEFINE('_HOME','Koti');
DEFINE('_SEARCH','Etsi');
DEFINE('_AGMAINT','Hälytys-ryhmä:n Ylläpito');
DEFINE('_USERPREF','Käyttäjän asetukset');
DEFINE('_CACHE','Välimuisti & Status');
DEFINE('_ADMIN','Hallinta');
DEFINE('_GALERTD','Graph Hälytys Data');//#
DEFINE('_GALERTDT','Graph Hälytys Detection Aika');//#
DEFINE('_USERMAN','Käyttäjien Hallinta');
DEFINE('_LISTU','Listaa käyttäjät');
DEFINE('_CREATEU','Luo käyttäjät');
DEFINE('_ROLEMAN',"$UI_CW_Role Hallinta");//#
DEFINE('_LISTR','Listaa roles');//#
DEFINE('_CREATER',"Luo $UI_CW_Role");//#
DEFINE('_LISTALL','Listaa Kaikki');
DEFINE('_CREATE','Luo');
DEFINE('_VIEW','Katsele');
DEFINE('_CLEAR','Tyhjää');
DEFINE('_LISTGROUPS','Listaa Ryhmät');
DEFINE('_CREATEGROUPS','Luo Ryhmät');
DEFINE('_VIEWGROUPS','Näytä Ryhmät');
DEFINE('_EDITGROUPS','Muuta Ryhmät');
DEFINE('_DELETEGROUPS','Poista Ryhmät');
DEFINE('_CLEARGROUPS','Clear Ryhmät');//#
DEFINE('_CHNGPWD','Vaihda '.strtolower($UI_CW_Pw));
DEFINE('_DISPLAYU','Näytä Käyttäjä');

//base_footer.php
DEFINE('_FOOTER','( <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnsonilta</A> ja <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE Projektin Tiimiltä</A><BR>Rakennettu ACID:n(Roman Danyliw) päälle )');//#

//index.php --Log in Page
DEFINE('_LOGINERROR','Käyttäjää ei ole tai antamasi '.strtolower($UI_CW_Pw).' on väärä!<br>Yritä uudelleen');

// base_main.php
DEFINE('_MOSTRECENT','Uusin ');
DEFINE('_MOSTFREQUENT','Tiheiten Esiintyvä ');
DEFINE('_ALERTS',' Hälytykset:');
DEFINE('_ADDRESSES',' Osoitteet');
DEFINE('_ANYPROTO','mikä tahansa protokolla');
DEFINE('_UNI','uniikki');
DEFINE('_LISTING','listaus');
DEFINE('_TALERTS','Tämänpäiväiset hälytykset: ');
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_L24ALERTS','Viimeisen 24 Tunnin hälytykset: ');
DEFINE('_L72ALERTS','Viimeisen 72 Tunnin hälytykset: ');
DEFINE('_UNIALERTS',' Uniikit hälytykset');
DEFINE('_LSOURCEPORTS','Viimeisimmät Lähde-Portit: ');
DEFINE('_LDESTPORTS','Viimeisimmät Kohde-Portit: ');
DEFINE('_FREGSOURCEP','Tiheiten Esiintyvät Lähde-Portit: ');
DEFINE('_FREGDESTP','Tiheiten Esiintyvät Kohde-Portit: ');
DEFINE('_QUERIED','Queried on');//#
DEFINE('_DATABASE','Tietokanta:');
DEFINE('_SCHEMAV','Scheman Versio:');//#
DEFINE('_TIMEWIN','Aika-ikkuna:');
DEFINE('_NOALERTSDETECT','hälytyksiä ole havaittu');
DEFINE('_USEALERTDB','Use Alert Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW

//base_auth.inc.php
DEFINE('_ADDEDSF','Lisätty Onnistuneesti');
DEFINE('_NOPWDCHANGE',$UI_CW_Pw.'nasi vaihtaminen ei onnistu: ');
DEFINE('_NOUSER','Käyttäjää ei ole!');
DEFINE('_OLDPWD','Annettua vanhaa '.strtolower($UI_CW_Pw).'a ei tunnisteta!');
DEFINE('_PWDCANT',$UI_CW_Pw.'si vaihtaminen ei onnistu: ');
DEFINE('_PWDDONE',$UI_CW_Pw.'si on vaihdettu!');
DEFINE('_ROLEEXIST',"$UI_CW_Role On Jo Olemassa");//#
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." On Jo Olemassa");
DEFINE('_ROLEADDED',"$UI_CW_Role lisätty");//#

//base_roleadmin.php
DEFINE('_ROLEADMIN',"BASE $UI_CW_Role Administration");//#
DEFINE('_FRMROLENAME',"$UI_CW_Role Nimi:");//#
DEFINE('_UPDATEROLE',"Update $UI_CW_Role"); //NEW

//base_useradmin.php
DEFINE('_USERADMIN','BASE Käyttäjä Hallinta');
DEFINE('_FRMFULLNAME','Koko nimi:');
DEFINE('_FRMUID',"Käyttäjä $UI_CW_Id:");//#
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW

//admin/index.php
DEFINE('_BASEADMIN','BASE Hallinta');
DEFINE('_BASEADMINTEXT','Valitse yksi vaihtoehto vasemmalta.');

//base_action.inc.php
DEFINE('_NOACTION','Yhtään action ei määritelty on the alerts');//#
DEFINE('_INVALIDACT',' on laiton(invalid) action');//#
DEFINE('_ERRNOAG','Hälytyksen lisääminen ei onnistunut koska AG:a ei määritelty ');
DEFINE('_ERRNOEMAIL','Hälytysten mailaaminen ei onnistunut koska email-osoitetta ei ole määritelty');
DEFINE('_ACTION','ACTION');//#
DEFINE('_CONTEXT','konteksti');
DEFINE('_ADDAGID',"Lisää AG:iin ( $UI_CW_Id:llä");//#
DEFINE('_ADDAG','Lisää uusi AG');//#
DEFINE('_ADDAGNAME','Lisää AG:iin (Nimellä');
DEFINE('_CREATEAG','Luo AG (Nimellä');
DEFINE('_CLEARAG','Posta AG:sta');
DEFINE('_DELETEALERT','Poista hälytykset');
DEFINE('_EMAILALERTSFULL','Email hälytykset (täysi)');
DEFINE('_EMAILALERTSSUMM','Email hälytykset (yhteenveto)');
DEFINE('_EMAILALERTSCSV','Email hälytykset (csv)');//#
DEFINE('_ARCHIVEALERTSCOPY','Arkistoi hälytykset (kopioi)');
DEFINE('_ARCHIVEALERTSMOVE','Arkistoi hälytykset (siirr�');
DEFINE('_IGNORED','Jätetty Huomiotta ');
DEFINE('_DUPALERTS',' useasti esiintyvät hälytykset');
DEFINE('_ALERTSPARA',' hälytykset');
DEFINE('_NOALERTSSELECT','Yhtään hälytystä valittu tai');
DEFINE('_NOTSUCCESSFUL','ei onnistunut');
DEFINE('_ERRUNKAGID',"Tuntematon AG $UI_CW_Id annettu (AG:a ei luultavasti ole olemassa)");//#
DEFINE('_ERRREMOVEFAIL','Uuden AG:n poistaminen ei onnistunut');
DEFINE('_GENBASE','BASE:n generoima');
DEFINE('_ERRNOEMAILEXP','EXPORT ERROR: Exported hälytykset lähettäminen to');//#
DEFINE('_ERRNOEMAILPHP','Tarkista PHP:n sähköpostiasetukset.');
DEFINE('_ERRDELALERT','Error Poistettaessa Hälytystä');//#
DEFINE('_ERRARCHIVE','Arkisto error:');//#
DEFINE('_ERRMAILNORECP','MAIL ERROR: Vastaanottajaa ei määritelty');//#

//base_cache.inc.php
DEFINE('_ADDED','Lisätty ');
DEFINE('_HOSTNAMESDNS',' hostnames to the IP-DNS-välimuistiin');//#
DEFINE('_HOSTNAMESWHOIS',' hostnames to the Whois cache');//#
DEFINE('_ERRCACHENULL','Caching ERROR: NULL '.$UI_CW_Event.' row found?');//#
DEFINE('_ERRCACHEERROR',$UI_CW_Event.' CACHING ERROR:');//#
DEFINE('_ERRCACHEUPDATE',$UI_CW_Event.'välimuistin päivittäminen ei onnistunut');
DEFINE('_ALERTSCACHE',' hälytykset Hälytysvälimuistiin');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','SQL trace tidoston avaminen ei onnistu');//#
DEFINE('_ERRSQLCONNECT','Virhe yhdistettaessä tietokantaan :');
DEFINE('_ERRSQLCONNECTINFO','<P>Tarkista DB connection variables(tietokantayhteyden muuttujat) tiedostosta <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : MySQL tietokannan nimi johon hälytykset on tallennettu 
               = $alert_host     : isäntä johon tietokanta on tallennettu
               = $alert_port     : portti johon tietokanta on tallennettu
               = $alert_user     : käyttäjänimi tietokantaan
               = $alert_password : '.strtolower($UI_CW_Pw).' käyttäjänimelle
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Error ?(p)? yhditettäessä tietokantaan :');//#
DEFINE('_ERRSQLDB','Tietokanta ERROR:');//#
DEFINE('_DBALCHECK','Tarkastaa tietokannan-abstraktio-kirjastoa in');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Virhe Ladattaessa tietokannan-abstraktio-kirjastoa : </B> from ');
DEFINE('_ERRSQLDBALLOAD2','<P>Tarkista tietokannan-abstraktio-kirjasto muuttuja <CODE>$DBlib_path</CODE> tiedostossa <CODE>base_conf.php</CODE>
            <P>
             Tällä hetkellä käytössa oleva tietokanta-kirjasto on nimeltään ADODB, jonka voi ladata osoitteesta
             ');
DEFINE('_ERRSQLDBTYPE','Määritelty tietokannan tyyppi on virheellinen');
DEFINE('_ERRSQLDBTYPEINFO1','Muuttuja <CODE>\$DBtype</CODE> tiedostossa <CODE>base_conf.php</CODE> oli asetettu mainittuun virheelliseen tietokannan tyyppiin ');
DEFINE('_ERRSQLDBTYPEINFO2','Vain seuraavat tietokannat ovat tuettuja: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','BASE FATAL ERROR:');//#

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Ladattu');
DEFINE('_SECONDS','sekunnissa');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Unable to resolve osoite');//#

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Query Results Output Header');//#

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','SigName unknown');//#
DEFINE('_ERRSIGPROIRITYUNK','SigPriority unknown');//#
DEFINE('_UNCLASS','unclassified');//#

//base_state_citems.inc.php
DEFINE('_DENCODED','data encoded as');//#
DEFINE('_NODENCODED','(ei datan konversiota, assuming criteria tietokannassa native koodaus)');//#
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
DEFINE('_SIGCLASS',"$UI_CW_Sig Classification"); //NEW
DEFINE('_SIGPRIO',"$UI_CW_Sig Priority"); //NEW
DEFINE('_SHORTSOURCE','Source'); //NEW
DEFINE('_SHORTDEST','Dest'); //NEW
DEFINE('_SHORTSOURCEORDEST','Src or Dest'); //NEW
DEFINE('_NOLAYER4','no layer4'); //NEW
DEFINE('_INPUTCRTENC','Input Criteria Encoding Type'); //NEW
DEFINE('_CONVERT2WS','Convert To (when searching)'); //NEW

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','PHP ERROR: A custom (user) PHP session on havaittu. Kuitenkaan, BASE:ä ei ole asetettu to explicitly use this custom handler. Aseta <CODE>use_user_session=1</CODE> tiedostossa <CODE>base_conf.php</CODE>');
DEFINE('_PHPERRORCSESSIONCODE','PHP ERROR: A custom (user) PHP session handler on konfiguroitu, mutta annettu handler määritelty tiedotossa <CODE>user_session_path</CODE> on laiton(invalid).');//#
DEFINE('_PHPERRORCSESSIONVAR','PHP ERROR: A custom (user) PHP session handler on konfiguroitu, mutta the implementation of this handler ei ole määritelty BASE:ssä.  If a custom session handler on toivottavaa, aseta <CODE>user_session_path</CODE> muuttuja tiedostossa <CODE>base_conf.php</CODE>.');//#
DEFINE('_PHPSESSREG','Session Registered');//#

//base_state_criteria.inc.php
DEFINE('_REMOVE','Poistamassa');
DEFINE('_FROMCRIT','from criteria');//#
DEFINE('_ERRCRITELEM','Invalid criteria element');//#

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Valid Canned Query List');//#
DEFINE('_DISPLAYING','Displaying');//#
DEFINE('_DISPLAYINGTOTAL','Displaying alerts %d-%d of %d total');//#
DEFINE('_NOALERTS','Ei havaittuja hälytyksiä.');
DEFINE('_QUERYRESULTS','Query Results');//#
DEFINE('_QUERYSTATE','Query State');//#
DEFINE('_DISPACTION','{ action }'); //NEW

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','Annettu AG nimi-etsintä on laiton(invalid).  Yritä uudelleen!');
DEFINE('_ERRAGNAMEEXIST','Annettu AG:a ei ole olemassa.');
DEFINE('_ERRAGIDSEARCH',"Annettu AG $UI_CW_Id etsintä on laiton(invalid).  Yritä uudelleen!");
DEFINE('_ERRAGLOOKUP','Error looking up an AG $UI_CW_Id:ta');//#
DEFINE('_ERRAGINSERT','Error Asetettaessa uutta AG:a');//#

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Hälytys-ryhmä (Alert Group - AG) Ylläpito');//#
DEFINE('_ERRAGUPDATE','Error päivitettäessä AG:a');//#
DEFINE('_ERRAGPACKETLIST','Error poistettaessa AG:n paketti-listaa:');//#
DEFINE('_ERRAGDELETE','Error poistettaessa AG:a');//#
DEFINE('_AGDELETE','POISTETTU Onnistuneesti');
DEFINE('_AGDELETEINFO','tieto poistettu');
DEFINE('_ERRAGSEARCHINV','Annetty hakukriteeri ei ole laillinen(valid).  Yritä uudelleen!');
DEFINE('_ERRAGSEARCHNOTFOUND','Yhtään AG:a ei löydetty tuolla kriteerillä.');
DEFINE('_NOALERTGOUPS','Yhtään AG:a ei löydy');
DEFINE('_NUMALERTS','# Hälytykset');
DEFINE('_ACTIONS','Actions');//#
DEFINE('_NOTASSIGN','not assigned yet');//#
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW

//base_common.php
DEFINE('_PORTSCAN','Portscan Traffic');//#

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Indexin luominen ei onnistu for');//#
DEFINE('_DBINDEXCREATE','Onnistuneesti luotu INDEXI for');//#
DEFINE('_ERRSNORTVER','Se saattaa olla vanhempaa versiota. Vain Snort 1.7-beta:lla tai uudemalla luodut tietokannat ovat tuettuja ');
DEFINE('_ERRSNORTVER1','Käytössä oleva tietokanta');
DEFINE('_ERRSNORTVER2','näyttää olevan epätäydellinen/laiton(invalid)');
DEFINE('_ERRDBSTRUCT1','Tietokannan versio on käypä, mutta BASE-tietokannan rakenne');
DEFINE('_ERRDBSTRUCT2','ei ole saatavilla. Käytä <A HREF="base_db_setup.php">Asetus Sivua</A> konfiguroidaksesi ja optimoidaksesi tietokannan.');
DEFINE('_ERRPHPERROR','PHP ERROR');//#
DEFINE('_ERRPHPERROR1','Yhteensopimaton versio');
DEFINE('_ERRVERSION','Versio');
DEFINE('_ERRPHPERROR2','(PHP) on liian vanha. Päivitä PHP:n versioon 4.0.4 tai uudempaan');
DEFINE('_ERRPHPMYSQLSUP','<B>PHP käännös(build) epätäydellinen</B>: <FONT>the prerequisite MySQL:n tuki joka vaaditaan hälytystietokannan lukemiseen ei ole käännetty PHP:n sisään.
                   Käännä PHP uudelleen tarvittavien kirjastojen kanssa (<CODE>--with-mysql</CODE>)</FONT>');//#
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP käännös(build) epätäydellinen</B>: <FONT>the prerequisite PostgreSQL:n tuki joka vaaditaan hälytystietokannan lukemiseen ei ole käännetty PHP:n sisään.
                   Käännä PHP uudelleen tarvittavien kirjastojen kanssa (<CODE>--with-pgsql</CODE>)</FONT>');//#
DEFINE('_ERRPHPMSSQLSUP','<B>PHP käännös(build) epätäydellinen</B>: <FONT>the prerequisite MS SQL Serverin tuki joka vaaditaan hälytystietokannan lukemiseen ei ole käännetty PHP:n sisään.
                   Käännä PHP uudelleen tarvittavien kirjastojen kanssa (<CODE>--enable-mssql</CODE>)</FONT>');//#
DEFINE('_ERRPHPORACLESUP','<B>PHP build incomplete</B>: <FONT>the prerequisite Oracle support required to 
                   read the alert database was not built into PHP.  
                   Please recompile PHP with the necessary library (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Kuvaajan Otsikko:');
DEFINE('_CHRTTYPEHOUR','Aika (tunti) vs. Hälytysten Määrä');
DEFINE('_CHRTTYPEDAY','Aika (päivä) vs. Hälytysten Määrä');
DEFINE('_CHRTTYPEWEEK','Aika (viikko) vs. Hälytysten Määrä');
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
DEFINE('_CHRTTYPEMONTH','Aika (kuukausi) vs. Hälytysten Määrä');
DEFINE('_CHRTTYPEYEAR','Aika (vuosi) vs. Hälytysten Määrä');
DEFINE('_CHRTTYPESRCIP','Lähde IP osoite vs. Hälytysten Määrä');
DEFINE('_CHRTTYPEDSTIP','Kohde IP osoite vs. Hälytysten Määrä');
DEFINE('_CHRTTYPEDSTUDP','Kohde UDP Portti vs. Hälytysten Määrä');
DEFINE('_CHRTTYPESRCUDP','Lähde UDP Portti vs. Hälytysten Määrä');
DEFINE('_CHRTTYPEDSTPORT','Kohde TCP Portti vs. Hälytysten Määrä');
DEFINE('_CHRTTYPESRCPORT','Lähde TCP Portti vs. Hälytysten Määrä');
DEFINE('_CHRTTYPESIG','Sig. Classification vs. Hälytysten Määrä');//#
DEFINE('_CHRTTYPESENSOR','Sensori vs. Hälytysten Määrä');
DEFINE('_CHRTBEGIN','Kuvaaja Alkaa(Chart Begi):');
DEFINE('_CHRTEND','Kuvaaja Loppuu(Chart End):');
DEFINE('_CHRTDS','Data Lähde:');
DEFINE('_CHRTX','X-akseli');
DEFINE('_CHRTY','Y-akseli');
DEFINE('_CHRTMINTRESH','Minimum Threshold Value');//#
DEFINE('_CHRTROTAXISLABEL','Pyöritä Akseli Labels (90 astetta)');//#
DEFINE('_CHRTSHOWX','Näytä X-akseli grid-lines');//#
DEFINE('_CHRTDISPLABELX','Display X-akseli label every');//#
DEFINE('_CHRTDATAPOINTS','data points');//#
DEFINE('_CHRTYLOG','Y-akseli logarithmic');//#
DEFINE('_CHRTYGRID','Näytä Y-akseli grid-lines');//#

//base_graph_main.php
DEFINE('_CHRTTITLE','BASE Kuvaaja');
DEFINE('_ERRCHRTNOTYPE','Kuvaajan tyyppiä ei ole määritelty');
DEFINE('_ERRNOAGSPEC','Yhtään AG:a ei määritelty. Käytetään kaikkia hälytyksiä.');
DEFINE('_CHRTDATAIMPORT','Starting data import');//#
DEFINE('_CHRTTIMEVNUMBER','Aika vs. Hälytysten Määrä');
DEFINE('_CHRTTIME','Aika');
DEFINE('_CHRTALERTOCCUR','Hälytysten Esiintymät');
DEFINE('_CHRTSIPNUMBER','Lähde IP vs. Hälytysten Määrä');
DEFINE('_CHRTSIP','Lähde IP Osoite');
DEFINE('_CHRTDIPALERTS','Kohde IP vs. Hälytysten Määrä');
DEFINE('_CHRTDIP','Kohde IP Osoite');
DEFINE('_CHRTUDPPORTNUMBER','UDP Portti (Kohde) vs. Hälytysten Määrä');
DEFINE('_CHRTDUDPPORT','Kohde UDP Portti');
DEFINE('_CHRTSUDPPORTNUMBER','UDP Portti (Lähde) vs. Hälytysten Määrä');
DEFINE('_CHRTSUDPPORT','Lähde UDP Portti');
DEFINE('_CHRTPORTDESTNUMBER','TCP Portti (Kohde) vs. Hälytysten Määrä');
DEFINE('_CHRTPORTDEST','Kohde TCP Portti');
DEFINE('_CHRTPORTSRCNUMBER','TCP Portti (Lähde) vs. Hälytysten Määrä');
DEFINE('_CHRTPORTSRC','Lähde TCP Portti');
DEFINE('_CHRTSIGNUMBER',"$UI_CW_Sig Classification vs. Hälytysten Määrä");//#
DEFINE('_CHRTCLASS','Classification');//#
DEFINE('_CHRTSENSORNUMBER','Sensori vs. Hälytysten Määrä');
DEFINE('_CHRTHANDLEPERIOD','Käsittelyaika jos tarpeellista ');
DEFINE('_CHRTDUMP','Dumping data ... (writing only every');//#
DEFINE('_CHRTDRAW','Piirtämässä graafia');
DEFINE('_ERRCHRTNODATAPOINTS','No data points to plot');//#

//base_maintenance.php
DEFINE('_MAINTTITLE','Ylläito');
DEFINE('_GRAPHALERTDATA','Graph Alert Data'); //NEW
DEFINE('_MNTPHP','PHP käännös(build)versio:');
DEFINE('_MNTCLIENT','ASIAKAS:');
DEFINE('_MNTSERVER','SERVERI:');
DEFINE('_MNTSERVERHW','SERVER HW:');//#
DEFINE('_MNTPHPVER','PHP VERSIONUMERO:');
DEFINE('_MNTPHPAPI','PHP API:');
DEFINE('_MNTPHPLOGLVL','PHP Logging level:');//#
DEFINE('_MNTPHPMODS','Ladatutu Moduulit:');
DEFINE('_MNTDBTYPE','Tietokannan Tyyppi:');
DEFINE('_MNTDBALV','Tietokannan-abstrtaktio-versio:');
DEFINE('_MNTDBALERTNAME','ALERT DB Name:');//#
DEFINE('_MNTDBARCHNAME','ARCHIVE DB Name:');//#
DEFINE('_MNTAIC','Hälytystietojen Välimuisti:');
DEFINE('_MNTAICTE','Tapahtumia Yhteensä:');
DEFINE('_MNTAICCE','Cached Events:');//#
DEFINE('_MNTIPAC','IP Osoitteiden välimuisti');
DEFINE('_MNTIPACUSIP','Uniikki Lähde IP:');
DEFINE('_MNTIPACDNSC','DNS Cached:');//#
DEFINE('_MNTIPACWC','Whois Cached:');//#
DEFINE('_MNTIPACUDIP','Uniikki Kohde IP:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Invalid (sid,cid) pair');//#
DEFINE('_QAALERTDELET','Hälytys POISTETTU');
DEFINE('_QATRIGGERSIG',"Triggered $UI_CW_Sig");//#
DEFINE('_QANORMALD','Normal Display'); //NEW
DEFINE('_QAPLAIND','Plain Display'); //NEW
DEFINE('_QANOPAYLOAD','Fast logging used so payload was discarded'); //NEW

//base_qry_common.php
DEFINE('_QCSIG','signature');//#
DEFINE('_QCIPADDR','IP osoitteet');
DEFINE('_QCIPFIELDS','IP kentät');
DEFINE('_QCTCPPORTS','TCP portit');
DEFINE('_QCTCPFLAGS','TCP flags');//#
DEFINE('_QCTCPFIELD','TCP kentät');
DEFINE('_QCUDPPORTS','UDP portit');
DEFINE('_QCUDPFIELDS','UDP kentät');
DEFINE('_QCICMPFIELDS','ICMP kentät');
DEFINE('_QCDATA','Data');
DEFINE('_QCERRCRITWARN','Criteria varoitus:');//#
DEFINE('_QCERRVALUE','A value of');//#
DEFINE('_QCERRFIELD','A field of');//#
DEFINE('_QCERROPER','An operator of');//#
DEFINE('_QCERRDATETIME','A date/time value of');//#
DEFINE('_QCERRPAYLOAD','A payload value of');//#
DEFINE('_QCERRIP','An IP osoite of');//#
DEFINE('_QCERRIPTYPE','IP-osoite tyypiltään');
DEFINE('_QCERRSPECFIELD',' annettiin protkolla-kenttää varten, mutta kyseistä kenttää ei määritelty.');//#
DEFINE('_QCERRSPECVALUE','was selected indicating that it should be a criteria, but no value was specified on which to match.');//#
DEFINE('_QCERRBOOLEAN','Multiple protocol field criteria entered without a boolean operator (e.g. AND, OR) between them.');//#
DEFINE('_QCERRDATEVALUE','was selected indicating that some date/time criteria should be matched, but no value was specified.');//#
DEFINE('_QCERRINVHOUR','(Invalid Hour) No date criteria were entered with the specified time.');//#
DEFINE('_QCERRDATECRIT','was selected indicating that some date/time criteria should be matched, but no value was specified.');//#
DEFINE('_QCERROPERSELECT','annettiin mutta yhtään operaattoria ei valittu.');
DEFINE('_QCERRDATEBOOL','Usea Pvm./Aika kriteeri annettu ilman boolean-operaattoreita(esim. AND, OR) niiden välissä.');
DEFINE('_QCERRPAYCRITOPER','was entered for a payload criteria field, but an operator (e.g. has, has not) was not specified.');//#
DEFINE('_QCERRPAYCRITVALUE','was selected indicating that payload should be a criteria, but no value on which to match was specified.');//#
DEFINE('_QCERRPAYBOOL','Multiple Data payload criteria entered without a boolean operator (e.g. AND, OR) between them.');//#
DEFINE('_QCMETACRIT','Meta Criteria');//#
DEFINE('_QCIPCRIT','IP Criteria');//#
DEFINE('_QCPAYCRIT','Payload Criteria');//#
DEFINE('_QCTCPCRIT','TCP Criteria');//#
DEFINE('_QCUDPCRIT','UDP Criteria');//#
DEFINE('_QCICMPCRIT','ICMP Criteria');//#
DEFINE('_QCERRINVIPCRIT','Invalid IP osoite criteria');//#
DEFINE('_QCERRCRITADDRESSTYPE','was entered for as a criteria value, but the type of osoite (e.g. source, destination) was not specified.');//#
DEFINE('_QCERRCRITIPADDRESSNONE','indicating that an IP osoite should be a criteria, but no osoite on which to match was specified.');//#
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QCERRCRITIPADDRESSNONE1','was selected (at #');//#
DEFINE('_QCERRCRITIPIPBOOL','Multiple IP osoite criteria entered without a boolean operator (e.g. AND, OR) between IP Criteria');//#

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Sort order');//#
DEFINE('_QFRMTIMEA','aikaleima (nouseva)');
DEFINE('_QFRMTIMED','aikaleima (laskeva)');
DEFINE('_QFRMSIG','signature');//#
DEFINE('_QFRMSORTNONE','none'); //NEW
DEFINE('_QFRMSIP','lähde IP');
DEFINE('_QFRMDIP','kohde IP');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Summary Statistics');//#
DEFINE('_QSCTIMEPROF','Aika profiili');
DEFINE('_QSCOFALERTS','hälytyksistä');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','Hälytyslistaus');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Categories:');//#
DEFINE('_SCSENSORTOTAL','Sensorit/Yhteens�');
DEFINE('_SCTOTALNUMALERTS','Hälytysten kokonaismäärä');
DEFINE('_SCSRCIP','Lähde IP osoite:');
DEFINE('_SCDSTIP','Kohde IP osoite:');
DEFINE('_SCUNILINKS','Unikiit IP linkit');
DEFINE('_SCSRCPORTS','Lähde Portit: ');
DEFINE('_SCDSTPORTS','Kohde Portit: ');
DEFINE('_SCSENSORS','Sensorit');
DEFINE('_SCCLASS','luokittelut');
DEFINE('_SCUNIADDRESS','Uniikit osoitteet: ');
DEFINE('_SCSOURCE','Lähde');
DEFINE('_SCDEST','Kohde');
DEFINE('_SCPORT','Portti');

//base_stat_ipaddr.php
DEFINE('_PSEVENTERR','PORTSCAN EVENT ERROR: ');//#
DEFINE('_PSEVENTERRNOFILE','Yhtään tiedostoa ei ole asetettu $portscan_file muuttujaan.');
DEFINE('_PSEVENTERROPENFILE','Porttiskannaus-'.$UI_CW_Event.'-tiedoston(portscan event file) avaaminen ei onnistu');
DEFINE('_PSDATETIME','Pvm./Aika');
DEFINE('_PSSRCIP','Lähde IP');
DEFINE('_PSDSTIP','Kohde IP');
DEFINE('_PSSRCPORT','Lähde Portti');
DEFINE('_PSDSTPORT','Kohde Portti');
DEFINE('_PSTCPFLAGS','TCP Flags');//#
DEFINE('_PSTOTALOCC','Esiintymiä<BR> Yhteensä');
DEFINE('_PSNUMSENSORS','Sensorien Määrä');
DEFINE('_PSFIRSTOCC','Ensimmäinen<BR> Esiintymä');
DEFINE('_PSLASTOCC','Viimeinen<BR> Esiintymä');
DEFINE('_PSUNIALERTS','Uniikit Hälytykset');
DEFINE('_PSPORTSCANEVE','Porttiskannaukset');
DEFINE('_PSREGWHOIS','Registry lookup (whois) in');//#
DEFINE('_PSNODNS','yhtään DNS resolution ei yritetty');//#
DEFINE('_PSNUMSENSORSBR','Sensorien <BR>Määrä');
DEFINE('_PSOCCASSRC','Ilmentymät <BR>as Lähde');//#
DEFINE('_PSOCCASDST','Ilmentymät <BR>as Kohde');//#
DEFINE('_PSWHOISINFO','Whois Information');//#

//base_stat_iplink.php
DEFINE('_SIPLTITLE','IP Linkit');
DEFINE('_PSTOTALHOSTS','Total Hosts Scanned'); //NEW
DEFINE('_PSDETECTAMONG','%d unique alerts detected among %d alerts on %s'); //NEW
DEFINE('_PSALLALERTSAS','all alerts with %s/%s as'); //NEW
DEFINE('_PSSHOW','show'); //NEW
DEFINE('_PSEXTERNAL','external'); //NEW
DEFINE('_SIPLSOURCEFGDN','Lähde FQDN');//#
DEFINE('_SIPLDESTFGDN','Kohde FQDN');//#
DEFINE('_SIPLDIRECTION','Suunta');
DEFINE('_SIPLPROTO','Protokolla');
DEFINE('_SIPLUNIDSTPORTS','Uniikki Kohde Portti');
DEFINE('_SIPLUNIEVENTS','Uniikit '.$UI_CW_Event.'t');
DEFINE('_SIPLTOTALEVENTS','Kaikki '.$UI_CW_Event.'t');

//base_stat_ports.php
DEFINE('_UNIQ','Uniikki');
DEFINE('_DSTPS','Kohde Portit');
DEFINE('_SRCPS','Lähde Portit');

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Sensori Listaus');
DEFINE('_OCCURRENCES','Occurrences'); //NEW

//base_stat_time.php
DEFINE('_BSTTITLE','Hälytysten Aikaprofiili');
DEFINE('_BSTTIMECRIT','Aika Kriteeri');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>Yhtään profilointikriteeriä ei annettu!</B>  klikkaa "tunti(hour)", "päivä(day)" tai "kuukausi(month)" valitaksesi the granularity of the aggregate statistics.</FONT>');//#
DEFINE('_BSTERRTIMETYPE','<FONT><B>The type of time parameter which will be passed was not specified!</B>  Valitse joko "on", valitaksesi yksittäisen päivän, tai "between" valitaksesi tietyn aikävälin.</FONT>');//#
DEFINE('_BSTERRNOYEAR','<FONT><B>Yhtään "vuosi(year)"-parametria ei annettu!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>Yhtään "kuukausi(month)"-parametria ei annettu!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>Yhtään "päivä(day)"-parametria ei annettu!</B></FONT>');

//base_stat_uaddr.php
DEFINE('_UNISADD','Uniikit Lähde-IP-osoitteet');
DEFINE('_BSTPROFILEBY','Profile by'); //NEW
DEFINE('_TIMEON','on'); //NEW
DEFINE('_TIMEBETWEEN','between'); //NEW
DEFINE('_PROFILEALERT','Profile Alert'); //NEW
DEFINE('_SUASRCIP','Lähde-IP-osoitteet');
DEFINE('_SUAERRCRITADDUNK','CRITERIA ERROR: tuntematon osoitteen tyyppi -- assuming Kohde osoite');//#
DEFINE('_UNIDADD','Uniikit Kohde-IP-osoitteet');
DEFINE('_SUADSTIP','Kohde-IP-osoitteet');
DEFINE('_SUAUNIALERTS','Uniikite&nbsp;Hälytykset');
DEFINE('_SUASRCADD','Lähde&nbsp;Osoite');
DEFINE('_SUADSTADD','Kohde&nbsp;Osoite');

//base_user.php
DEFINE('_BASEUSERTITLE','BASE Käyttäjän asetukset');
DEFINE('_BASEUSERERRPWD',"$UI_CW_Pw ei voi olla tyhjä tai ".strtolower($UI_CW_Pw).'t eivät täsmää!');
DEFINE('_BASEUSEROLDPWD','Vanha '.$UI_CW_Pw.':');
DEFINE('_BASEUSERNEWPWD','Uusi '.$UI_CW_Pw.':');
DEFINE('_BASEUSERNEWPWDAGAIN',"Uusi $UI_CW_Pw Uudestaan:");

DEFINE('_LOGOUT','Kirjaudu ulos');

?>
