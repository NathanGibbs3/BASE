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
** Purpose: English language file
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
DEFINE('_LOCALESTR1', 'eng_ENG.ISO8859-1'); //NEW
DEFINE('_LOCALESTR2', 'eng_ENG.utf-8'); //NEW
DEFINE('_LOCALESTR3', 'english'); //NEW
DEFINE('_STRFTIMEFORMAT','%a %B %d, %Y %H:%M:%S'); //NEW - see strftime() sintax

//common phrases
DEFINE('_CHARSET','iso-8859-1');
DEFINE('_TITLE','Basic Analysis and Security Engine (BASE) '.$BASE_installID);
// Basic Analysis and Security Engine (BASE)
DEFINE('_FRMLOGIN','Identifiant:');
// Login:
DEFINE('_FRMPWD','Mot de passe:');
// Password:
DEFINE('_SOURCE','Source');
// Source
DEFINE('_SOURCENAME','Nom de la Source');
// Source Name
DEFINE('_DEST','Destination');
// Destination
DEFINE('_DESTNAME','Nom de la Destination');
// Dest. Name
DEFINE('_SORD','Src ou Dest');
// Src or Dest
DEFINE('_EDIT','Modifier');
// Edit
DEFINE('_DELETE','Supprimer');
// Delete
DEFINE('_ID','ID');
// ID
DEFINE('_NAME','Nom');
// Name
DEFINE('_INTERFACE','Interface');
// Interface
DEFINE('_FILTER','Filtre');
// Filter
DEFINE('_DESC','Description');
// Description
DEFINE('_LOGIN','Identifiant');
// Login
DEFINE('_ROLEID','Identifiant de R�le');
// Role ID
DEFINE('_ENABLED','Activ�');
// Enabled
DEFINE('_SUCCESS','Succ�s -');
// Successful
DEFINE('_SENSOR','Sonde');
// Sensor
DEFINE('_SIGNATURE','Signature');
// Signature
DEFINE('_TIMESTAMP','Horodatage');
// Timestamp
DEFINE('_NBSOURCEADDR','Adresse&nbsp;Source');
// Source&nbsp;Address
DEFINE('_NBDESTADDR','Adresse&nbsp;Dest.');
// Dest.&nbsp;Address
DEFINE('_NBLAYER4','Protocole&nbsp;de&nbsp;niveau&nbsp;4');
// Layer&nbsp;4&nbsp;Proto
DEFINE('_PRIORITY','Priorit�');
// Priority
DEFINE('_EVENTTYPE','type d\'�v�nement');
// event type
DEFINE('_JANUARY','janvier');
// January
DEFINE('_FEBRUARY','f�vrier');
// February
DEFINE('_MARCH','mars');
// March
DEFINE('_APRIL','avril');
// April
DEFINE('_MAY','mai');
// May
DEFINE('_JUNE','juin');
// June
DEFINE('_JULY','juillet');
// July
DEFINE('_AUGUST','ao�t');
// August
DEFINE('_SEPTEMBER','septembre');
// September
DEFINE('_OCTOBER','octobre');
// October
DEFINE('_NOVEMBER','novembre');
// November
DEFINE('_DECEMBER','d�cembre');
// December
DEFINE('_LAST','Derni(er|�re)');
// Last
DEFINE('_ALERT','Alertes ');
// Alerts
DEFINE('_ADDRESS','Adresse');
// Address
DEFINE('_UNKNOWN','inconnu');
// unknown

//Menu items
DEFINE('_HOME','Accueil'); 
// Home
DEFINE('_SEARCH','Rechercher'); 
// Search
DEFINE('_AGMAINT','Maintenance des Groupes d\'Alertes'); 
// Alert Group Maintenance
DEFINE('_USERPREF','Pr�f�rences Utilisateur'); 
// User Preferences
DEFINE('_CACHE','Cache et Statut'); 
// Cache & Status
DEFINE('_ADMIN','Administration'); 
// Administration
DEFINE('_GALERTD','Cr�er des graphiques');
// Graph Alert Data
DEFINE('_GALERTDT','R�partition temporelle des alertes');
// Graph Alert Detection Time
DEFINE('_USERMAN','Gestion des Utilisateurs');
// User Management
DEFINE('_LISTU','Liste des utilisateurs');
// List users
DEFINE('_CREATEU','Cr�er un utilisateur');
// Create a user
DEFINE('_ROLEMAN','Gestion des R�les');
// Role Management
DEFINE('_LISTR','Liste des R�les');
// List Roles
DEFINE('_LOGOUT','Logout');
// Logout
DEFINE('_CREATER','Cr�er un R�le');
// Create a Role
DEFINE('_LISTALL','Tout Lister');
// List All
DEFINE('_CREATE','Cr�er'); 
// Create
DEFINE('_VIEW','Afficher'); 
// View
DEFINE('_CLEAR','Effacer'); 
// Clear
DEFINE('_LISTGROUPS','Liste des Groupes');
// List Groups
DEFINE('_CREATEGROUPS','Cr�er un Groupe');
// Create Group
DEFINE('_VIEWGROUPS','Afficher le Groupe');
// View Group
DEFINE('_EDITGROUPS','Modifier le Groupe');
// Edit Group
DEFINE('_DELETEGROUPS','Supprimer le Groupe');
// Delete Group
DEFINE('_CLEARGROUPS','Vider le Groupe');
// Clear Group
DEFINE('_CHNGPWD','Modifier le mot de passe');
// Change password
DEFINE('_DISPLAYU','Afficher l\'utilisateur');
// Display user
//base_footer.php
DEFINE('_FOOTER','( de <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnson</A> et <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">l\'�quipe du projet BASE </A><BR>B�tis sur ACID de Roman Danyliw )');

/*( by <A class="largemenuitem" href="mailto:kjohnson@secureideas.net">Kevin Johnson</A> and the BASE Project Team<BR>Built on ACID by Roman Danyliw )
*/

//index.php --Log in Page
DEFINE('_LOGINERROR','Utilisateur inconnu ou mot de passe incorrect !<br> Veuillez essayer � nouveau');
// User does not exist or your password was incorrect!<br>Please try again

// base_main.php
DEFINE('_MOSTRECENT','Alertes les plus r�centes - ');
// Most recent 
DEFINE('_MOSTFREQUENT','Alertes les plus fr�quentes - ');
// Most frequent 
DEFINE('_ALERTS',' alertes ');
//  Alerts:
DEFINE('_ADDRESSES','adresses ');
//  Addresses:
// This one is twice in this file - it makes a buggy homepage most frequent address text
DEFINE('_ANYPROTO','tous protocoles');
// any protocol
DEFINE('_UNI','unique');
// unique
DEFINE('_LISTING','liste');
// listing
DEFINE('_TALERTS','Alertes du jour :'); 
// Today\'s alerts: 
DEFINE('_L24ALERTS','Alertes des derni�res 24 heures :');
// Last 24 Hours alerts: 
DEFINE('_L72ALERTS','Alertes des derni�res 72 heures :');
// Last 72 Hours alerts: 
DEFINE('_UNIALERTS',' Alertes Uniques');
//  Unique Alerts
DEFINE('_LSOURCEPORTS','Derniers Port Source:');
// Last Source Ports: 
DEFINE('_LDESTPORTS','Derniers Port de Destination:');
// Last Destination Ports: 
DEFINE('_FREGSOURCEP','Ports Source les plus fr�quents:');
// Most Frequent Source Ports: 
DEFINE('_FREGDESTP','Ports de Destination les plus fr�quents:');
// Most Frequent Destination Ports: 
DEFINE('_QUERIED','Interrog� le');
// Queried on
DEFINE('_DATABASE','DB : ');
// Database:
DEFINE('_SCHEMAV','Version du Schema:');
// Schema Version:
DEFINE('_TIMEWIN','Fen�tre temporelle');
// Time Window:
DEFINE('_NOALERTSDETECT','aucune alerte detect�e');
// no alerts detected

//base_auth.inc.php
DEFINE('_ADDEDSF','Ajout r�ussi');
// Added Successfully
DEFINE('_NOPWDCHANGE','Impossible de modifier votre mot de passe: ');
// Unable to change your password: 
DEFINE('_NOUSER','Utilisateur inconnu');
// User doesn\'t exist!
DEFINE('_OLDPWD','Ancien mot de passe invalide');
// Old password entered doesn\'t match our records!
DEFINE('_PWDCANT','Impossible de modifier votre mot de passe');
// Unable to change your password: 
DEFINE('_PWDDONE','Votre mot de passe a �t� modifi�');
// Your password has been changed!
DEFINE('_ROLEEXIST','Ce R�le existe d�j�');
// Role Already Exists
DEFINE('_ROLEIDEXIST','Cet Identifiant de R�le existe d�j�');
// Role ID Already Exists
DEFINE('_ROLEADDED','Ajout de R�le r�ussi');
// Role Added Successfully

//base_roleadmin.php
DEFINE('_ROLEADMIN','Administration des R�les BASE ');
// BASE Role Administration
DEFINE('_FRMROLEID','Identifiant du R�le:');
// Role ID:
DEFINE('_FRMROLENAME','Nom du R�le');
// Role Name:
DEFINE('_FRMROLEDESC','Description: ');
// Description:

//base_useradmin.php
DEFINE('_USERADMIN','Administration des Utilisateurs BASE');
// BASE User Administration
DEFINE('_FRMFULLNAME','Nom compl�t (Pr�nom Nom):');
// Full Name:
DEFINE('_FRMROLE','R�le:');
// Role:
DEFINE('_FRMUID','Identifiant Utilisateur:');
// User ID:

//admin/index.php
DEFINE('_BASEADMIN','Administration BASE');
// BASE Administration
DEFINE('_BASEADMINTEXT','S�l�ctionner une option dans la liste � gauche SVP');
// Please select an option from the left.

//base_action.inc.php
DEFINE('_NOACTION','Aucune action n\'est pr�cis�e !');
// No action was specified on the alerts
DEFINE('_INVALIDACT',' est une action inadapt�e');
//  is an invalid action
DEFINE('_ERRNOAG','Impossible d\'ajouter les alertes car aucun Groupe d\'Alertes n\'est pr�cis�');
// Could not add alerts since no AG was specified
DEFINE('_ERRNOEMAIL','Impossible d\'envoyer les alertes car aucune adresse n\'est pr�cis�e');
// Could not email alerts since no email address was specified
DEFINE('_ACTION','ACTION');
// ACTION
DEFINE('_CONTEXT','contexte');
// context
DEFINE('_ADDAGID','Ajouter au Groupe d\'Alertes (par Identifiant)');
// ADD to AG (by ID)
DEFINE('_ADDAG','Ajouter un nouveau Groupe d\'Alertes');
// ADD-New-AG
DEFINE('_ADDAGNAME','Ajouter au Groupe d\'Alertes (par Nom)');
// ADD to AG (by Name)
DEFINE('_CREATEAG','Cr�er un Groupe d\'Alertes (par Nom)');
// Create AG (by Name)
DEFINE('_CLEARAG','Effacer du Groupe d\'Alertes');
// Clear from AG
DEFINE('_DELETEALERT','Supprimer les Alertes');
// Delete alert(s)
DEFINE('_EMAILALERTSFULL','Envoyer par Email (d�tail)');
// Email alert(s) (full)
DEFINE('_EMAILALERTSSUMM','Envoyer par Email (resum�)');
// Email alert(s) (summary)
DEFINE('_EMAILALERTSCSV','Envoyer par Email (csv)');
// Email alert(s) (csv)
DEFINE('_ARCHIVEALERTSCOPY','Archiver (copier)');
// Archive alert(s) (copy)
DEFINE('_ARCHIVEALERTSMOVE','Archiver (d�placer)');
// Archive alert(s) (move)
DEFINE('_IGNORED','Ignor� ');
// Ignored 
DEFINE('_DUPALERTS',' alerte(s) en double');
//  duplicate alert(s)
DEFINE('_ALERTSPARA',' alerte(s)');
//  alert(s)
DEFINE('_NOALERTSSELECT','Aucune alerte s�lectionn�e ou \'');
// No alerts were selected or the
DEFINE('_NOTSUCCESSFUL','\' a �chou�.');
// was not successful
DEFINE('_ERRUNKAGID','Identifiant de Groupe inconnu (Celui-ci n\'existe probablement pas)');
// Unknown AG ID specified (AG probably does not exist)
DEFINE('_ERRREMOVEFAIL','Impossible d\'effacer le nouveau Groupe');
// Failed to remove new AG
DEFINE('_GENBASE','G�n�r� par BASE');
// Generated by BASE
DEFINE('_ERRNOEMAILEXP','ERREUR D\'EXPORTATION: Impossible d\'envoyer les alertes export�es vers');
// EXPORT ERROR: Could not send exported alerts to
DEFINE('_ERRNOEMAILPHP','V�rifier la configuration mail dans PHP.');
// Check the mail configuration in PHP.
DEFINE('_ERRDELALERT','Erreur en supprimant Alerte');
// Error Deleting Alert
DEFINE('_ERRARCHIVE','Erreur d\'Archivage:');
// Archive error:
DEFINE('_ERRMAILNORECP','ERREUR MAIL: Aucun destinataire pr�cis�');
// MAIL ERROR: No recipient Specified

//base_cache.inc.php
DEFINE('_ADDED','Ajout� ');
// Added 
DEFINE('_HOSTNAMESDNS','noms d\'h�tes au cache IP DNS');
//  hostnames to the IP DNS cache
DEFINE('_HOSTNAMESWHOIS','noms d\'h�tes au cache Whois');
//  hostnames to the Whois cache
DEFINE('_ERRCACHENULL','ERREUR DE MISE EN CACHE: �v�nemement null?');
// Caching ERROR: NULL event row found?
DEFINE('_ERRCACHEERROR','ERREUR DE MISE EN CACHE D\'EVENEMENT:');
// EVENT CACHING ERROR:
DEFINE('_ERRCACHEUPDATE','Impossible de mettre � jour le cache d\'�v�nements');
// Could not update event cache
DEFINE('_ALERTSCACHE',' alerte(s) au cache d\'Alertes');
//  alert(s) to the Alert cache

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Impossible d\'ouvrir le fichier trace SQL');
// Unable to open SQL trace file
DEFINE('_ERRSQLCONNECT','Erreur de connexion � la base de donn�es :');
// Error connecting to DB :
DEFINE('_ERRSQLCONNECTINFO','<P>Contr�ler les variables de connexion � la base dans <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : nom de la base de donn�es o� les alertes sont stock�es 
               = $alert_host     : adresse de la machine o� la base de donn�es est localis�e
               = $alert_port     : port o� la base de donn�es est localis�e
               = $alert_user     : compte (username) pour acc�der � la base de donn�es
               = $alert_password : mot de passe pour ce compte
              </PRE>
              <P> ');
/*'<P>Check the DB connection variables in <I>base_conf.php</I> 
              <PRE>
               = $alert_dbname   : MySQL database name where the alerts are stored 
               = $alert_host     : host where the database is stored
               = $alert_port     : port where the database is stored
               = $alert_user     : username into the database
               = $alert_password : password for the username
              </PRE>
              <P>
*/
DEFINE('_ERRSQLPCONNECT','Erreur de connexion � la base (p)connect :');
// Error (p)connecting to DB :
DEFINE('_ERRSQLDB','ERREUR de la base de donn�es');
// Database ERROR:
DEFINE('_DBALCHECK','Recherche de la biblioth�que d\'abstraction de DB dans ');
// Checking for DB abstraction lib in
DEFINE('_ERRSQLDBALLOAD1','<P><B>Erreur lors du chargement de la biblioth�que d\'abstraction base de donn�es : </B> � partir de ');
// <P><B>Error loading the DB Abstraction library: </B> from 
DEFINE('_ERRSQLDBALLOAD2','<P> V�rifier la variable <CODE>$DBlib_path</CODE> dans <CODE>base_conf.php</CODE></P>
		<P>
		La bibliot�que sousjacente actuellement utilis�e est ADODB, t�l�chargeable
		 � ');
/*
<P>Check the DB abstraction library variable <CODE>$DBlib_path</CODE> in <CODE>base_conf.php</CODE>
            <P>
            The underlying database library currently used is ADODB, that can be downloaded
            at <A HREF="http://adodb.sourceforge.net/">http://adodb.sourceforge.net/</A>
*/

DEFINE('_ERRSQLDBTYPE','Type de base de donn�es incorrect');
// Invalid Database Type Specified
DEFINE('_ERRSQLDBTYPEINFO1','La variable <CODE>\$DBtype</CODE> dans <CODE>base_conf.php</CODE> sp�cifie un type non reconnu : ');
/*
	'The variable <CODE>\$DBtype</CODE> in <CODE>base_conf.php</CODE> was set to the unrecognized 	database type of 
*/
DEFINE('_ERRSQLDBTYPEINFO2','Seuls les types de base de donn�es suivants sont support�s : <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');
/*'Only the following databases are supported: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
             </PRE>
*/
//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','ERREUR FATALE DE LA BASE');
// BASE FATAL ERROR:

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Charg� en');
// Loaded in
DEFINE('_SECONDS','seconde(s)');
// seconds

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Impossible de r�soudre l\'adresse');
// Unable to resolve address

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Ent�te des r �sultats de la requ�te');
// Query Results Output Header

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','SigName inconnu');
// SigName unknown
DEFINE('_ERRSIGPROIRITYUNK','SigPriority inconnue');
// SigPriority unknown
DEFINE('_UNCLASS','non class�');
// unclassified

//base_state_citems.inc.php
DEFINE('_DENCODED','donn�es encod�es en ');
// data encoded as
DEFINE('_NODENCODED','aucune conversion des donn�es, pr�somption d\'encodage des crit�res compatible avec la base de donn�es');
// (no data conversion, assuming criteria in DB native encoding)

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','ERREUR PHP: Une session PHP custom (user) a �t� d�tect�e. 
Cependant, BASE n\'a pas �t� explicitement configur� pour utiliser ce custom handler.  Fixer <CODE>use_user_session=1</CODE> dans <CODE>base_conf.php</CODE>');
/*
'PHP ERROR: A custom (user) PHP session have been detected. However, BASE has not been set to explicitly use this custom handler.  Set <CODE>use_user_session=1</CODE> in <CODE>base_conf.php</CODE>
*/
DEFINE('_PHPERRORCSESSIONCODE','ERREUR PHP: Une session PHP custom (user) a �t� configur�e, mais le code handler fourni dans <CODE>user_session_path</CODE> n\'est pas valable.');
/*'PHP ERROR: A custom (user) PHP session hander has been configured, but the supplied hander code specified in <CODE>user_session_path</CODE> is invalid.
*/
DEFINE('_PHPERRORCSESSIONVAR','ERREUR PHP: Une session PHP custom (user) a �t� configur�e, mais l\'implementation de ce handler n\'a pas �t� pr�cis� dans BASE. Si un custom session handler est souhait�, fixer la variable <CODE>user_session_path</CODE> dans <CODE>base_conf.php</CODE>.');
/*
'PHP ERROR: A custom (user) PHP session handler has been configured, but the implementation of this handler has not been specified in BASE.  If a custom session handler is desired, set the <CODE>user_session_path</CODE> variable in <CODE>base_conf.php</CODE>.
*/

DEFINE('_PHPSESSREG','Session Enregistr�e');
// Session Registered

//base_state_criteria.inc.php
DEFINE('_REMOVE','Suppression');
// Removing
DEFINE('_FROMCRIT','des crit�res');
// from criteria
DEFINE('_ERRCRITELEM','El�ment de crit�re non valide');
// Invalid criteria element

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Liste des requ�tes pr�d�finies valides');
// Valid Canned Query List
DEFINE('_DISPLAYING','Affichage');
// Displaying
DEFINE('_DISPLAYINGTOTAL','Affichage des alertes %d-%d sur %d au total');
// Displaying alerts %d-%d of %d total
DEFINE('_NOALERTS','Aucune Alerte trouv�e.');
// No Alerts were found.
DEFINE('_QUERYRESULTS','R�sultats de la requ�te');
// Query Results
DEFINE('_QUERYSTATE','Etat de la requ�te');
// Query State

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','La recherche par nom de Groupe d\'Alertes n\'est pas valide.  Essayez � nouveau!');
// The specified AG name search is invalid.  Try again!
DEFINE('_ERRAGNAMEEXIST','Le Groupe d\'Alertes sp�cifi� n\'existe pas.');
// The specified AG does not exist.
DEFINE('_ERRAGIDSEARCH','La recherche par num�ro de Groupe d\'Alerte n\'est pas valide.  Essayez � nouveau!');
// The specified AG ID search is invalid.  Try again!
DEFINE('_ERRAGLOOKUP','Erreur lors de la recherche de l\'identifiant (ID) d\'un Groupe d\'Alertes');
// Error looking up an AG ID
DEFINE('_ERRAGINSERT','Erreur lors de d\'insertion du nouveau Groupe d\'Alertes');
// Error Inserting new AG

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Maintenance des Groupes d\'Alertes');
// Alert Group (AG) Maintenance
DEFINE('_ERRAGUPDATE','Erreur de mise � jour du Groupe d\'Alertes');
// Error updating the AG
DEFINE('_ERRAGPACKETLIST','Erreur lors de la suppression de la liste des paquets du Groupe d\'Alertes :');
// Error deleting packet list for the AG:
DEFINE('_ERRAGDELETE','Erreur lors de la suppression du Groupe d\'Alertes');
// Error deleting the AG
DEFINE('_AGDELETE','Suppression r�ussie');
// DELETED successfully
DEFINE('_AGDELETEINFO','information supprim�e');
// information deleted
DEFINE('_ERRAGSEARCHINV','Le crit�re de recherche n\'est pas valide. Essayez � nouveau!');
// The entered search criteria is invalid.  Try again!
DEFINE('_ERRAGSEARCHNOTFOUND','Aucun Groupe d\'Alertes correspondant � ce crit�re n\'a �t� trouv�');
// No AG found with that criteria.
DEFINE('_NOALERTGOUPS','Il n\'y a pas de Groupes d\'Alertes');
// There are no Alert Groups
DEFINE('_NUMALERTS','# Alertes');
// # Alerts
DEFINE('_ACTIONS','Actions');
// Actions
DEFINE('_NOTASSIGN','non affect� ');
// not assigned yet

//base_common.php
DEFINE('_PORTSCAN','Scans de Port');
// Portscan Traffic

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Echec de cr�ation d\'index pour');
// Unable to CREATE INDEX for
DEFINE('_DBINDEXCREATE','Cr�ation d\'index r�ussie pour');
// Successfully created INDEX for
DEFINE('_ERRSNORTVER','Il s\'agit peut �tre d\'une version plus ancienne. Seules les bases d\'alertes cr��es par Snort 1.7-beta0 ou ult�rieur sont support�es');
// It might be an older version.  Only alert databases created by Snort 1.7-beta0 or later are supported
DEFINE('_ERRSNORTVER1','La base de donn�es sousjacente');
// The underlying database
DEFINE('_ERRSNORTVER2','semble �tre incompl�te/invalide');
// appears to be incomplete/invalid
DEFINE('_ERRDBSTRUCT1','La version de la base de donn�es est valide, mais la structure db de BASE ');
// The database version is valid, but the BASE DB structure

DEFINE('_ERRDBSTRUCT2','n\'est pas pr�sente. Utilisez la <A HREF="base_db_setup.php">Setup page</A> pour configurer et optimiser la DB.');
/*
'is not present. Use the <A HREF="base_db_setup.php">Setup page</A> to configure and optimize the DB. 
*/
DEFINE('_ERRPHPERROR','ERREUR PHP');
// PHP ERROR
DEFINE('_ERRPHPERROR1','Version incompatible');
// Incompatible version
DEFINE('_ERRVERSION','Version');
// Version
DEFINE('_ERRPHPERROR2','de PHP est trop ancienne. Utiliser la version 4.0.4 ou ult�rieure');
// of PHP is too old.  Please upgrade to version 4.0.4 or later
DEFINE('_ERRPHPMYSQLSUP','<B>PHP build incomplet</B>: <FONT>le support MySQL requis pour acc�der � la base de donn�es des alertes est absent de PHP.  
               Recompiler PHP avec la biblioth�que requise (<CODE>--with-mysql</CODE>) SVP</FONT> ');
/*
'<B>PHP build incomplete</B>: <FONT>the prerequisite MySQL support required to 
               read the alert database was not built into PHP.  
               Please recompile PHP with the necessary library (<CODE>--with-mysql</CODE>)</FONT>
*/
DEFINE('_ERRPHPPOSTGRESSUP','<B>PHP build incomplet</B>: <FONT>le support PostgreSQL requis pour acc�der � la base de donn�es des alertes est absent de PHP.  
               Recompiler PHP avec la biblioth�que requise (<CODE>--with-pgsql</CODE>) SVP</FONT> ');
/*
'<B>PHP build incomplete</B>: <FONT>the prerequisite PostgreSQL support required to 
               read the alert database was not built into PHP.  
               Please recompile PHP with the necessary library (<CODE>--with-pgsql</CODE>)</FONT>
*/

DEFINE('_ERRPHPMSSQLSUP','<B>PHP build incomplet</B>: <FONT>le support MS SQL Server requis pour acc�der � la base de donn�es des alertes est absent de PHP.  
               Recompiler PHP avec la biblioth�que requise (<CODE>--enable-mssql</CODE>) SVP</FONT>');
/*
'<B>PHP build incomplete</B>: <FONT>the prerequisite MS SQL Server support required to 
                   read the alert database was not built into PHP.  
                   Please recompile PHP with the necessary library (<CODE>--enable-mssql</CODE>)</FONT>
*/
DEFINE('_ERRPHPORACLESUP','<B>PHP build incomplete</B>: <FONT>the prerequisite Oracle support required to 
                   read the alert database was not built into PHP.  
                   Please recompile PHP with the necessary library (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Titre du Graphique :');
// Chart Title:
DEFINE('_CHRTTYPEHOUR','Date (heure) vs. Nombre d\'Alertes');
// Time (hour) vs. Number of Alerts
DEFINE('_CHRTTYPEDAY','Date (jour) vs. Nombre d\'Alertes');
// Time (day) vs. Number of Alerts
DEFINE('_CHRTTYPEWEEK','Date (semaine) vs. Nombre d\'Alertes');
// Time (week) vs. Number of Alerts
DEFINE('_CHRTTYPEMONTH','Date (mois) vs. Nombre d\'Alertes');
// Time (month) vs. Number of Alerts
DEFINE('_CHRTTYPEYEAR','Date (ann�e) vs. Nombre d\'Alertes');
// Time (year) vs. Number of Alerts
DEFINE('_CHRTTYPESRCIP','Adresse IP Src. vs. Nombre d\'Alertes');
// Src. IP address vs. Number of Alerts
DEFINE('_CHRTTYPEDSTIP','Adresse IP Dst. vs. Nombre d\'Alertes');
// Dst. IP address vs. Number of Alerts
DEFINE('_CHRTTYPEDSTUDP','Port UDP de Dst. vs. Nombre d\'Alertes');
// Dst. UDP Port vs. Number of Alerts
DEFINE('_CHRTTYPESRCUDP','Port UDP Src. vs. Nombre d\'Alertes');
// Src. UDP Port vs. Number of Alerts
DEFINE('_CHRTTYPEDSTPORT','Port TCP de Dst. vs. Nombre d\'Alertes');
// Dst. TCP Port vs. Number of Alerts
DEFINE('_CHRTTYPESRCPORT','Port TCP Src. vs. Nombre d\'Alertes');
// Src. TCP Port vs. Number of Alerts
DEFINE('_CHRTTYPESIG','Classif. Signif. vs. Nombre d\'Alertes');
// Sig. Classification vs. Number of Alerts
DEFINE('_CHRTTYPESENSOR','Sonde vs. Nombre d\'Alertes');
// Sensor vs. Number of Alerts
DEFINE('_CHRTBEGIN','D�but du graphique :');
// Chart Begin:
DEFINE('_CHRTEND','Fin du graphique :');
// Chart End:
DEFINE('_CHRTDS','Source des donn�es :');
// Data Source:
DEFINE('_CHRTX','Abscisse (Axe X)');
// X Axis
DEFINE('_CHRTY','Ordonn�e (Axe Y)');
// Y Axis
DEFINE('_CHRTMINTRESH','Seuil minimal');
// Minimum Threshold Value
DEFINE('_CHRTROTAXISLABEL','Rotation des libell�s (90 degr�s)');
// Rotate Axis Labels (90 degrees)
DEFINE('_CHRTSHOWX','Affichage du quadrillage vertical');
// Show X-axis grid-lines
DEFINE('_CHRTDISPLABELX','Afficher le libell� d\'abscisse toutes les ');
// Display X-axis label every
DEFINE('_CHRTDATAPOINTS','unit�s');
// data points
DEFINE('_CHRTYLOG','Utiliser une �chelle logarithmique en ordonn�e');
// Y-axis logarithmic
DEFINE('_CHRTYGRID',' Affichage le quadrillage horizontal');
// Show Y-axis grid-lines

//base_graph_main.php
DEFINE('_CHRTTITLE','Graphique BASE');
// BASE Chart
DEFINE('_ERRCHRTNOTYPE','Aucun type de graphique s�l�ctionn�');
// No chart type was specified
DEFINE('_ERRNOAGSPEC','Aucun Groupe d\'Alertes pr�cis�. Toutes les alertes sont prises en compte.');
// No AG was specified.  Using all alerts.
DEFINE('_CHRTDATAIMPORT','D�but de l\'importation des donn�es');
// Starting data import
DEFINE('_CHRTTIMEVNUMBER','Date vs. Nombre d\'Alertes');
// Time vs. Number of Alerts
DEFINE('_CHRTTIME','Date');
// Time
DEFINE('_CHRTALERTOCCUR','Occurrences');
// Alert Occurrences
DEFINE('_CHRTSIPNUMBER','IP Source vs. Nombre d Alertes');
// Source IP vs. Number of Alerts
DEFINE('_CHRTSIP','Adresse IP Source ');
// Source IP Address
DEFINE('_CHRTDIPALERTS','IP Destination vs. Nombre d Alertes');
// Destination IP vs. Number of Alerts
DEFINE('_CHRTDIP','Adresse IP de Destination');
// Destination IP Address
DEFINE('_CHRTUDPPORTNUMBER','Port UDP (Destination) vs. Nombre d Alertes');
// UDP Port (Destination) vs. Number of Alerts
DEFINE('_CHRTDUDPPORT','Port UDP Dst.');
// Dst. UDP Port
DEFINE('_CHRTSUDPPORTNUMBER','Port UDP (Source) vs. Nombre d Alertes');
// UDP Port (Source) vs. Number of Alerts
DEFINE('_CHRTSUDPPORT','Port UDP Src.');
// Src. UDP Port
DEFINE('_CHRTPORTDESTNUMBER','Port TCP (Destination) vs. Nombre d Alertes');
// TCP Port (Destination) vs. Number of Alerts
DEFINE('_CHRTPORTDEST','Port TCP Dst.');
// Dst. TCP Port
DEFINE('_CHRTPORTSRCNUMBER','Port TCP (Source) vs. Nombre d Alertes');
// TCP Port (Source) vs. Number of Alerts
DEFINE('_CHRTPORTSRC','Port TCP Src.');
// Src. TCP Port
DEFINE('_CHRTSIGNUMBER','Classification de Signature vs. Nombre d Alertes');
// Signature Classification vs. Number of Alerts
DEFINE('_CHRTCLASS','Classification');
// Classification
DEFINE('_CHRTSENSORNUMBER','Sonde vs. Nombre d Alertes');
// Sensor vs. Number of Alerts
DEFINE('_CHRTHANDLEPERIOD','Traitement de la p�riode si n�cessaire');
// Handling Period if necessary
DEFINE('_CHRTDUMP','Exportation des donn�es ... (Ecriture seulement toutes les ');
// Dumping data ... (writing only every
DEFINE('_CHRTDRAW','Cr�ation du graphique en cours');
// Drawing graph
DEFINE('_ERRCHRTNODATAPOINTS','Pas de donn�es � afficher');
// No data points to plot

//base_maintenance.php
DEFINE('_MAINTTITLE','Maintenance');
// Maintenance
DEFINE('_MNTPHP','Build PHP :');
// PHP Build:
DEFINE('_MNTCLIENT','CLIENT :');
// CLIENT:
DEFINE('_MNTSERVER','SERVEUR :');
// SERVER:
DEFINE('_MNTSERVERHW','MATERIEL SERVEUR :');
// SERVER HW:
DEFINE('_MNTPHPVER','VERSION PHP :');
// PHP VERSION:
DEFINE('_MNTPHPAPI','API PHP :');
// PHP API:
DEFINE('_MNTPHPLOGLVL','PHP Logging level :');
// PHP Logging level:
DEFINE('_MNTPHPMODS','Modules charg�s :');
// Loaded Modules:
DEFINE('_MNTDBTYPE','Type de base de donn�es :');
// DB Type:
DEFINE('_MNTDBALV','Version d\'Abstraction Base de Donn�es :');
// DB Abstraction Version:
DEFINE('_MNTDBALERTNAME','Nom de la base ALERTES :');
// ALERT DB Name:
DEFINE('_MNTDBARCHNAME','Nom de la base ARCHIVE :');
// ARCHIVE DB Name:
DEFINE('_MNTAIC','Cache des informations Alertes :');
// Alert Information Cache:
DEFINE('_MNTAICTE','Nombre total d\'Ev�nements :');
// Total Events:
DEFINE('_MNTAICCE','Nombre d\'Ev�nements en cache :');
// Cached Events:
DEFINE('_MNTIPAC','Cache d\'Adresses IP');
// IP Address Cache
DEFINE('_MNTIPACUSIP','IP Src Unique');
// Unique Src IP:
DEFINE('_MNTIPACDNSC','Cache DNS :');
// DNS Cached:
DEFINE('_MNTIPACWC','Cache Whois :');
// Whois Cached:
DEFINE('_MNTIPACUDIP','IP Dst Unique :');
// Unique Dst IP:

//base_qry_alert.php
DEFINE('_QAINVPAIR','Paire (sid,cid) non valide');
// Invalid (sid,cid) pair
DEFINE('_QAALERTDELET','Alerte SUPPRIMEE ');
// Alert DELETED
DEFINE('_QATRIGGERSIG','Signature D�clench�e');
// Triggered Signature

//base_qry_common.php
DEFINE('_QCSIG','signature');
// signature
DEFINE('_QCIPADDR','adresses IP');
// IP addresses
DEFINE('_QCIPFIELDS','champs IP');
// IP fields
DEFINE('_QCTCPPORTS','Ports TCP ');
// TCP ports
DEFINE('_QCTCPFLAGS','Indicateurs (flags) TCP');
// TCP flags
DEFINE('_QCTCPFIELD','champs TCP');
// TCP fields
DEFINE('_QCUDPPORTS','ports UDP');
// UDP ports
DEFINE('_QCUDPFIELDS','champs UDP');
// UDP fields
DEFINE('_QCICMPFIELDS','champs ICMP');
// ICMP fields
DEFINE('_QCDATA','Donn�es');
// Data
DEFINE('_QCERRCRITWARN','Avertissement concernant les crit�res :');
// Criteria warning:
DEFINE('_QCERRVALUE','La valeur');
// A value of
DEFINE('_QCERRFIELD','Le champ');
// A field of
DEFINE('_QCERROPER','L\'op�rateur');
// An operator of
DEFINE('_QCERRDATETIME','La date ou l\'heure');
// A date/time value of
DEFINE('_QCERRPAYLOAD','Le contenu (payload)');
// A payload value of
DEFINE('_QCERRIP','L\'adresse IP');
// An IP address of
DEFINE('_QCERRIPTYPE','Une adresse IP de type');
// An IP address of type
DEFINE('_QCERRSPECFIELD','�tait saisi pour un champ de protocole, mais le champ pr�cis n\'est pas sp�cifi�.');
//  was entered for a protocol field, but the particular field was not specified.
DEFINE('_QCERRSPECVALUE','�tait choisi comme crit�re, mais aucune valeur n\'est sp�cifi�e.');
// was selected indicating that it should be a criteria, but no value was specified on which to match.
DEFINE('_QCERRBOOLEAN','Crit�res multiples de protocole saisis sans op�rateur(s) logiques (AND, OR) entre eux.');
// Multiple protocol field criteria entered without a boolean operator (e.g. AND, OR) between them.
DEFINE('_QCERRDATEVALUE','�tait s�l�ctionn� ce qui indique que des crit�res date/heure doivent s\'appliquer, mais aucune valeur n\'�tait pr�cis�e.');
// was selected indicating that some date/time criteria should be matched, but no value was specified.
DEFINE('_QCERRINVHOUR','(Heure non valide) Aucun crit�re date saisi avec l\'heure specifi�e.');
// (Invalid Hour) No date criteria were entered with the specified time.
DEFINE('_QCERRDATECRIT','�tait s�l�ctionn� ce qui indique que des crit�res date/heure doivent s\'appliquer, mais aucune valeur n\'�tait pr�cis�e.');
// was selected indicating that some date/time criteria should be matched, but no value was specified.
DEFINE('_QCERROPERSELECT','�tait saisi mais aucun op�rateur n\'a �t� choisi.');
// was entered but no operator was selected.
DEFINE('_QCERRDATEBOOL','Crit�res Date/Heure multiples sans op�rateur(s) logiques (AND, OR) entre eux ');
// Multiple Date/Time criteria entered without a boolean operator (e.g. AND, OR) between them.
DEFINE('_QCERRPAYCRITOPER','�tait saisi comme filtre sur le contenu, mais un op�rateur tel "has","has not" n\' pas �t� pr�cis�.');
// was entered for a payload criteria field, but an operator (e.g. has, has not) was not specified.
DEFINE('_QCERRPAYCRITVALUE','�tait s�l�ctionn� ce qui indique que des crit�res doivent s\'appliquer au contenu (payload), mais aucune valeur n\'�tait pr�cis�e.  ');
// was selected indicating that payload should be a criteria, but no value on which to match was specified.
DEFINE('_QCERRPAYBOOL','De multiples crit�res de contenu (payload) saisis sans op�rateur logique (AND, OR) entre eux.');
// Multiple Data payload criteria entered without a boolean operator (e.g. AND, OR) between them.
DEFINE('_QCMETACRIT','Meta crit�res');
// Meta Criteria
DEFINE('_QCIPCRIT','Crit�res IP');
// IP Criteria
DEFINE('_QCPAYCRIT','Crit�res de contenu (payload)');
// Payload Criteria
DEFINE('_QCTCPCRIT','Crit�res TCP');
// TCP Criteria
DEFINE('_QCUDPCRIT','Crit�res UDP');
// UDP Criteria
DEFINE('_QCICMPCRIT','Crit�res ICMP');
// ICMP Criteria
DEFINE('_QCERRINVIPCRIT','Crit�re d\'adresse IP non valide');
// Invalid IP address criteria
DEFINE('_QCERRCRITADDRESSTYPE','a �t� saisi en tant que valeur de crit�re, mais le type d\'adresse (source, destination) n\'�tait pas pr�cis�.');
// was entered for as a criteria value, but the type of address (e.g. source, destination) was not specified.
DEFINE('_QCERRCRITIPADDRESSNONE','ce qui suppose un crit�re sur l\'adresse IP, mais aucune adresse IP �   ');
// indicating that an IP address should be a criteria, but no address on which to match was specified.
DEFINE('_QCERRCRITIPADDRESSNONE1','�tait s�l�ctionn� (� #');
// was selected (at #
DEFINE('_QCERRCRITIPIPBOOL','Multiples crit�res d\'adresse IP saisis sans op�rateur logique (AND, OR) entre eux.');
// Multiple IP address criteria entered without a boolean operator (e.g. AND, OR) between IP Criteria

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Ordre de tri');
// Sort order
DEFINE('_QFRMTIMEA','horodatage (ascendant)');
// timestamp (ascend)
DEFINE('_QFRMTIMED','horodatage (descendant)');
// timestamp (descend)
DEFINE('_QFRMSIG','signature');
// signature
DEFINE('_QFRMSIP','IP source ');
// source IP
DEFINE('_QFRMDIP','IP de destination');
// dest. IP

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Statistiques');
// Summary Statistics
DEFINE('_QSCTIMEPROF','R�partition temporelle');
// Time profile
DEFINE('_QSCOFALERTS','des alertes');
// of alerts

//base_stat_alerts.php
DEFINE('_ALERTTITLE','Liste des Alertes');
// Alert Listing

//base_stat_common.php
DEFINE('_SCCATEGORIES','Cat�gories : ');
// Categories:
DEFINE('_SCSENSORTOTAL','Sondes / Total : ');
// Sensors/Total:
DEFINE('_SCTOTALNUMALERTS','Nombre Total d\'Alertes : ');
// Total Number of Alerts:
DEFINE('_SCSRCIP','Adresse(s) IP Source : ');
// Src IP addrs:
DEFINE('_SCDSTIP','Adresse(s) IP Destination : ');
// Dest. IP addrs
DEFINE('_SCUNILINKS','Liens IP Uniques : ');
// Unique IP links
DEFINE('_SCSRCPORTS','Ports Source : ');
// Source Ports: 
DEFINE('_SCDSTPORTS','Ports de Destination : ');
// Dest Ports: 
DEFINE('_SCSENSORS','Sondes');
// Sensors
DEFINE('_SCCLASS','Classifications');
// classifications
DEFINE('_SCUNIADDRESS','Adresses uniques : ');
// Unique addresses: 
DEFINE('_SCSOURCE','Source');
// Source
DEFINE('_SCDEST','Destination');
// Destination
DEFINE('_SCPORT','Port');
// Port

//base_stat_ipaddr.php
DEFINE('_PSEVENTERR','ERREUR D\'EVENEMENT PORTSCAN');
// PORTSCAN EVENT ERROR: 
DEFINE('_PSEVENTERRNOFILE','Aucun fichier pr�cis� dans la variable $portscan_file.');
// No file was specified in the $portscan_file variable.
DEFINE('_PSEVENTERROPENFILE','Impossible d\ouvrir le fichier des �v�nements Portscan.');
// Unable to open Portscan event file
DEFINE('_PSDATETIME','Date/Heure');
// Date/Time
DEFINE('_PSSRCIP','IP Source');
// Source IP
DEFINE('_PSDSTIP','IP de Destination');
// Destination IP
DEFINE('_PSSRCPORT','Port Source');
// Source Port
DEFINE('_PSDSTPORT','Port de Destination');
// Destination Port
DEFINE('_PSTCPFLAGS','Indicateurs (flags) TCP');
// TCP Flags
DEFINE('_PSTOTALOCC','Total<BR> Occurrences');
// Total<BR> Occurrences
DEFINE('_PSNUMSENSORS','Nombre de sondes');
// Num of Sensors
DEFINE('_PSFIRSTOCC','Premi�re<br>occurrence');
// First<BR> Occurrence
DEFINE('_PSLASTOCC','Derni�re<br>occurrence');
// Last<BR> Occurrence
DEFINE('_PSUNIALERTS','Alertes uniques');
// Unique Alerts
DEFINE('_PSPORTSCANEVE','Ev�nements portscan');
// Portscan Events
DEFINE('_PSREGWHOIS','Recherche d\inscription (whois) dans');
// Registry lookup (whois) in
DEFINE('_PSNODNS','aucune r�solution DNS tent�e');
// no DNS resolution attempted
DEFINE('_PSNUMSENSORSBR','Nombre de <br>sondes');
// Num of <BR>Sensors
DEFINE('_PSOCCASSRC','Occurrences<br>en Src. ');
// Occurances <BR>as Src.
DEFINE('_PSOCCASDST','Occurrences<br>en Dest.');
// Occurances <BR>as Dest.
DEFINE('_PSWHOISINFO','Informations d\'inscription (Whois)');
// Whois Information

//base_stat_iplink.php
DEFINE('_SIPLTITLE','Liens IP');
// IP Links
DEFINE('_SIPLSOURCEFGDN','FQDN Source');
// Source FQDN
DEFINE('_SIPLDESTFGDN','FQDN de Destination ');
// Destination FQDN
DEFINE('_SIPLDIRECTION','Direction');
// Direction
DEFINE('_SIPLPROTO','Protocole');
// Protocol
DEFINE('_SIPLUNIDSTPORTS','Ports Dst Uniques');
// Unique Dst Ports
DEFINE('_SIPLUNIEVENTS','Ev�nements uniques');
// Unique Events
DEFINE('_SIPLTOTALEVENTS','Nombre total d\'ev�nements');
// Total Events

//base_stat_ports.php
DEFINE('_UNIQ','Unique');
// Unique
DEFINE('_DSTPS','Port(s) de Destination');
// Destination Port(s)
DEFINE('_SRCPS','Port(s) Source');
// Source Port(s)

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Liste des sondes');
// Sensor Listing

//base_stat_time.php
DEFINE('_BSTTITLE','Profile temporel des Alertes');
// Time Profile of Alerts
DEFINE('_BSTTIMECRIT','Crit�re temporel');
// Time Criteria
DEFINE('_BSTERRPROFILECRIT','<font><b>Crit�res manquants!</b> Choisir "heure", "jour", ou "mois" pour d�finir la granularit� des statistiques consolid�es</font>');
/*'<FONT><B>No profiling criteria was specified!</B>  Click on "hour", "day", or "month" to choose the granularity of the aggregate statistics.</FONT>
*/
DEFINE('_BSTERRTIMETYPE','<font><b>Le type de param�tre temporel � appliquer n\'�tait pas sp�cifi�!</b> Choisir soit "on", pour une seule date, soit "between" pour sp�cifier un intervalle. ');
/*'<FONT><B>The type of time parameter which will be passed was not specified!</B>  Choose either "on", to specify a single date, or "between" to specify an interval.</FONT>
*/
DEFINE('_BSTERRNOYEAR','Aucun param�tre d\'Ann�e pr�cis�!');
// <FONT><B>No Year parameter was specified!</B></FONT>
DEFINE('_BSTERRNOMONTH','Aucun param�tre de Mois pr�cis�!');
// <FONT><B>No Month parameter was specified!</B></FONT>
DEFINE('_BSTERRNODAY','Aucun param�tre de Jour pr�cis�! ');
// <FONT><B>No Day parameter was specified!</B></FONT>

//base_stat_uaddr.php
DEFINE('_UNISADD','Adresse(s) Source Unique(s)');
// Unique Source Address(es)
DEFINE('_SUASRCIP','Adresse IP Src');
// Src IP address
DEFINE('_SUAERRCRITADDUNK','ERREUR DE CRITERE: type d\'adresse inconnu -- suppose adresse Dst.');
// CRITERIA ERROR: unknown address type -- assuming Dst address
DEFINE('_UNIDADD','Adresse(s) de Destination Unique(s) ');
// Unique Destination Address(es)
DEFINE('_SUADSTIP','adresse IP Dst');
// Dst IP address
DEFINE('_SUAUNIALERTS','Alertes&nbsp;Uniques');
// Unique&nbsp;Alerts
DEFINE('_SUASRCADD','Adresse&nbsp;Src.');
// Src.&nbsp;Addr.
DEFINE('_SUADSTADD','Adresse&nbsp;Dest.');
// Dest.&nbsp;Addr.

//base_user.php
DEFINE('_BASEUSERTITLE','Pr�f�rence Utilisateur BASE ');
// BASE User preferences
DEFINE('_BASEUSERERRPWD','Votre mot de passe ne peut pas �tre nul ou les deux mots de passe n\'�tait pas identiques!');
// Your password can not be blank or the two passwords did not match!
DEFINE('_BASEUSEROLDPWD','Ancien mot de passe :');
// Old Password:
DEFINE('_BASEUSERNEWPWD','Nouveau mot de passe :');
// New Password:
DEFINE('_BASEUSERNEWPWDAGAIN','Confirmer le mot de passe :');
// New Password Again:

//New stuff:
DEFINE('_SENSORS','Sensors'); //NEW
DEFINE('_FIRST','First'); //NEW
DEFINE('_TOTAL','Total'); //NEW
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
DEFINE('_SOURCEIP','Source IP'); //NEW
DEFINE('_DESTIP','Destination IP'); //NEW
DEFINE('_USEALERTDB','Use Alert Database'); //NEW
DEFINE('_USEARCHIDB','Use Archive Database'); //NEW
DEFINE('_TRAFFICPROBPRO','Traffic Profile by Protocol'); //NEW
DEFINE('_UPDATEROLE','Update Role'); //NEW
DEFINE('_SUBMITQUERY','Submit Query'); //NEW
DEFINE('_UPDATEUSER','Update User'); //NEW
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
DEFINE('_DISPACTION','{ action }'); //NEW
DEFINE('_SAVECHANGES','Save Changes'); //NEW
DEFINE('_CONFIRMDELETE','Confirm Delete'); //NEW
DEFINE('_CONFIRMCLEAR','Confirm Clear'); //NEW
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
DEFINE('_CHARTHOUR','{hour}'); //NEW
DEFINE('_CHARTDAY','{day}'); //NEW
DEFINE('_CHARTMONTH','{month}'); //NEW
DEFINE('_GRAPHALERTS','Graph Alerts'); //NEW
DEFINE('_AXISCONTROLS','X / Y AXIS CONTROLS'); //NEW
DEFINE('_GRAPHALERTDATA','Graph Alert Data'); //NEW
DEFINE('_QCLAYER4CRIT','Layer 4 Criteria'); //NEW
DEFINE('_QFRMSORTNONE','none'); //NEW
DEFINE('_PSTOTALHOSTS','Total Hosts Scanned'); //NEW
DEFINE('_PSDETECTAMONG','%d unique alerts detected among %d alerts on %s'); //NEW
DEFINE('_PSALLALERTSAS','all alerts with %s/%s as'); //NEW
DEFINE('_PSSHOW','show'); //NEW
DEFINE('_PSEXTERNAL','external'); //NEW
DEFINE('_OCCURRENCES','Occurrences'); //NEW
DEFINE('_BSTPROFILEBY','Profile by'); //NEW
DEFINE('_TIMEON','on'); //NEW
DEFINE('_TIMEBETWEEN','between'); //NEW
DEFINE('_PROFILEALERT','Profile Alert'); //NEW
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
DEFINE('_QANORMALD','Normal Display'); //NEW
DEFINE('_QAPLAIND','Plain Display'); //NEW
DEFINE('_QANOPAYLOAD','Fast logging used so payload was discarded'); //NEW

?>
