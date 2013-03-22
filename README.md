Pegasus Framework
===============

Lightweight PHP-framework.


Installation
-----------
1. Setup virtual host

        <VirtualHost *:80>
            ServerName pegasus.<DOMAIN>
            ServerAlias pegasus.<DOMAIN>
            DocumentRoot "/path/to/project/folder/public"
                <Directory "/path/to/project/folder/public">
	    	        DirectoryIndex index.php
    		        AllowOverride All
		        Order allow,deny
		        Allow from all
		        Options -Indexes
               </Directory>
        </VirtualHost>

2. Run "make". This will take care of the project's one dependency; RedbeanPHP.
3. Copy App/Config/Local.dist.php to App/Config/Local.php. Setup your custom info in this file.
4. Define routes in Configuration and what they should point to. 
5. Create controllers and corresponding View for the route you have defined.
6. Done.
