<?php

	class view_default extends View {

		function display() {

			include( PRESENTATION_RESOURCES_DIR . "/html/docHead.php" ) ;

			$lorem = getFiller() ;

			print "
			<div class='pageSection'>
			<p class='pageHeading'>
			MirageVC :: <span class='pageSubHeading'>A Model-View-Controller framework for PHP</span>
			</p>

			<p>
			MirageVC is a lightweight Model-View-Controller framework written in PHP to aid developers in the creation of dynamic websites.  It is designed to be flexible and to accommodate site architectures of any design.  It has been successfully used with Apache2 and IIS6 using both PHP 5 and PHP 4.  The default Model class is wrapped around <a href='http://pear.php.net/manual/en/package.database.mdb2.php'>PEAR:MDB2</a>, but could easily be extended or replaced to use other database connectors.  Each page of a site employs unique Controller and View classes for application logic and presentation.  No templating language is specified, however <a href='http://smarty.php.net'>Smarty</a> or other tools could be used.
			</p>

			<p>
			MirageVC is a synthesis of architectures, and as such is modelled after the wonderful <a href='http://codeigniter.com'>Code Igniter</a>, as well as the ASG MVC, a framework developed by myself and others at the <a href='http://asg.citp.uga.edu'>Application Support Group</a> at the University of Georgia (which was itself modelled after the excellent <a href='http://www.onlamp.com/pub/a/php/2005/09/15/mvc_intro.html'>ideas outlined by Joe Stump</a> for <a href='http://bugs.joestump.net/trac.cgi/framework'>his Framework</a>).
			</p>
			</div>


			<div class='pageSection'>
			<p class='pageHeading'>Downloads</p>
			<p>
			Get the latest svn revision <a href='http://sourceforge.net/projects/miragevc'>from Sourceforge</a>:
			</p> 

			<p style='margin-left:10px'> 
			<code>svn co https://miragevc.svn.sourceforge.net/svnroot/miragevc/main miragevc</code>
			</p> 

			<p> 
			A beta release and example application will soon be available.
			</p>
			</div>



			<div class='pageSection'>
			<p class='pageHeading'>Documentation</p>
			<p>
			An example application and tutorial will be available shortly.  <a href='docs'>API documentation</a> generated with PhpDocumentor is also available.
			</p>
			</div>
			" ;

			include( PRESENTATION_RESOURCES_DIR . "/html/docFoot.php" ) ;

		}

	}

?>

