#!/bin/bash
#
#
#
#
#------usage------------------------------------#
#
# Start_wsODV.bash 
#
#-----------------------------------------------#


odv_file_path=$1
settings=$2
homedir=$3
exepath=$4 #/var/www/html/odv_5.1.7beta_linux-amd64_ubuntu-18.04/bin_linux-amd64
virtual_display=$5 #enable
read_write=$6
port=$7
disable_exports=$8
auto_shutdown=$9
admin_password=${10}


#remove .access
if [ -f ${odv_file_path%%.odv}.Data/.access ]
   then
       rm ${odv_file_path%%.odv}.Data/.access
fi
#remove .agreement
if [ -f ${odv_file_path%%.odv}.Data/.agreement ]
   then
       rm ${odv_file_path%%.odv}.Data/.agreement
fi


#copy default file
cp $settings "$homedir"/.odv_settings_linux-amd64

#export libraries
export LD_LIBRARY_PATH=$exepath:$LD_LIBRARY_PATH

#----------------run wsODV on a virtual screen------------------------#
if [ "$virtual_display" == "enable" ]
then
    #check if display 102 is running
    D=`pgrep -a Xvfb`
    D=`echo $D  | awk '/102/{print}'`

    if [ "$D" == "" ]
    then
	Xvfb :102 -screen 0 3840x2160x24 &
    else
	dummy=1
    fi
    export DISPLAY=:102.0    
else
    export DISPLAY=:0
    dummy=1
fi
#----------------------------------------------------------------------#


#----------------------start wsODV--------------------------------#

if [ "$disable_exports" == "dummy" ]
then
    disable_exports=""
fi



#$exepath/odvws -view '$FullScreenMap$' -port $port -access $read_write -auto_shutdown $odv_file_path > /dev/null & echo $!

# echo $! prints out the PID
# parenthesis are needed to start odvws in the background and pipe stdout to grep
# grep waits until "collection ready" is printed to stdout
# write pid to file
if [ "$odv_file_path" == "dummy" ]
then
    #echo "dummy"
    $exepath/odvws -view '$FullScreenMap$' -port $port -access $read_write $auto_shutdown $disable_exports -admin_password $admin_password > /dev/null & echo $!
else
    #echo "no dummy"
    ($exepath/odvws -view '$FullScreenMap$' -port $port -access $read_write $auto_shutdown $disable_exports -admin_password $admin_password $odv_file_path & echo $! > pid.txt) | grep -q "collection ready"
    # cat pid
    cat pid.txt
fi


#-------------------------------------------------------------------#

