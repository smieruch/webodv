
echo `whoami` > /var/www/html/webodv/storage/logs/kill.txt
PIDs=$1
kill $PIDs
