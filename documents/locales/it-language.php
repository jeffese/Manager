<?php

// Messaggi in italiano per PhpDig (28-mag-2001)
// Traduzione di Mirko Maischberger <mirko@lilik.ing.unifi.it>
// Sito preferito: http://lilik.ing.unifi.it
// Aggiunta alla Traduzione di Simone Capra - E.R.WEB - s.r.l. <capra@erweb.it>
// Sito internet: http://www.erweb.it

//'keyword' => 'translation'
$phpdig_mess = array (
'mode'          =>'mode',
'query'         =>'query',
'list_meanings' =>'• Total - lists the total number of searches for each query
• Query - lists the various keywords for each search query
• Mode - lists the "and, exact, or" search mode per query
• Links - lists the average number of links found per query
• Time - lists the most recent GMT timestamp of each query',
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
'yes'          =>'si',
'no'           =>'no',
'delete'       =>'cancella',
'reindex'      =>'rigenera indice',
'back'         =>'Indietro',
'files'        =>'files',
'admin'        =>'Amministrazione',
'warning'      =>'Attenzione!',
'index_uri'    =>'Quale URI vuoi indicizzare?',
'spider_depth' =>'Profondit&agrave; della ricerca',
'spider_warn'  =>'Assicurati che nessun altro stia aggiornando lo stesso sito prima di procedere.
La prossima versione implementer&agrave; un meccanismo di locking.',
'site_update'  =>'Aggiorna un sito o uno dei suoi rami',
'clean'        =>'Pulisci',
't_index'      =>'indice',
't_dic'        =>'dizionario',
't_stopw'      =>'parole di uso comune',

'update'       =>'Aggiorna',
'tree_found'   =>'Found tree',
'update_mess'  =>'Re-index or delete a tree ',
'update_warn'  =>'L\'esclusione &egrave; permanente',
'update_help'  =>'Clicca sulla croce per cancellare questo ramo
Clicca sul simbolo verde per aggiornarlo',
'branch_start' =>'Seleziona la cartella da mostrare sul lato sinistro',
'branch_help1' =>'Seleziona i documenti da aggiornare manualmente',
'branch_help2' =>'Clicca sulla croce per cancellare un documento
Clicca sul simbolo verde per reindicizzare
La freccia lancia lo spider',
'redepth'      =>'livelli di profondit&agrave;',
'branch_warn'  =>'La cancellazione &egrave; permanente',
'to_admin'     =>'all\'interfaccia di amministrazione',

'search'       =>'Cerca',
'results'      =>'risultati',
'display'      =>'mostra',
'w_begin'      =>'solo inizio parole',
'w_whole'      =>'parole esatte',
'w_part'       =>'qualsiasi parte delle parole',

'limit_to'     =>'limita a',
'this_path'    =>'questa cartella',
'total'        =>'totali',
'seconds'      =>'secondi',
'w_common'     =>'ignora le parole di uso comune.',
'w_short'      =>'ignora le parole troppo corte.',
's_results'    =>'risultati della ricerca',
'previous'     =>'Precedente',
'next'         =>'Successivo',
'on'           =>'per',

'id_start'     =>'Indicizzazione del sito',
'id_end'       =>'Indicizzazione completata!',
'id_recent'    =>'&Egrave; stato indicizzato recentemente',
'num_words'    =>'Numero di parole',
'time'         =>'data',
'error'        =>'Errore',
'no_spider'    =>'Lo spider non &egrave; stato lanciato',
'no_site'      =>'Questo sito non esiste nel database',
'no_temp'      =>'Nessun link nella tabella temporanea',
'no_toindex'   =>'Nulla da indicizzare',
'double'       =>'Duplicato di un documento esistente',

'spidering'    =>'Lo spider sta lavorando...',
'links_more'   =>'ulteriori link',
'level'        =>'livello',
'links_found'  =>'trovati nuovi link',
'define_ex'    =>'Definisci le esclusioni',
'index_all'    =>'indicizza tutto',

'end'          =>'fine',
'no_query'     =>'Riempia il campo di ricerca, per piacere',
'pwait'        =>'Attendere prego',
'statistics'   =>'Statistiche',

// INSTALL
'slogan'   =>'Il pi&ugrave; piccolo motore di ricerca dell\'universo versione : version',
'installation'   =>'Installazione',
'instructions' =>'Specificare i parametri di MySQL. Specificare un utente che possa creare databases se necessario (nel crea database o modifica esistente).',
'hostname'   =>'Nome Host  :',
'port'   =>'Porta (vuoto = default) :',
'sock'   =>'Sock (vuoto = default) :',
'user'   =>'Utente :',
'password'   =>'Password :',
'phpdigdatabase'   =>'Database PhpDig :',
'tablesprefix'   =>'Prefisso delle tabelle :',
'instructions2'   =>'* opzionale. Usa caratteri minuscoli, 16 caratteri al massimo.',
'installdatabase'   =>'Installa il database phpdig ',
'error1'   =>'Non trovo il template di connessione. ',
'error2'   =>'Non riesco a scrivere il template di connessione. ',
'error3'   =>'Non trovo il file init_db.sql. ',
'error4'   =>'Non riesco a creare le tabelle. ',
'error5'   =>'Non riesco a trovare i files di connessione al database. ',
'error6'   =>'Non riesco a creare il database.<br />Verificare i diritti dell\'utente. ',
'error7'   =>'Non riesco a connettermi al database<br />Verificare i dati di connessione. ',
'createdb' =>'Crea il database',
'createtables' =>'Crea solo le tabelle',
'updatedb' =>'Modifica il database esistente',
'existingdb' =>'Scrivi solo i parametri di connessione',
// CLEANUP_ENGINE
'cleaningindex'   =>'Sto pulendo l\'indice',
'enginenotok'   =>' index references targeted an inexistent keyword.',
'engineok'   =>'L\'indice &egrave; coerente.',
// CLEANUP_KEYWORDS
'cleaningdictionnary'   =>'Sto pulendo il dizionario',
'keywordsok'   =>'Tutte le parole chiave sono presenti in almeno una pagina.',
'keywordsnotok'   =>' non era in alcuna pagina.',
// CLEANUP_COMMON
'cleanupcommon' =>'Pulisci il dizionario dalle parole comuni',
'cleanuptotal' =>'Totali ',
'cleaned' =>' pulite.',
'deletedfor' =>' cancellate per ',
// INDEX ADMIN
'digthis' =>'Indicizza!',
'databasestatus' =>'Stato del DataBase',
'entries' =>' elementi ',
'updateform' =>'Modifica indice',
'deletesite' =>'Cancella sito',
// SPIDER
'spiderresults' =>'Risultati dello Spider',
// STATISTICS
'mostkeywords' =>'Parole chiave pi&ugrave; ricorrenti',
'richestpages' =>'Pagine pi&ugrave; ricche',
'mostterms'    =>'Termini di ricerca pi&ugrave; usati',
'largestresults'=>'Risultati pi&ugrave; ampi ottenuti',
'mostempty'     =>'Risultati nulli ottenuti',
'lastqueries'   =>'Ultime ricerche effettuate',
'responsebyhour'=>'Tempi di risposta',
// UPDATE
'userpasschanged' =>'User/Password cambiata !',
'uri' =>'URI : ',
'change' =>'Cambia',
'root' =>'Root',
'pages' =>' pagine',
'locked' => 'Bloccato',
'unlock' => 'Sblocca il sito',
'onelock' => 'Un sito &egrave; bloccato perch&egrave; lo si sta indicizzando . Non puoi fare questo ora!',
// PHPDIG_FORM
'go' =>'Vai ...',
// SEARCH_FUNCTION
'noresults' =>'Nessun risultato'
);
?>