#!/bin/bash
# Joomla extension auto installation
# Use that for development purpose, don't ever think of using it on a production website.
# The developer of that script won't endorse any problem you may encounter by using this script: Use it at your own risk!
# Please configure the three first params before using it.

# Usage :
# ./install.sh
# Will try to install every dir in the current dir
# ./install.sh directory
# Will try to install dir specified as agument
# ./install.sh dir1 dir2 ... dirn
# Will try to install dir1 to dirn

# Dependencies :
# curl - for website communication
# sgrep - for output parsing
# w3m - for a better output

# Enter here your installation information
# JOOMLAURL is the path to the administrator directory of your Joomla (don't forget the trailing slash)
# ADMINNAME is the username of an user who can install extension on the joomla
# ADMINPASS is the password of that user
JOOMLAURL=http://localhost/HIT/administrator/
ADMINNAME=admin
ADMINPASS=admin

# Don't modify anything past this point unless you know what you are doing!

# Get the Form token for Administrator Login
AUTHTOKEN=$(curl -c cookie.tmp -s $JOOMLAURL | grep -o -E '<input type="hidden" name="(.*)" value="1" />' | sed 's/<input type="hidden" name="\(.*\)" value="1" \/>/\1/')

# Login into administrator
URL="option=com_login&task=login&username=${ADMINNAME}&passwd=${ADMINPASS}&${AUTHTOKEN}=1&return=aW5kZXgucGhw";
OUTPUT=$(curl -c cookie.tmp --cookie cookie.tmp -s $JOOMLAURL -d $URL)

function JInstallZip {
	echo "Installing..."
	# Get a token for Installer form
	TOKEN=$(curl -c cookie.tmp --cookie cookie.tmp -s $JOOMLAURL -d "option=com_installer&view=install" | grep -o -E '<input type="hidden" name="(.*)" value="1" \/>' | sed 's/<input type="hidden" name="\(.*\)" value="1" \/>/\1/')

	# Launch Installation of the extensions in the current directory
	URL="option=com_installer&view=install&installtype=upload&task=install.install&${TOKEN}=1"
	OUTPUT=$(curl -c cookie.tmp --cookie cookie.tmp -F "install_package=@$1;type=application/zip" -F "installtype=upload" -F "task=install.install" -F "${TOKEN}=1" -s $JOOMLAURL\?option=com_installer&view=install)

	# Get any Error message the page may display
	# It may still need some improvement as it's still displaying "<li>" tags
	OUTPUTEND=$(curl -c cookie.tmp --cookie cookie.tmp -s $JOOMLAURL -d "option=com_installer&layout=default_message&tmpl=component")
	MESSAGE=$(echo $OUTPUTEND | sed 's/.*<dl id="system-message">\(.*\)<\/dl>.*/\1/g')

	ERROR=$(echo $MESSAGE | grep "error")

	if [ -n "$ERROR" ]; then
		echo -e "\e[0;31m$1 failed"
		echo $MESSAGE | sgrep -o"%r\n" '"<li>".."</li>"' | sed 's/<li>\(.*\)<\/li>/\1<br \/>/' | w3m -dump -T text/html
		echo -ne "\e[0;00m"
	else
		echo $1 installed
	fi

}

# If there is a first param set, it will install
if [ $1 ]
then
	for file in $@; do
		if [ -d $file ]; then
			rm $file.zip &> /dev/null
			zip -q -r $file $file
			JInstallZip $file
		else 
			JInstallZip $file
		fi
	done
else
	LATEST=`ls -1 com_kampinfo-0.* | tail -1`
	JInstallZip $LATEST
fi

