build: composer

composer: clean
	#Get composer
	@@curl -s http://getcomposer.org/installer | php
	#Installing composer dependencies
	@@./composer.phar install
	
clean:
	#Removing build artifacts
	@@rm -r -f vendor
	@@rm -f composer.lock composer.phar

composerupdate:
	#Update composer
	@@./composer.phar update

composerinstall:
	#Installing composer dependencies
	@@./composer.phar install