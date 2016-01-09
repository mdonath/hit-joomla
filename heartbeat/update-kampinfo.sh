#!/bin/bash

#
# crontab -e:
#
# 0,30 06-23 * * * /home/pi/update-kampinfo.sh
# 0 00-06 * * * /home/pi/update-kampinfo.sh

DATUM=`date +"%F %R"`
PASSWORD=dit-is-niet-het-goede-wachtwoord
SENDTO=martijn.donath@scouting.nl

CONTENT=$(wget "https://hit.scouting.nl/index.php?option=com_kampinfo&task=update.download&secret=$PASSWORD" -q -O - )

echo $DATUM $CONTENT  >> log.txt

if [ "$CONTENT" != "OK" ];
then
	cat log.txt | mail -s "KampInfo update gefaald op $DATUM" $SENDTO
fi
