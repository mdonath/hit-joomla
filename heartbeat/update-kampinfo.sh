#!/bin/bash

DATUM=`date +"%F %R"`
PASSWORD=dit-is-niet-het-goede-wachtwoord

CONTENT=$(wget "https://hit.scouting.nl/index.php?option=com_kampinfo&task=update.download&secret=$PASSWORD" -q -O - )

echo $DATUM $CONTENT  >> log.txt

if [ "$CONTENT" != "OK" ];
then
	cat log.txt | mail -s "KampInfo update gefaald op $DATUM" martijn.donath@scouting.nl
fi
