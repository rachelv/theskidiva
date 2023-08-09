<?php
	
	// init environment
    error_reporting( E_ALL | E_STRICT );  
    ini_set( 'display_startup_errors', 1 );  
    ini_set( 'display_errors', 1 );
    set_include_path( '../library' . PATH_SEPARATOR . get_include_path() );
    date_default_timezone_set( 'America/New_York' );
	setlocale( LC_ALL, 'en_US' );
    
    // autoloading of classes
    require_once 'Zend/Loader.php';
    Zend_Loader::registerAutoload();
	
	// db connection
	$db = Zend_Db::factory( 'Mysqli', array(
		'host' => 'mysql5-22.wc1',
		'username' => '360291_skidiva',
		'password' => 'ekcjqIEx8930Zdq',
		'dbname' => '360291_skidiva'
	) );
	$db->setFetchMode( Zend_Db::FETCH_OBJ );
    Zend_Registry::set( 'db', $db );
	
	// cache
	$cache = Zend_Cache::factory(
        'Core',
        'File',
        $frontend_opts = array( 
        	'automatic_serialization' => true,
            'lifetime' => 900, // 15 minutes
            'automatic_cleaning_factor' => 1
        ),
        $backend_opts = array(
            'cache_dir' => '../tmp',
            'file_name_prefix' => 'sdv',
        )
    );
    Zend_Registry::set( 'shortcache', $cache );
	
	// cache date for better performance
	Zend_Date::setOptions( array( 'cache' => $cache ) );
	
	// retrieve 'home' page content
	if ( !$page = $cache->load( 'page_home' ) )
	{
		$query = 'SELECT * FROM pages WHERE shortname LIKE ?';
		$page = $db->fetchRow( $query, 'home' );
		
		$cache->save( $page, 'page_home' );
	}
	
	
	// retrieve 2 random photos
	$query = 'SELECT * FROM images WHERE DATE_ADD(created, INTERVAL 10 MONTH)>NOW() ORDER BY RAND() LIMIT 2';
	$photos = $db->fetchAll( $query );
	
	
	// retrieve 4 random RSS items
	if ( !$feeds = $cache->load( 'rss_home' ) )
	{
		
		$cache->save( $feeds, 'rss_home' );
	}
	
	// retrieve 3 latest forum posts
	if ( !$forum = $cache->load( 'forum_home' ) )
	{
		$forum = array();
		
		$channel = new Zend_Feed_Rss( 'http://www.theskidiva.com/forums/external.php?type=rss2' );
		foreach ( $channel as $item )
		{
			$pubdate = new Zend_Date( $item->pubDate(), Zend_Date::RSS );
			$forum[] = array(
				'title' => $item->title(),
				'date' => $pubdate,
				'link' => $item->link(),
				'author' => $item->__call( 'dc:author', null )
			);
		}
		
		$cache->save( $forum, 'forum_home' );
	}
	
	// retrieve 1 random store item
	if ( !$page = $cache->load( 'store_home' ) )
	{
		
		$cache->save( $page, 'store_home' );
	}
	
	
?>