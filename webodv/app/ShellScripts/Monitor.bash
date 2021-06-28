#!/bin/bash
#
#
#usage: Monitor.bash 
#
#

#outpath=$1



#get top info
mem=`top -b -n 2 | grep buff | awk 'NR>1{print $8/$4*100}'`
cpu=`top -b -n 2 | grep Cpu | awk 'NR>1{print $2}'`

#date
#d=`date +"%F"`

#xtime=`date +"%T"`



#echo $result >> /var/www/html/Log/Monitor.txt
# echo "Cpu=" $cpu
# echo "Mem=" $mem

#echo $d $xtime $cpu $mem >> "$outpath""/"$d".txt"
echo $cpu "," $mem 

