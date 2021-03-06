<?php

//Spanish messages for PhpDig
//by Geffrey Vel�squez, Manuel Talens

//'keyword' => 'translation'
$phpdig_mess = array (
'mode'          =>'mode',
'query'         =>'query',
'list_meanings' =>'� Total - lists the total number of searches for each query
� Query - lists the various keywords for each search query
� Mode - lists the "and, exact, or" search mode per query
� Links - lists the average number of links found per query
� Time - lists the most recent GMT timestamp of each query',
'with_no_results' =>'with no results',
'with_results' =>'with results',
'searches'     =>'searches',
'page'         =>'Page',
'of'           =>'of',
'to'           =>'to',
'listing'      =>'Listing',
'viewList'     =>'View List of Queries',
'one_per_line' =>'Enter one link per line',

'StopSpider'   =>'Stop spider',
'id'           =>'ID',
'url'          =>'URL',
'days'         =>'Days',
'links'        =>'Links',
'depth'        =>'Depth',
'viewRSS'      =>'View RSS for this Page',
'powered_by'   =>'Powered by PhpDig',
'searchall'    =>'Search All',
'wait'         =>'Wait... ',
'done'         =>'Done!',
'limit'        =>'Limit',
'manage'       =>'Here you can manage:',
'dayscron'     =>'- the number of <b>days</b> crontab waits to reindex (0 = ignore)',
'links_mean'   =>'- the max number of <b>links</b> per depth per site (0 = unlimited)',
'depth_mean'   =>'- the max search <b>depth</b> per site (0 = none, depth trumps links)',
'max_found'    =>'Maximum links found is ((links * depth) + 1) when links is greater than zero.',
'default_vals' =>'Default values',
'use_vals_from' =>'Use values from',
'table_present' =>'table if present and use<br/>default values if values absent from table?',
'admin_msg_1'   =>'- To empty tempspider table click delete button <i>without</i> selecting a site',
'admin_msg_2'   =>'- Search depth of zero tries to crawl just that page regardless of links per',
'admin_msg_3'   =>'- Set links per depth to the max number of links to check at each depth',
'admin_msg_4'   =>'- Links per depth of zero means to check for all links at each seach depth',
'admin_msg_5'   =>'- Clean dashes removes \'-\' index pages from blue arrow listings of pages',
'admin_panel'   =>'Admin Panel',

'choose_temp'  =>'Choose a template',
'select_site'  =>'Select a site to search',
'restart'      =>'Restart',
'narrow_path'  =>'Narrow Path to Search',
'upd_sites'    =>'Update sites',
'upd2'         =>'Update Done',
'links_per'    =>'Links per',
'yes'          =>'s�',
'no'           =>'no',
'delete'       =>'eliminar',
'reindex'      =>'Reindexar',
'back'         =>'Atr�s',
'files'        =>'archivos',
'admin'        =>'Administraci�n',
'warning'      =>'�Advertencia!',
'index_uri'    =>'�Qu� URI desea indexar?',
'spider_depth' =>'Profundidad de b�squeda',
'spider_warn'  =>'Por favor, aseg�rese de que nadie m�s est� actualizando este mismo sitio.
Un mecanismo de bloqueo ser� incluido en versiones posteriores',
'site_update'  =>'Actualizar un sitio o una de sus ramificaciones',
'clean'        =>'Limpiar',
't_index'      =>'�ndice',
't_dic'        =>'diccionario',
't_stopw'      =>'palabras comunes',
't_dash'       =>'dashes',

'update'       =>'Actualizar',
'tree_found'   =>'�rbol encontrado',
'update_mess'  =>'Reindexar o borrar un �rbol ',
'update_warn'  =>'La exclusi�n es permanente',
'update_help'  =>'Haga clic en el aspa para borrar la ramificaci�n
Haga clic en el bot�n verde para actualizar',
'branch_start' =>'Seleccione la carpeta para mostrarla en el lado izquierdo',
'branch_help1' =>'Seleccione los documentos para actualizarlos',
'branch_help2' =>'Haga clic en el aspa para eliminar un documento
Haga clic en el bot�n verde para reindexar
La flecha inicia un spidering',
'redepth'      =>'Grados de profundidad',
'branch_warn'  =>'La eliminaci�n es permanente',
'to_admin'     =>'vaya a la p�gina de administraci�n',

'search'       =>'B�squeda',
'results'      =>'resultados',
'display'      =>'Mostrar',
'w_begin'      =>'Al inicio',
'w_whole'      =>'Palabras exactas',
'w_part'       =>'En cualquier lugar',
'alt_try'      =>'Did you mean',

'limit_to'     =>'Limitar a',
'this_path'    =>'esta ruta',
'total'        =>'total',
'seconds'      =>'segundos',
'w_common_sing'     =>'las palabras comunes fueron obviadas.',
'w_short_sing'      =>'las palabras cortas fueron obviadas.',
'w_common_plur'     =>'las palabras comunes fueron obviadas.',
'w_short_plur'      =>'las palabras cortas fueron obviadas.',
's_results'    =>'resultados de la b�squeda',
'previous'     =>'Anterior',
'next'         =>'Siguiente',
'on'           =>'en',

'id_start'     =>'Indexaci�n del sito',
'id_end'       =>'�Indexaci�n completa!',
'id_recent'    =>'Fue recientemente indexado',
'num_words'    =>'N�mero de palabras',
'time'         =>'tiempo',
'error'        =>'Error',
'no_spider'    =>'Spider no iniciado',
'no_site'      =>'No se encontr� el sitio en la base de datos',
'no_temp'      =>'No existe el enlace en la tabla temporal',
'no_toindex'   =>'Nada para indexar',
'double'       =>'Duplicado de un documento existente',

'spidering'    =>'Spidering en progreso',
'links_more'   =>'m�s enlaces nuevos',
'level'        =>'nivel',
'links_found'  =>'enlaces encontrados',
'define_ex'    =>'Definir exclusiones',
'index_all'    =>'indexar todo',

'end'          =>'fin',
'no_query'     =>'Escriba en el recuadro la palabra o la frase que desea buscar',
'pwait'        =>'Por favor, espere',
'statistics'   =>'Estad�sticas',

// INSTALL
'slogan'   =>'The smallest search engine in the universe : version',
'installation'   =>'Installation',
'instructions' =>'Type here the MySql parameters. Specify a valid existing user who can create databases if you choose create or update.',
'hostname'   =>'Hostname  :',
'port'   =>'Port (none = default) :',
'sock'   =>'Sock (none = default) :',
'user'   =>'User :',
'password'   =>'Password :',
'phpdigdatabase'   =>'PhpDig database :',
'tablesprefix'   =>'Tables prefix :',
'instructions2'   =>'* optional. Use lowercase characters, 16 characters max.',
'installdatabase'   =>'Install phpdig database',
'error1'   =>'Can\'t find connexion template. ',
'error2'   =>'Can\'t write connexion template. ',
'error3'   =>'Can\'t find init_db.sql file. ',
'error4'   =>'Can\'t create tables. ',
'error5'   =>'Can\'t find all config database files. ',
'error6'   =>'Can\'t create database.<br />Verify user\'s rights. ',
'error7'   =>'Can\'t connect to database<br />Verify connection datas. ',
'createdb' =>'Create database',
'createtables' =>'Create tables only',
'updatedb' =>'Update existing database',
'existingdb' =>'Write only connection parameters',
// CLEANUP_ENGINE
'cleaningindex'   =>'Cleaning index',
'enginenotok'   =>' index references targeted an inexistent keyword.',
'engineok'   =>'Engine is coherent.',
// CLEANUP_KEYWORDS
'cleaningdictionnary'   =>'Cleaning dictionnary',
'keywordsok'   =>'All keywords are in one or more page.',
'keywordsnotok'   =>' keywords where not in one page at least.',
// CLEANUP_COMMON
'cleanupcommon' =>'Cleanup common words',
'cleanuptotal' =>'Total ',
'cleaned' =>' cleaned.',
'deletedfor' =>' deleted for ',
// INDEX ADMIN
'digthis' =>'Dig this !',
'databasestatus' =>'DataBase status',
'entries' =>' Entries ',
'updateform' =>'Update form',
'deletesite' =>'Delete site',
// SPIDER
'spiderresults' =>'Spider results',
// STATISTICS
'mostkeywords' =>'Most keywords',
'richestpages' =>'Richest pages',
'mostterms'    =>'Most search terms',
'largestresults'=>'Largest results',
'mostempty'     =>'Most searchs giving empty results',
'lastqueries'   =>'Last search queries',
'responsebyhour'=>'Response time by hour',
// UPDATE
'userpasschanged' =>'User/Password changed !',
'uri' =>'URI : ',
'change' =>'Change',
'root' =>'Root',
'pages' =>' pages',
'locked' => 'Locked',
'unlock' => 'Unlock site',
'onelock' => 'A site is locked, because of spidering. You can\'t do this for now',
// PHPDIG_FORM
'go' =>'Ir...',
// SEARCH_FUNCTION
'noresults' =>'No hay resultados'
);
?>