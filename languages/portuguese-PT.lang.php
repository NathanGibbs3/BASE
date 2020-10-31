<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (veja o arquivo 'base_main.php' para detalhes de licença)
**
** Lideranças do Projeto: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Feito a partir do trabalho de Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Propósito: Arquivo da linguagem Português de Portugal  
**      Para traduzir para outra linguagem, copie este arquivo e
**          traduza cada variável para a linguagem escolhida.
**          Deixe qualquer variável não traduzida como está para que o
**          sistema tenha algo para mostrar.
********************************************************************************
** Autores:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
** Joel Esler <joelesler@users.sourceforge.net>
** Traduzido por: João Ricardo Marques Mota
**                iceburn_tuga@users.sourceforge.net
** Baseada na tradução de: Pedro Henrique Nogueira Walter
**                thehardware@users.sourceforge.net
** Corrigida e finalizada por: Thiago Martins
**                tmartins@users.sourceforge.net
** 31/01/2005 - Correção dos códigos unicode que não estão sendo exibidos corretamente
** 01/02/2005 - Finalização dos ítens não traduzidos
********************************************************************************
*/

$UI_Spacing = 1; // Inter Character Spacing.
$UI_ILC = 'pt'; // ISO 639-1 Language Code.
$UI_IRC = 'PT'; // Region Code.
// Locale.
$UI_Locales = array( 'pt_PT.ISO8859-1', 'pt_PT.utf-8', 'portuguese' );
// Time Format - See strftime() syntax.
$UI_Timefmt = '%a %d de %b de %Y %H:%M:%S';
// UI Init.
$UI_Charset = 'UTF-8';
$UI_Title = 'Basic Analysis and Security Engine';
// Common Words.
$UI_CW_Edit = 'Editar';
$UI_CW_Delete = 'Apagar';
$UI_CW_Src = 'Origem';
$UI_CW_Dst = 'Destino';
$UI_CW_Id = 'ID';
$UI_CW_Name = 'Nome';
$UI_CW_Int = 'Interface';
$UI_CW_Filter = 'Filtro';
$UI_CW_Desc = 'Descrição';
$UI_CW_SucDesc = 'Com Sucesso';
$UI_CW_Sensor = 'Sensor';
$UI_CW_Sig = 'Assinatura';
$UI_CW_Role = 'perfil';
$UI_CW_Pw = 'Senha';
$UI_CW_Ts = 'Data';
$UI_CW_Addr = 'Endereço';
$UI_CW_Layer = 'Camada';
$UI_CW_Proto = 'Protocol';
$UI_CW_Pri = 'Prioridade';
$UI_CW_Event = 'Evento';
$UI_CW_Type = 'Tipo';
$UI_CW_ML1 = 'Janeiro';
$UI_CW_ML2 = 'Fevereiro';
$UI_CW_ML3 = 'Março';
$UI_CW_ML4 = 'Abril';
$UI_CW_ML5 = 'Maio';
$UI_CW_ML6 = 'Junho';
$UI_CW_ML7 = 'Julho';
$UI_CW_ML8 = 'Agosto';
$UI_CW_ML9 = 'Setembro';
$UI_CW_ML10 = 'Outubro';
$UI_CW_ML11 = 'Novembro';
$UI_CW_ML12 = 'Dezembro';
$UI_CW_Last = 'Último';
$UI_CW_First = 'Primeiro';
$UI_CW_Total = 'Total';
$UI_CW_Alert = 'Alerta';
// Common Phrases.
$UI_CP_SrcName = array($UI_CW_Name,'da',$UI_CW_Src);
$UI_CP_DstName = array($UI_CW_Name,'do',$UI_CW_Dst);
$UI_CP_SrcDst = array($UI_CW_Src,'ou',$UI_CW_Dst);
$UI_CP_SrcAddr = array($UI_CW_Addr,'de',$UI_CW_Src);
$UI_CP_DstAddr = array($UI_CW_Addr,'de',$UI_CW_Dst);
$UI_CP_L4P = array($UI_CW_Proto,$UI_CW_Layer,'4');
$UI_CP_ET = array($UI_CW_Type,'de',$UI_CW_Event);
// Authentication Data.
$UI_AD_UND = $UI_CW_Name;
$UI_AD_RID = array($UI_CW_Id,'do',$UI_CW_Role);
$UI_AD_ASD = 'Habilitado';

//common phrases
DEFINE('_ADDRESS','Endereço');
DEFINE('_UNKNOWN','Desconhecido');
DEFINE('_AND','E');
DEFINE('_OR','OU');
DEFINE('_IS','é');
DEFINE('_ON','em');
DEFINE('_IN','em');
DEFINE('_ANY','qualquer');
DEFINE('_NONE','nenhum');
DEFINE('_HOUR','Hora');
DEFINE('_DAY','Dia');
DEFINE('_MONTH','Mês');
DEFINE('_YEAR','Ano');
DEFINE('_ALERTGROUP','Grupo de '.$UI_CW_Alert.'s');
DEFINE('_ALERTTIME','Hora dos '.$UI_CW_Alert.'s');
DEFINE('_CONTAINS','contém');
DEFINE('_DOESNTCONTAIN','não contem');
DEFINE('_SOURCEPORT','porta origem');
DEFINE('_DESTPORT','porta destino');
DEFINE('_HAS','tem');
DEFINE('_HASNOT','não tem');
DEFINE('_PORT','Porta');
DEFINE('_FLAGS','Flags');
DEFINE('_MISC','Misc');
DEFINE('_BACK','Voltar');
DEFINE('_DISPYEAR','{ ano }');
DEFINE('_DISPMONTH','{ mês }');
DEFINE('_DISPHOUR','{ hora }');
DEFINE('_DISPDAY','{ dia }');
DEFINE('_DISPTIME','{ hora }');
DEFINE('_ADDADDRESS','Adicionar Endereço');
DEFINE('_ADDIPFIELD','Adicionar Campo IP');
DEFINE('_ADDTIME','Adicionar Hora');
DEFINE('_ADDTCPPORT','Adicionar Porta TCP');
DEFINE('_ADDTCPFIELD','Adicionar Campo TCP');
DEFINE('_ADDUDPPORT','Adicionar Porta UDP');
DEFINE('_ADDUDPFIELD','Adicionar Campo UDP');
DEFINE('_ADDICMPFIELD','Adicionar Campo ICMP');
DEFINE('_ADDPAYLOAD','Adicionar Payload');
DEFINE('_MOSTFREQALERTS',$UI_CW_Alert.'s mais frequentes');
DEFINE('_MOSTFREQPORTS','portas mais frequentes');
DEFINE('_MOSTFREQADDRS','endereços IP mais frequentes');
DEFINE('_LASTALERTS',$UI_CW_Alert.'s mais recentes');
DEFINE('_LASTPORTS','portas mais recentes');
DEFINE('_LASTTCP',$UI_CW_Alert.'s TCP mais recentes');
DEFINE('_LASTUDP',$UI_CW_Alert.'s UDP mais recentes');
DEFINE('_LASTICMP',$UI_CW_Alert.'s ICMP mais recentes');
DEFINE('_QUERYDB','Pesquisar na BD');
DEFINE('_QUERYDBP','Pesquisar+na+BD'); //Igual a _QUERYDB onde os espaços são '+'s. 
                                       //Deveria ser algo como: DEFINE('_QUERYDBP',str_replace(" ", "+", _QUERYDB));
DEFINE('_SELECTED','Seleccionado(s)');
DEFINE('_ALLONSCREEN','Todos no ecrã');
DEFINE('_ENTIREQUERY','Toda a pesquisa');
DEFINE('_OPTIONS','Opções');
DEFINE('_LENGTH','comprimento');
DEFINE('_CODE','código');
DEFINE('_DATA','dados');
DEFINE('_TYPE',$UI_CW_Type);
DEFINE('_NEXT','Próximo');
DEFINE('_PREVIOUS','Anterior');

//Menu items
DEFINE('_HOME','Início');
DEFINE('_SEARCH','Pesquisa');
DEFINE('_AGMAINT','Manutenção do Grupo de '.$UI_CW_Alert.'s');
DEFINE('_USERPREF','Preferências do Utilizador');
DEFINE('_CACHE','Cache & Status');
DEFINE('_ADMIN','Administração');
DEFINE('_GALERTD','Gráfico de '.$UI_CW_Alert.'s');
DEFINE('_GALERTDT','Gráfico de '.$UI_CW_Alert.'s por Tempo');
DEFINE('_USERMAN','Controlo de Utilizadores');
DEFINE('_LISTU','Lista de utilizadores');
DEFINE('_CREATEU','Criar um utilizador');
DEFINE('_ROLEMAN','Gestão de Perfis');
DEFINE('_LISTR','Lista de Perfis');
DEFINE('_CREATER',"Criar um $UI_CW_Role");
DEFINE('_LISTALL','Listar Tudo');
DEFINE('_CREATE','Criar');
DEFINE('_VIEW','Ver');
DEFINE('_CLEAR','Limpar');
DEFINE('_LISTGROUPS','Listar Grupos');
DEFINE('_CREATEGROUPS','Criar Grupo');
DEFINE('_VIEWGROUPS','Ver Grupo');
DEFINE('_EDITGROUPS','Editar Grupo');
DEFINE('_DELETEGROUPS','Apagar Grupo');
DEFINE('_CLEARGROUPS','Limpar Grupo');
DEFINE('_CHNGPWD','Mudar '.$UI_CW_Pw);
DEFINE('_DISPLAYU','Mostrar Utilizador');

//base_footer.php
DEFINE('_FOOTER','( Por <A class="largemenuitem" href="https://github.com/secureideas">Kevin Johnson</A> e grupo <A class="largemenuitem" href="https://github.com/NathanGibbs3/BASE/wiki/Project-Team">do projecto BASE</A><BR>Baseado no ACID de Roman Danyliw )');

//index.php --Log in Page
DEFINE('_LOGINERROR','Utilizador inexistente ou '.strtolower($UI_CW_Pw).' incorreta!<br>Por favor tente novamente');

// base_main.php
DEFINE('_MOSTRECENT',$UI_CW_Alert.'s mais recentes - ');
DEFINE('_MOSTFREQUENT',$UI_CW_Alert.'s mais frequentes - ');
DEFINE('_ALERTS',' '.$UI_CW_Alert.'s:');
DEFINE('_ADDRESSES',' endereços');
DEFINE('_ANYPROTO','qualquer protocolo');
DEFINE('_UNI','únicos');
DEFINE('_LISTING','lista');
DEFINE('_TALERTS',$UI_CW_Alert.'s de Hoje: ');
DEFINE('_SOURCEIP','IP Origem');
DEFINE('_DESTIP','IP Destino');
DEFINE('_L24ALERTS',$UI_CW_Alert.'s das últimas 24 Horas: ');
DEFINE('_L72ALERTS',$UI_CW_Alert.'s das últimas 72 Horas: ');
DEFINE('_UNIALERTS',' '.$UI_CW_Alert.'s Únicos');
DEFINE('_LSOURCEPORTS','Portas de origem mais recentes: ');
DEFINE('_LDESTPORTS','Portas de destino mais recentes: ');
DEFINE('_FREGSOURCEP','Portas de origem mais frequentes: ');
DEFINE('_FREGDESTP','Portas de destino mais frequentes: ');
DEFINE('_QUERIED','Consultado em');
DEFINE('_DATABASE','BD:');
DEFINE('_SCHEMAV','Versão do Esquema:');
DEFINE('_TIMEWIN','Janela de tempo:');
DEFINE('_NOALERTSDETECT','nenhum '.$UI_CW_Alert.' detectado');
DEFINE('_USEALERTDB','Usar Base de Dados de '.$UI_CW_Alert.'s');
DEFINE('_USEARCHIDB','Usar Base de Dados de Arquivo');
DEFINE('_TRAFFICPROBPRO','Carácter do Tráfego por Protocolo');

//base_auth.inc.php
DEFINE('_ADDEDSF','Adicionado(s) com Sucesso');
DEFINE('_NOPWDCHANGE','Não foi possível trocar a sua '.strtolower($UI_CW_Pw).': ');
DEFINE('_NOUSER','Utilizador não existe!');
DEFINE('_OLDPWD',"$UI_CW_Pw antiga incorrecta!");
DEFINE('_PWDCANT','Não foi possível mudar a sua '.strtolower($UI_CW_Pw).': ');
DEFINE('_PWDDONE','A sua '.strtolower($UI_CW_Pw).' foi mudada!');
DEFINE('_ROLEEXIST',"$UI_CW_Role já existe");
// TD Migration Hack
if ($UI_Spacing == 1){
	$glue = ' ';
}else{
	$glue = '';
}
DEFINE('_ROLEIDEXIST',implode($glue, $UI_AD_RID)." já existe");
DEFINE('_ROLEADDED',"$UI_CW_Role adicionada com sucesso");

//base_roleadmin.php
DEFINE('_ROLEADMIN','Administração de Perfis do BASE');
DEFINE('_FRMROLENAME',"Nome do $UI_CW_Role:");
DEFINE('_UPDATEROLE',"Actualizar $UI_CW_Role");

//base_useradmin.php
DEFINE('_USERADMIN','BASE: Administração de Utilizadores');
DEFINE('_FRMFULLNAME','Nome Completo:');
DEFINE('_FRMUID','ID do Usuário:');
DEFINE('_SUBMITQUERY','Inserir');
DEFINE('_UPDATEUSER','Actualizar Utilizador');

//admin/index.php
DEFINE('_BASEADMIN','BASE: Administração');
DEFINE('_BASEADMINTEXT','Por favor selecione uma opção à esquerda.');

//base_action.inc.php
DEFINE('_NOACTION','Não foi especificada nenhuma acção nos '.$UI_CW_Alert.'s');
DEFINE('_INVALIDACT',' é uma acção inválida');
DEFINE('_ERRNOAG','Não foi possível adicionar '.$UI_CW_Alert.'s porque o GA não foi especificado');
DEFINE('_ERRNOEMAIL','Não foi possível enviar e-mail de '.$UI_CW_Alert.'s porque nenhum foi especificado');
DEFINE('_ACTION','ACÇÃO');
DEFINE('_CONTEXT','contexto');
DEFINE('_ADDAGID','ADICIONAR ao GA (pelo ID)');
DEFINE('_ADDAG','ADICIONAR-Novo-AG');
DEFINE('_ADDAGNAME','ADICIONAR ao GA (por Nome)');
DEFINE('_CREATEAG','Criar GA (por Nome)');
DEFINE('_CLEARAG','Apagar do GA');
DEFINE('_DELETEALERT','Apagar '.$UI_CW_Alert.'(s)');
DEFINE('_EMAILALERTSFULL',$UI_CW_Alert.'(s) de E-mail (completo)');
DEFINE('_EMAILALERTSSUMM',$UI_CW_Alert.'(s) de E-mail (sumário)');
DEFINE('_EMAILALERTSCSV',$UI_CW_Alert.'(s) de E-mail (csv)');
DEFINE('_ARCHIVEALERTSCOPY','Arquivo de '.$UI_CW_Alert.'(s) (cópia)');
DEFINE('_ARCHIVEALERTSMOVE','Arquivo de '.$UI_CW_Alert.'(s) (mover)');
DEFINE('_IGNORED','Ignorado ');
DEFINE('_DUPALERTS',' '.$UI_CW_Alert.'(s) duplicado(s)');
DEFINE('_ALERTSPARA',' '.$UI_CW_Alert.'(s)');
DEFINE('_NOALERTSSELECT','Não foi selecionado nenhum '.$UI_CW_Alert.' ou o');
DEFINE('_NOTSUCCESSFUL','não teve sucesso');
DEFINE('_ERRUNKAGID','O GA ID especificado não existe (o GA provávelmente não existe)');
DEFINE('_ERRREMOVEFAIL','Falhou ao remover o novo GA');
DEFINE('_GENBASE','Gerado por BASE');
DEFINE('_ERRNOEMAILEXP','ERRO DE EXPORTAÇÃO: Não foi possível enviar '.$UI_CW_Alert.'s para');
DEFINE('_ERRNOEMAILPHP','Verificar a configuração de mensagens em PHP.');
DEFINE('_ERRDELALERT','Erro ao apagar '.$UI_CW_Alert);
DEFINE('_ERRARCHIVE','Erro de arquivo:');
DEFINE('_ERRMAILNORECP','ERRO DE MENSAGEM: Nenhum destino Especificado');

//base_cache.inc.php
DEFINE('_ADDED','Adicionado(s) ');
DEFINE('_HOSTNAMESDNS',' hostnames para a cache IP DNS');
DEFINE('_HOSTNAMESWHOIS',' hostnames para a cache Whois');
DEFINE('_ERRCACHENULL','ERRO de Cache: Coluna NULA de '.$UI_CW_Event.' encontrada?');
DEFINE('_ERRCACHEERROR','ERRO DE CACHE DE '.$UI_CW_Event.':');
DEFINE('_ERRCACHEUPDATE','Não foi possível actualizar a cache de '.$UI_CW_Event.'s');
DEFINE('_ALERTSCACHE',' '.$UI_CW_Alert.'(s) para a cache de '.$UI_CW_Alert.'s');

//base_db.inc.php
DEFINE('_ERRSQLTRACE','Não foi possível abrir arquivo de trace do SQL');
DEFINE('_ERRSQLCONNECT','Erro ao conectar à BD: ');
DEFINE('_ERRSQLCONNECTINFO','<P>Verfique as variáveis de conexão à BD em <I>base_conf.php</I>
              <PRE>
               = $alert_dbname   : Base de dados MySQL onde os '.$UI_CW_Alert.'s estão gravados 
               = $alert_host     : host onde a base de dados está gravada
               = $alert_port     : porta onde a base de dados está gravada
               = $alert_user     : nome do utilizador da base de dados
               = $alert_password : '.strtolower($UI_CW_Pw).' para o utilizador
              </PRE>
              <P>');
DEFINE('_ERRSQLPCONNECT','Erro ao conectar à BD: ');
DEFINE('_ERRSQLDB','ERRO da base de dados:');
DEFINE('_DBALCHECK','Verificando lib de abstração da BD em');
DEFINE('_ERRSQLDBALLOAD1','<P><B>Erro ao carregar lib de abstração da BD: </B> de ');
DEFINE('_ERRSQLDBALLOAD2','<P>Verifique a variável da lib de abstração de BD <CODE>$DBlib_path</CODE> no <CODE>base_conf.php</CODE>
            <P>
            A biblioteca de Base de Dados actual utilizada é o ADODB, que pode ser descarregada
            em ');
DEFINE('_ERRSQLDBTYPE',$UI_CW_Type.' de Base de Dados Inválido Especificado');
DEFINE('_ERRSQLDBTYPEINFO1','A variável <CODE>\$DBtype</CODE> em <CODE>base_conf.php</CODE> foi selecionada para o '.$UI_CW_Type.' não reconhecido de ');
DEFINE('_ERRSQLDBTYPEINFO2','Somente as seguintes base de dados são suportadas: <PRE>
                MySQL         : \'mysql\'
                PostgreSQL    : \'postgres\'
                MS SQL Server : \'mssql\'
                Oracle        : \'oci8\'
             </PRE>');

//base_log_error.inc.php
DEFINE('_ERRBASEFATAL','BASE: ERRO FATAL:');

//base_log_timing.inc.php
DEFINE('_LOADEDIN','Carregado em');
DEFINE('_SECONDS','segundo(s)');

//base_net.inc.php
DEFINE('_ERRRESOLVEADDRESS','Não foi possível resolver o endereço');

//base_output_query.inc.php
DEFINE('_QUERYRESULTSHEADER','Query Results Output Header');

//base_signature.inc.php
DEFINE('_ERRSIGNAMEUNK','SigName desconhecido');
DEFINE('_ERRSIGPROIRITYUNK','SigPriority desconhecido');
DEFINE('_UNCLASS','não-classificado');

//base_state_citems.inc.php
DEFINE('_DENCODED','Dados codificados como');
DEFINE('_NODENCODED','(nenhuma conversão de dados, assumindo critério de codificação nativa da BD)');
DEFINE('_SHORTJAN','Jan');
DEFINE('_SHORTFEB','Fev');
DEFINE('_SHORTMAR','Mar');
DEFINE('_SHORTAPR','Abr');
DEFINE('_SHORTMAY','Mai');
DEFINE('_SHORTJUN','Jun');
DEFINE('_SHORTJLY','Jul');
DEFINE('_SHORTAUG','Ago');
DEFINE('_SHORTSEP','Set');
DEFINE('_SHORTOCT','Out');
DEFINE('_SHORTNOV','Nov');
DEFINE('_SHORTDEC','Dez');
DEFINE('_DISPSIG','{ assinatura }');
DEFINE('_DISPANYCLASS','{ qualquer Classificação }');
DEFINE('_DISPANYPRIO','{ qualquer Prioridade }');
DEFINE('_DISPANYSENSOR','{ qualquer Sensor }');
DEFINE('_DISPADDRESS','{ endereço }');
DEFINE('_DISPFIELD','{ campo }');
DEFINE('_DISPPORT','{ porta }');
DEFINE('_DISPENCODING','{ codificação }');
DEFINE('_DISPCONVERT2','{ Converter Em }');
DEFINE('_DISPANYAG','{ qualquer Grupo de '.$UI_CW_Alert.'s }');
DEFINE('_DISPPAYLOAD','{ payload }');
DEFINE('_DISPFLAGS','{ flags }');
DEFINE('_SIGEXACTLY','exactamente');
DEFINE('_SIGROUGHLY','parecido');
DEFINE('_SIGCLASS',"Classificação da $UI_CW_Sig");
DEFINE('_SIGPRIO',"Prioridade da $UI_CW_Sig");
DEFINE('_SHORTSOURCE','Orig');
DEFINE('_SHORTDEST','Dest');
DEFINE('_SHORTSOURCEORDEST','Orig ou Dest');
DEFINE('_NOLAYER4','sem camada 4');
DEFINE('_INPUTCRTENC',$UI_CW_Type.' de codificação para o critério');
DEFINE('_CONVERT2WS','Converter em (na procura)');

//base_state_common.inc.php
DEFINE('_PHPERRORCSESSION','ERRO PHP: Uma sessão (usuário) PHP customizada foi detectada. Porém, o BASE não foi configurado para explicitamente usar esse handler customizado.  Configure <CODE>use_user_session=1</CODE> em <CODE>base_conf.php</CODE>');
DEFINE('_PHPERRORCSESSIONCODE','ERRO PHP: Um handler de sessão (usuário) PHP customizado foi configurado, mas o código handler definido no <CODE>user_session_path</CODE> é inválido.');
DEFINE('_PHPERRORCSESSIONVAR','ERRO PHP: Um handler de sessão (usuário) PHP customizado foi configurado, mas a implementação deste handler não foi especificada no BASE.  Se deseja um handler customizado, configure a variável <CODE>user_session_path</CODE> em <CODE>base_conf.php</CODE>.');
DEFINE('_PHPSESSREG','Sessão Registada');

//base_state_criteria.inc.php
DEFINE('_REMOVE','Removendo');
DEFINE('_FROMCRIT','do critério');
DEFINE('_ERRCRITELEM','Elemento do critério inválido');

//base_state_query.inc.php
DEFINE('_VALIDCANNED','Valid Canned Query List');
DEFINE('_DISPLAYING','Exibindo');
DEFINE('_DISPLAYINGTOTAL','Exibindo '.$UI_CW_Alert.'s %d-%d de %d '.$UI_CW_Total);
DEFINE('_NOALERTS','Não foi encontrado nenhum '.$UI_CW_Alert.'.');
DEFINE('_QUERYRESULTS','Resultados da Consulta');
DEFINE('_QUERYSTATE','Estado da Consulta');
DEFINE('_DISPACTION','{ acção }');

//base_ag_common.php
DEFINE('_ERRAGNAMESEARCH','O nome do GA especificado é inválido. Tente novamente!');
DEFINE('_ERRAGNAMEEXIST','O GA especificado não existe.');
DEFINE('_ERRAGIDSEARCH','A pesquisa GA ID especificada é inválida. Tente novamente!');
DEFINE('_ERRAGLOOKUP','Erro ao procurar pelo GA ID');
DEFINE('_ERRAGINSERT','Erro ao inserir novo GA');

//base_ag_main.php
DEFINE('_AGMAINTTITLE','Manutenção de Grupos de '.$UI_CW_Alert.'s (GA)');
DEFINE('_ERRAGUPDATE','Erro ao atualizar o GA');
DEFINE('_ERRAGPACKETLIST','Erro removendo lista de pacotes para o GA:');
DEFINE('_ERRAGDELETE','Erro removendo o GA');
DEFINE('_AGDELETE','REMOVIDO com sucesso');
DEFINE('_AGDELETEINFO','informação removida');
DEFINE('_ERRAGSEARCHINV','O critério de pesquisa inserido é inválido. Tente novamente!');
DEFINE('_ERRAGSEARCHNOTFOUND','Não foi encontrado nenhum GA com esse critério.');
DEFINE('_NOALERTGOUPS','Não existem Grupos de '.$UI_CW_Alert.'s');
DEFINE('_NUMALERTS','# '.$UI_CW_Alert.'s');
DEFINE('_ACTIONS','Ações');
DEFINE('_NOTASSIGN','ainda não definido');
DEFINE('_SAVECHANGES','Guardar alterações');
DEFINE('_CONFIRMDELETE','Confirmar Eiminação');
DEFINE('_CONFIRMCLEAR','Confirmar Limpeza');

//base_common.php
DEFINE('_PORTSCAN',$UI_CW_Alert.'s de Portscan');

//base_db_common.php
DEFINE('_ERRDBINDEXCREATE','Não foi possível criar INDEX para');
DEFINE('_DBINDEXCREATE','INDEX criado com sucesso para');
DEFINE('_ERRSNORTVER','Pode ser uma versão antiga.  Somente bases de dados de '.$UI_CW_Alert.' criadas pelo Snort 1.7-beta0 ou superior são suportadas');
DEFINE('_ERRSNORTVER1','A seguinte base de dados');
DEFINE('_ERRSNORTVER2','parece estar incompleta/inválida');
DEFINE('_ERRDBSTRUCT1','A versão da base de dados é válida, mas a estrutura de BD do BASE');
DEFINE('_ERRDBSTRUCT2','não está presente. Use a <A HREF="base_db_setup.php">página de Setup</A> para configurar e optimizar a BD.');
DEFINE('_ERRPHPERROR','ERRO DO PHP');
DEFINE('_ERRPHPERROR1','Versão incompatível');
DEFINE('_ERRVERSION','Versão');
DEFINE('_ERRPHPERROR2','do PHP é muito antiga. Por favor, atualize para a versão 4.0.4 ou superior');
DEFINE('_ERRPHPMYSQLSUP','<B>Compilação do PHP incompleta</B>: <FONT>o suporte ao MySQL necessário para
               ler a base de dados de '.$UI_CW_Alert.'s não foi compilado no PHP.  
               Por favor, recompile o PHP com as bibliotecas necessárias (<CODE>--with-mysql</CODE>)</FONT>');
DEFINE('_ERRPHPPOSTGRESSUP','<B>Compilação do PHP incompleta</B>: <FONT>o suporte ao PostgreSQL necessário para
               ler a base de dados de '.$UI_CW_Alert.'s não foi compilado no PHP.  
               Por favor, recompile o PHP com as bibliotecas necessárias (<CODE>--with-pgsql</CODE>)</FONT>');
DEFINE('_ERRPHPMSSQLSUP','<B>Compilação do PHP incompleta</B>: <FONT>o suporte ao MS SQL Server necessário para
                   ler a base de dados de '.$UI_CW_Alert.'s não foi compilado no PHP.  
                   Por favor, recompile o PHP com as bibliotecas necessárias (<CODE>--enable-mssql</CODE>)</FONT>');
DEFINE('_ERRPHPORACLESUP','<B>Compilação do PHP incompleta</B>: <FONT>o suporte ao Oracle necessário para
                   ler a base de dados de '.$UI_CW_Alert.'s não foi compilado no PHP.  
                   Por favor, recompile o PHP com as bibliotecas necessárias (<CODE>--with-oci8</CODE>)</FONT>');

//base_graph_form.php
DEFINE('_CHARTTITLE','Título do gráfico:');

DEFINE('_CHARTTYPE',$UI_CW_Type.' de gráfico:');
DEFINE('_CHARTTYPES','{ '.$UI_CW_Type.' de gráfico }');
DEFINE('_CHARTPERIOD','Periodo do gráfico:');
DEFINE('_PERIODNO','sem periodo');
DEFINE('_PERIODWEEK','7 (uma semana)');
DEFINE('_PERIODDAY','24 (dia inteiro)');
DEFINE('_PERIOD168','168 (24x7)');
DEFINE('_CHARTSIZE','Tamanho: (largura x altura)');
DEFINE('_PLOTMARGINS','Margens de desenho: (esq x dir x cima x baixo)');
DEFINE('_PLOTTYPE',$UI_CW_Type.' de desenho:');
DEFINE('_TYPEBAR','barras');
DEFINE('_TYPELINE','linhas');
DEFINE('_TYPEPIE','tarte');
DEFINE('_CHARTHOUR','{hora}');
DEFINE('_CHARTDAY','{dia}');
DEFINE('_CHARTMONTH','{mês}');
DEFINE('_GRAPHALERTS','Desenhar gráfico');
DEFINE('_AXISCONTROLS','Controlo dos eixos X / Y');


DEFINE('_CHRTTYPEHOUR','Tempo (hora) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPEDAY','Tempo (dia) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPEWEEK','Tempo (semana) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPEMONTH','Tempo (mês) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPEYEAR','Tempo (ano) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPESRCIP','Endereço IP (Src.) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPEDSTIP','Endereço IP (Dst.) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPEDSTUDP','Porta UDP (Dst.) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPESRCUDP','Porta UDP (Src.) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPEDSTPORT','Porta TCP (Dst.) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPESRCPORT','Porta TCP (Src.) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPESIG','Classificação (Sig.) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTYPESENSOR','Sensor vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTBEGIN','Início do gráfico:');
DEFINE('_CHRTEND','Fim do gráfico:');
DEFINE('_CHRTDS','Origem dos dados:');
DEFINE('_CHRTX','Eixo X');
DEFINE('_CHRTY','Eixo Y');
DEFINE('_CHRTMINTRESH','Valor de limite mínimo');
DEFINE('_CHRTROTAXISLABEL','Rodar etiquetas dos eixos (90 degrees)');
DEFINE('_CHRTSHOWX','Mostrar linhas de grelha do eixo X');
DEFINE('_CHRTDISPLABELX','Mostrar etiqueta do eixo X a cada');
DEFINE('_CHRTDATAPOINTS','pontos de dados');
DEFINE('_CHRTYLOG','Eixo Y logarítmico');
DEFINE('_CHRTYGRID','Mostrar linhas de grelha do eixo Y');

//base_graph_main.php
DEFINE('_CHRTTITLE','Gráfico BASE');
DEFINE('_ERRCHRTNOTYPE','Não foi especificado nenhum '.$UI_CW_Type.' de gráfico');
DEFINE('_ERRNOAGSPEC','Não foi especificado nenhum GA. Usando todos '.$UI_CW_Alert.'s.');
DEFINE('_CHRTDATAIMPORT','Iniciando importação de dados');
DEFINE('_CHRTTIMEVNUMBER','Tempo vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTTIME','Tempo');
DEFINE('_CHRTALERTOCCUR','Ocorrência de '.$UI_CW_Alert.'s');
DEFINE('_CHRTSIPNUMBER','IP de Origem vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTSIP','Endereço IP de Origem');
DEFINE('_CHRTDIPALERTS','IP de Destino vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTDIP','Endereço IP de Destino');
DEFINE('_CHRTUDPPORTNUMBER','Porta UDP (Destino) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTDUDPPORT','Porta UDP Dest.');
DEFINE('_CHRTSUDPPORTNUMBER','Porta UDP (Origem) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTSUDPPORT','Porta UDP Orig.');
DEFINE('_CHRTPORTDESTNUMBER','Porta TCP (Destino) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTPORTDEST','Porta TCP Port');
DEFINE('_CHRTPORTSRCNUMBER','Porta TCP (Origem) vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTPORTSRC','Porta TCP Orig.');
DEFINE('_CHRTSIGNUMBER','Classificação da '.$UI_CW_Sig.' vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTCLASS','Classificação');
DEFINE('_CHRTSENSORNUMBER','Sensor vs. Número de '.$UI_CW_Alert.'s');
DEFINE('_CHRTHANDLEPERIOD','Manipulando Período se necessário');
DEFINE('_CHRTDUMP','Mostrando dados ... (escrevendo somente cada');
DEFINE('_CHRTDRAW','Desenhando gráfico');
DEFINE('_ERRCHRTNODATAPOINTS','Nenhum ponto de dados para desenhar');
DEFINE('_GRAPHALERTDATA','Gráficos de '.$UI_CW_Alert.'s');

//base_maintenance.php
DEFINE('_MAINTTITLE','Manutenção');
DEFINE('_MNTPHP','Compilação do PHP:');
DEFINE('_MNTCLIENT','CLIENTE:');
DEFINE('_MNTSERVER','SERVIDOR:');
DEFINE('_MNTSERVERHW','HW DO SERVIDOR:');
DEFINE('_MNTPHPVER','VERSÃO DO PHP:');
DEFINE('_MNTPHPAPI','API DO PHP:');
DEFINE('_MNTPHPLOGLVL','Nível do log do PHP:');
DEFINE('_MNTPHPMODS','Módulos Carregados:');
DEFINE('_MNTDBTYPE',$UI_CW_Type.' de BD:');
DEFINE('_MNTDBALV','Versão da abstração da BD:');
DEFINE('_MNTDBALERTNAME','Nome da BD de '.$UI_CW_Alert.':');
DEFINE('_MNTDBARCHNAME','Nome da BD de ARQUIVO:');
DEFINE('_MNTAIC','Informações da cache de '.$UI_CW_Alert.'s:');
DEFINE('_MNTAICTE',$UI_CW_Total.' de '.$UI_CW_Event.'s:');
DEFINE('_MNTAICCE',$UI_CW_Event.'s na Cache:');
DEFINE('_MNTIPAC','Cache de Endereços IP');
DEFINE('_MNTIPACUSIP','IP de origem únicos:');
DEFINE('_MNTIPACDNSC','DNS na Cache:');
DEFINE('_MNTIPACWC','Whois na Cache:');
DEFINE('_MNTIPACUDIP','IP de destino únicos:');

//base_qry_alert.php
DEFINE('_QAINVPAIR','Par (sid,cid) inválido');
DEFINE('_QAALERTDELET',$UI_CW_Alert.' REMOVIDO');
DEFINE('_QATRIGGERSIG',$UI_CW_Sig.' que despoletou');
DEFINE('_QANORMALD','Vista normal');
DEFINE('_QAPLAIND','Vista plana');
DEFINE('_QANOPAYLOAD','Foi usado log rápido portanto o payload foi descartado');

//base_qry_common.php
DEFINE('_QCSIG','assinatura');
DEFINE('_QCIPADDR','Endereços IP');
DEFINE('_QCIPFIELDS','Campos IP');
DEFINE('_QCTCPPORTS','Portas TCP');
DEFINE('_QCTCPFLAGS','Flags TCP');
DEFINE('_QCTCPFIELD','Campos TCP');
DEFINE('_QCUDPPORTS','Portas UDP');
DEFINE('_QCUDPFIELDS','Campos UDP');
DEFINE('_QCICMPFIELDS','Campos ICMP');
DEFINE('_QCDATA','Dados');
DEFINE('_QCERRCRITWARN','Aviso de critério:');
DEFINE('_QCERRVALUE','Um valor de');
DEFINE('_QCERRFIELD','Um campo de');
DEFINE('_QCERROPER','Um operador de');
DEFINE('_QCERRDATETIME','Um valor data/hora de');
DEFINE('_QCERRPAYLOAD','Um valor payload de');
DEFINE('_QCERRIP','Um endereço IP de');
DEFINE('_QCERRIPTYPE','Um endereço IP do '.$UI_CW_Type);
DEFINE('_QCERRSPECFIELD',' foi inserido para um campo de protocolo, mas o campo em particular não foi especificado.');
DEFINE('_QCERRSPECVALUE','foi selecionado indicando que deve ser um critério, mas nenhum valor foi especificado para a comparação.');
DEFINE('_QCERRBOOLEAN','Multiplos campos de protocolo inseridos sem um operador boleano (ex.: E, OU) entre eles.');
DEFINE('_QCERRDATEVALUE','foi selecionado indicando que um critério de data/hora deve ser comparado, mas nenhum valor foi especificado.');
DEFINE('_QCERRINVHOUR','(Hora Inválida) Nenhum critério de data foi inserido com a hora especificada.');
DEFINE('_QCERRDATECRIT','foi selecionado indicando que um critério de data/hora deve ser comparado, mas nenhum valor foi especificado.');
DEFINE('_QCERROPERSELECT','foi inserido mas nenhum operador foi selecionado.');
DEFINE('_QCERRDATEBOOL','Múltiplos critério de Data/Hora foram inseridos sem um operador boleano (ex.: E, OU) entre eles.');
DEFINE('_QCERRPAYCRITOPER','foi inserido para um critério de campo de payload, mas um operator (e.g. has, has not) não foi especificado.');
DEFINE('_QCERRPAYCRITVALUE','foi selecionado indicando que o payload deve ser um critério, mas nenhum valor para comparação foi especificado.');
DEFINE('_QCERRPAYBOOL','Múltiplos critérios de dados de payload inseridos sem um operador boleano (ex.: E, OU) entre eles.');
DEFINE('_QCMETACRIT','Meta Critério');
DEFINE('_QCIPCRIT','Critério de IP');
DEFINE('_QCPAYCRIT','Critério de Payload');
DEFINE('_QCTCPCRIT','Critério de TCP');
DEFINE('_QCUDPCRIT','Critério de UDP');
DEFINE('_QCICMPCRIT','Critério de ICMP');
DEFINE('_QCLAYER4CRIT','Critério de Camada 4');
DEFINE('_QCERRINVIPCRIT','Critério de endereço IP inválido');
DEFINE('_QCERRCRITADDRESSTYPE','foi inserido como um valor de critério, mas o '.$UI_CW_Type.' de endereço (ex.: origem, destino) não foi especificado.');
DEFINE('_QCERRCRITIPADDRESSNONE','indicando que um endereço IP deve ser um critério, mas nenhum endereço para comparação foi especificado.');
DEFINE('_QCERRCRITIPADDRESSNONE1','foi selecionado (em #');
DEFINE('_QCERRCRITIPIPBOOL','Foi inserido um critério de Multiplos endereços IP sem um operador boleano (ex.: E, OU) entre eles.');

//base_qry_form.php
DEFINE('_QFRMSORTORDER','Classificação');
DEFINE('_QFRMSORTNONE','Nenhuma');
DEFINE('_QFRMTIMEA','Data (ascendente)');
DEFINE('_QFRMTIMED','Data (descendente)');
DEFINE('_QFRMSIG',$UI_CW_Sig);
DEFINE('_QFRMSIP','IP de origem');
DEFINE('_QFRMDIP','IP de destino');

//base_qry_sqlcalls.php
DEFINE('_QSCSUMM','Resumo Estatístico');
DEFINE('_QSCTIMEPROF',' Perfil de Tempo');
DEFINE('_QSCOFALERTS','dos '.$UI_CW_Alert.'s');

//base_stat_alerts.php
DEFINE('_ALERTTITLE','Lista de '.$UI_CW_Alert.'s');

//base_stat_common.php
DEFINE('_SCCATEGORIES','Categorias:');
DEFINE('_SCSENSORTOTAL','Sensores/'.$UI_CW_Total.':');
DEFINE('_SCTOTALNUMALERTS','Número '.$UI_CW_Total.' de '.$UI_CW_Alert.'s:');
DEFINE('_SCSRCIP','Ends. IP de Origem:');
DEFINE('_SCDSTIP','Ends. IP de Destino:');
DEFINE('_SCUNILINKS','Links IP Únicos');
DEFINE('_SCSRCPORTS','Portas de Origem: ');
DEFINE('_SCDSTPORTS','Portas de Destino: ');
DEFINE('_SCSENSORS',' Sensores');
DEFINE('_SCCLASS','classificações');
DEFINE('_SCUNIADDRESS',' Endereços Únicos: ');
DEFINE('_SCSOURCE','Origem');
DEFINE('_SCDEST','Destino');
DEFINE('_SCPORT','Porta');

//base_stat_ipaddr.php
DEFINE('_PSEVENTERR','ERRO DE '.$UI_CW_Event.' PORTSCAN: ');
DEFINE('_PSEVENTERRNOFILE','Não foi especificado nenhum arquivo na variável $portscan_file.');
DEFINE('_PSEVENTERROPENFILE','Não foi possível abrir o arquivo de '.$UI_CW_Event.'s Portscan');
DEFINE('_PSDATETIME','Data/Hora');
DEFINE('_PSSRCIP','IP de Origem');
DEFINE('_PSDSTIP','IP de Destino');
DEFINE('_PSSRCPORT','Porta de Origem');
DEFINE('_PSDSTPORT','Porta de Destino');
DEFINE('_PSTCPFLAGS','Flags TCP');
DEFINE('_PSTOTALOCC',$UI_CW_Total.' de<BR> Ocorrências');
DEFINE('_PSNUMSENSORS','Núm de Sensores');
DEFINE('_PSFIRSTOCC',$UI_CW_First.'<BR> Ocorrência');
DEFINE('_PSLASTOCC',$UI_CW_Last.'<BR> Ocorrência');
DEFINE('_PSUNIALERTS',$UI_CW_Alert.'s Únicos');
DEFINE('_PSPORTSCANEVE',$UI_CW_Event.'s Portscan');
DEFINE('_PSREGWHOIS','Consulta registo (whois) em');
DEFINE('_PSNODNS','nenhuma consulta DNS realizada');
DEFINE('_PSNUMSENSORSBR','Núm de <BR>Sensores');
DEFINE('_PSOCCASSRC','Ocorrências <BR>como Orig.');
DEFINE('_PSOCCASDST','Ocorrências <BR>como Dest.');
DEFINE('_PSWHOISINFO','Informações Whois');

//base_stat_iplink.php
DEFINE('_SIPLTITLE','Links IP');
DEFINE('_SIPLSOURCEFGDN','FQDN de Origem');
DEFINE('_SIPLDESTFGDN','FQDN de Destino');
DEFINE('_SIPLDIRECTION','Direção');
DEFINE('_SIPLPROTO','Protocolo');
DEFINE('_SIPLUNIDSTPORTS','Portas de Destino Únicas');
DEFINE('_SIPLUNIEVENTS',$UI_CW_Event.'s Únicos');
DEFINE('_SIPLTOTALEVENTS',$UI_CW_Total.' de '.$UI_CW_Event.'s');
DEFINE('_PSTOTALHOSTS',$UI_CW_Total.' de endereços pesquisados');
DEFINE('_PSDETECTAMONG','Foram detectados %d '.$UI_CW_Alert.'s únicos entre %d no endereço %s');
DEFINE('_PSALLALERTSAS','Todos os '.$UI_CW_Alert.'s com %s/%s como');
DEFINE('_PSSHOW','Mostra');
DEFINE('_PSEXTERNAL','Externo');

//base_stat_ports.php
DEFINE('_UNIQ','Únicas');
DEFINE('_DSTPS','Porta(s) de Destino');
DEFINE('_SRCPS','Porta(s) de Origem');
DEFINE('_OCCURRENCES','Ocorrências');

//base_stat_sensor.php
DEFINE('SPSENSORLIST','Lista de Sensores');

//base_stat_time.php
DEFINE('_BSTTITLE','Perfil de tempo dos '.$UI_CW_Alert.'s');
DEFINE('_BSTTIMECRIT','Critério de Tempo');
DEFINE('_BSTERRPROFILECRIT','<FONT><B>Nenhum critério de perfil foi especificado!</B>  Clique em "hora", "dia", ou "mês" para escolher a granularidade do agrupamento estatístico.</FONT>');
DEFINE('_BSTERRTIMETYPE','<FONT><B>O parâmetro de intervalo de tempo a ser usado não foi especificado!</B>  Escolha "em" para especificar uma única data, ou "entre" para especificar um intervalo.</FONT>');
DEFINE('_BSTERRNOYEAR','<FONT><B>Nenhum parâmetro de ano foi especificado!</B></FONT>');
DEFINE('_BSTERRNOMONTH','<FONT><B>Nenhum parâmetro de mês foi especificado!</B></FONT>');
DEFINE('_BSTERRNODAY','<FONT><B>Nenhum parâmetro de dia foi especificado!</B></FONT>');
DEFINE('_BSTPROFILEBY','Caracterizar por');
DEFINE('_TIMEON','em');
DEFINE('_TIMEBETWEEN','entre');
DEFINE('_PROFILEALERT','Caracterizar '.$UI_CW_Alert);

//base_stat_uaddr.php
DEFINE('_UNISADD','Ends. de Origem Únicos');
DEFINE('_SUASRCIP','Ends. IP de Origem');
DEFINE('_SUAERRCRITADDUNK','ERRO DE CRITÉRIO: '.$UI_CW_Type.' de end. desconhecido -- assumindo end. de destino');
DEFINE('_UNIDADD','Endereços Únicos');
DEFINE('_SUADSTIP','Ends. IP de Destino');
DEFINE('_SUAUNIALERTS',$UI_CW_Alert.'s&nbsp;Únicos');
DEFINE('_SUASRCADD','Ends.&nbsp;de&nbsp;Origem');
DEFINE('_SUADSTADD','Ends.&nbsp;de&nbsp;Destino');

//base_user.php
DEFINE('_BASEUSERTITLE','Preferências de usuário do BASE');
DEFINE('_BASEUSERERRPWD','A '.strtolower($UI_CW_Pw).' não pode ser nula ou as duas '.strtolower($UI_CW_Pw).'s não coincidem!');
DEFINE('_BASEUSEROLDPWD',"$UI_CW_Pw antiga:");
DEFINE('_BASEUSERNEWPWD','Nova '.strtolower($UI_CW_Pw).':');
DEFINE('_BASEUSERNEWPWDAGAIN','Repita a nova '.strtolower($UI_CW_Pw).':');

DEFINE('_LOGOUT','Logout');

?>
