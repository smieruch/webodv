#!/bin/bash



#------go to webodv -------------------------#
cd /var/www/html/webodv


#------downloads-----------------------------#
if [ ! -d "public/downloads" ]
then
    mkdir -p public/downloads
fi
chown -R woody public
chgrp -R www-data public
chmod 775 public/downloads

#-----bootstrap and storage-----------------#
chgrp www-data -R storage/ bootstrap/
chmod 775 -R storage/ bootstrap/

#-----odv_data------------------------------#
mkdir storage/app/default
chown -R woody storage/app/default
chgrp -R www-data storage/app/default
chmod -R 775 storage/app/default


#-------pid.txt for Start_wsODV.bash-------#
if [ ! -f "public/pid.txt" ]
then
    touch public/pid.txt
fi
chown woody public/pid.txt



# PKTenv
if [ -f storage/app/settings/settings_webodv/PKTenv ]
then
    cp storage/app/settings/settings_webodv/PKTenv /var/www/html/webodv/.env
fi

# wsODV_ProxyPass
if [ -f storage/app/settings/settings_webodv/"$proxy_ws" ]
   then
       cp storage/app/settings/settings_webodv/"$proxy_ws" /etc/apache2/sites-available/wsODV_ProxyPass.txt
fi

php artisan config:cache

#------migrate db and seed--------------------#
echo "wait until db is ready!"
#------wait until db is ready----------------#

#check if dbs exist
db1=`nc -z -v -w30 db 3306 2>&1 | awk '{print}'`
echo "check db1"
echo $db1
db1_check=`echo $db1 | awk '/Unknown/{print}'`


db2=`nc -z -v -w30 dbodv 3306 2>&1 | awk '{print}'`
echo "check db2"
echo $db2
db2_check=`echo $db2 | awk '/Unknown/{print}'`

if [ "$db1_check" == "" ]
then
    until nc -z -v -w30 db 3306
    do
	echo "db not ready yet!"
	sleep 1
    done
fi

if [ "$db2_check" == "" ]
then
    until nc -z -v -w30 dbodv 3306
    do
	echo "db not ready yet!"
	sleep 1
    done
fi


#-----uncomment the migrations, seedings and queues if the Laravel setup has been finished----#
echo "now migrate db and seed"
php artisan migrate
php artisan db:seed --class=WsodvAvailable
#in a distributed system, where other webODV instances access a
#global user db and at the same time want to have
#internal (Docker) wsodv DB's we have to remove the respective
#migrations from the global db to allow migration of the other webODVs
php artisan delete:migrationDB
#create dummy user e.g. for auth.basic.once
php artisan create:dummyUser
#start worker for sending emails
php artisan queue:work &
#-----uncomment the migrations and seedings if the Laravel setup has been finished----#

#make Start_wsODV.bash executable
chmod +x app/ShellScripts/Start_wsODV.bash

#Monitor
chgrp www-data app/ShellScripts/Monitor.bash
chmod +x app/ShellScripts/Monitor.bash

#start cron
chmod 0644 /etc/crontab
touch /var/log/cron.log
service cron start


#-------run apache in foreground------------------------#
#-------that means, that this scripts does not end------#
/usr/sbin/apache2ctl -D FOREGROUND

