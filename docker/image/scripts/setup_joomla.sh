#!/bin/bash
set -e

JOOMLA_CLI="php cli/joomla.php"
EXTENSION_DIR=/tmp/hit/ext
OWN_EXTENSION_DIR=/tmp/hit/own

SQL_EXECUTE="mysql -h ${JOOMLA_DB_HOST} -u ${JOOMLA_DB_USER} --password=${JOOMLA_DB_PASSWORD} ${JOOMLA_DB_NAME} --ssl-verify-server-cert=false -e"


if [ -f /var/www/html/setup-hit-completed ]; then
    echo "Already setup for KampInfo: OK!"
    exit
fi

echo "========================="
echo "Changing website settings"
echo "========================="
echo "Hiding postinstall messages"
${SQL_EXECUTE} 'UPDATE joom_postinstall_messages SET enabled = 0 WHERE language_extension = "com_cpanel";'

cd /var/www/html

echo "Changing website timezone"
cat configuration.php | sed "s/\\\$offset = '.*'/\$offset = 'Europe\/Amsterdam'/" > configuration_with_timezone.tmp
mv configuration_with_timezone.tmp configuration.php
chown -R www-data:www-data configuration.php
chmod 444 configuration.php

echo "====================================="
echo "Installing external Joomla extensions"
echo "====================================="

echo "Installing Scouting TC4 Template"
${JOOMLA_CLI} extension:install --path=${EXTENSION_DIR}/j4scoutingtc4_111.zip

echo "Switching Default Site Template"
${SQL_EXECUTE} 'update joom_template_styles set home = 1 - home where client_id = 0;'


echo "================================"
echo "Installing HIT Joomla extensions"
echo "================================"

echo "Installing KampInfo and Im/Export Components"
${JOOMLA_CLI} extension:install --path=${OWN_EXTENSION_DIR}/com_kampinfo.zip
${JOOMLA_CLI} extension:install --path=${OWN_EXTENSION_DIR}/com_kampinfoimexport.zip

echo "Installing and enabling Library 'lib_kampinfo'"
${JOOMLA_CLI} extension:install --path=${OWN_EXTENSION_DIR}/lib_kampinfo.zip
${SQL_EXECUTE} 'update joom_extensions set enabled = 1 where type = "library" and element = "Kampinfo";'

echo "Installing and enabling Content plugin 'plg_content_kampinfo'"
${JOOMLA_CLI} extension:install --path=${OWN_EXTENSION_DIR}/plg_content_kampinfo.zip
${SQL_EXECUTE} 'update joom_extensions set enabled = 1 where type="plugin" and folder="content" and element = "kampinfo";'

echo "Installing and enabling Content plugin 'plg_content_socialmedia'"
${JOOMLA_CLI} extension:install --path=${OWN_EXTENSION_DIR}/plg_content_socialmedia.zip
${SQL_EXECUTE} 'update joom_extensions set enabled = 1 where type="plugin" and folder="content" and element = "socialmedia";'

echo "Installing and enabling Task plugin 'plg_task_updateinschrijvingen'"
${JOOMLA_CLI} extension:install --path=${OWN_EXTENSION_DIR}/plg_task_updateinschrijvingen.zip
${SQL_EXECUTE} 'update joom_extensions set enabled = 1 where type="plugin" and folder="task" and element = "updateinschrijvingen";'


echo "================================"
echo "Installing CLI Joomla extensions"
echo "================================"

echo "Installing and enabling CLI plugin 'plg_console_group'"
${JOOMLA_CLI} extension:install --path=${OWN_EXTENSION_DIR}/plg_console_group.zip
${SQL_EXECUTE} 'update joom_extensions set enabled = 1 where type="plugin" and folder="console" and element = "group";'

echo "Installing and enabling CLI plugin 'plg_console_kampinfo'"
${JOOMLA_CLI} extension:install --path=${OWN_EXTENSION_DIR}/plg_console_kampinfo.zip
${SQL_EXECUTE} 'update joom_extensions set enabled = 1 where type="plugin" and folder="console" and element = "kampinfo";'


echo "================================"
echo "Adding HIT User Groups and Users"
echo "================================"

echo "Creating HIT User Groups"
${JOOMLA_CLI} group:add --name="KampInfo" --parent="Author"
${JOOMLA_CLI} group:add --name="HIT Alphen" --parent="KampInfo"
${JOOMLA_CLI} group:add --name="HIT Dwingeloo" --parent="KampInfo"
${JOOMLA_CLI} group:add --name="HIT Harderwijk Kamp" --parent="KampInfo"
${JOOMLA_CLI} group:add --name="HIT Harderwijk" --parent="HIT Harderwijk Kamp"
${JOOMLA_CLI} group:add --name="HIT Heerenveen Kamp" --parent="KampInfo"
${JOOMLA_CLI} group:add --name="HIT Heerenveen" --parent="HIT Heerenveen Kamp"
${JOOMLA_CLI} group:add --name="HIT Nijmegen Kamp" --parent="KampInfo"
${JOOMLA_CLI} group:add --name="HIT Nijmegen" --parent="HIT Nijmegen Kamp"
${JOOMLA_CLI} group:add --name="HIT Ommen Kamp" --parent="KampInfo"
${JOOMLA_CLI} group:add --name="HIT Ommen" --parent="HIT Ommen Kamp"
${JOOMLA_CLI} group:add --name="HIT Zandvoort Kamp" --parent="KampInfo"
${JOOMLA_CLI} group:add --name="HIT Zandvoort" --parent="HIT Zandvoort Kamp"
${JOOMLA_CLI} group:add --name="HIT Zeeland Kamp" --parent="KampInfo"
${JOOMLA_CLI} group:add --name="HIT Zeeland" --parent="HIT Zeeland Kamp"


echo "Enabling admin-login for group KampInfo"
SQL="update joom_assets set rules = replace(json_insert(rules, concat('$.\\\"core.login.admin\\\".\"', (select id from joom_usergroups where title='KampInfo'), '\"'), 1), ' ', '') where id=1;"
${SQL_EXECUTE} "$SQL"

echo "Enabling core.manage for group KampInfo"
SQL="update joom_assets set rules = json_insert('{}', '$.\\\"core.manage\\\"', json_insert('{}', concat('$.\\\"', (select id from joom_usergroups where title='KampInfo'), '\"'), 1)) where name='com_kampinfo';"
${SQL_EXECUTE} "$SQL"


echo "Creating HIT Test Users"
${JOOMLA_CLI} user:add --username=hitalphen --name="C-team HIT Alphen" --password="hithithithit" --usergroup="HIT Alphen" --email="noreply+hitalphen@hit.scouting.nl"
${JOOMLA_CLI} user:add --username=hitdwingeloo --name="C-team HIT Dwingeloo" --password="hithithithit" --usergroup="HIT Dwingeloo" --email="noreply+hitdwingeloo@hit.scouting.nl"
${JOOMLA_CLI} user:add --username=hitharderwijk --name="C-team HIT Harderwijk" --password="hithithithit" --usergroup="HIT Harderwijk" --email="noreply+hitharderwijk@hit.scouting.nl"
${JOOMLA_CLI} user:add --username=hitheerenveen --name="C-team HIT Heerenveen" --password="hithithithit" --usergroup="HIT Heerenveen" --email="noreply+hitheerenveen@hit.scouting.nl"
${JOOMLA_CLI} user:add --username=hitnijmegen --name="C-team HIT Nijmegen" --password="hithithithit" --usergroup="HIT Nijmegen" --email="noreply+hitnijmegen@hit.scouting.nl"
${JOOMLA_CLI} user:add --username=hitommen --name="C-team HIT Ommen" --password="hithithithit" --usergroup="HIT Ommen" --email="noreply+hitommen@hit.scouting.nl"
${JOOMLA_CLI} user:add --username=hitzandvoort --name="C-team HIT Zandvoort" --password="hithithithit" --usergroup="HIT Zandvoort" --email="noreply+hitzandvoort@hit.scouting.nl"
${JOOMLA_CLI} user:add --username=hitzeeland --name="C-team HIT Zeeland" --password="hithithithit" --usergroup="HIT Zeeland" --email="noreply+zeeland@hit.scouting.nl"

echo Change owner of added files to www-data
chown -R www-data:www-data .

echo "Marking container as being setup"
touch /var/www/html/setup-hit-completed
