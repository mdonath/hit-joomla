#!/bin/bash
set -e

if [ -f /var/www/html/setup-hit-completed ]; then
    echo "Already setup for KampInfo: OK!"
    exit
fi

echo "Copying prepared configuration.php"
mv /tmp/hit/configuration.php /var/www/html
chown -R www-data:www-data /var/www/html/configuration.php


echo "Modifying databasescript with new prefix"
sed -i 's/#_/j/g' /var/www/html/installation/sql/mysql/base.sql
sed -i 's/#_/j/g' /var/www/html/installation/sql/mysql/extensions.sql
sed -i 's/#_/j/g' /var/www/html/installation/sql/mysql/supports.sql


echo "Running Joomla install SQL with modified databasescripts"
mysql -h"$JOOMLA_DB_HOST" -u"$JOOMLA_DB_USER" --password="$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" < /var/www/html/installation/sql/mysql/base.sql
mysql -h"$JOOMLA_DB_HOST" -u"$JOOMLA_DB_USER" --password="$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" < /var/www/html/installation/sql/mysql/extensions.sql
mysql -h"$JOOMLA_DB_HOST" -u"$JOOMLA_DB_USER" --password="$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" < /var/www/html/installation/sql/mysql/supports.sql


echo "Adding admin user"
mysql -h"$JOOMLA_DB_HOST" -uroot --password="$MARIADB_ROOT_PASSWORD" "$JOOMLA_DB_NAME" < /tmp/hit/add_superuser.sql


echo "Deleting installation directory"
rm -rf /var/www/html/installation


echo "Installing extra Joomla extensions"
cd /var/www/html/cli
php joomla.php extension:install --path=/tmp/hit/ext/pkg_j4scoutingtc4.zip
php joomla.php extension:install --path=/tmp/hit/ext/j4scoutingtc4_105.zip

chown -R www-data:www-data /var/www/html/templates
chown -R www-data:www-data /var/www/html/modules/mod_scouting*
chown -R www-data:www-data /var/www/html/media/templates/site/j4scoutingtc4


echo "Hiding postinstall messages"
mysql -h"$JOOMLA_DB_HOST" -u"$JOOMLA_DB_USER" --password="$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" -e 'UPDATE j_postinstall_messages SET enabled = 0 WHERE language_extension = "com_cpanel";'


echo "Switching Default Site Template"
mysql -h"$JOOMLA_DB_HOST" -u"$JOOMLA_DB_USER" --password="$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" -e 'update j_template_styles set home = 1 - home where client_id = 0;'


echo "Installing and enabling CLI plugin 'group:add'"
php joomla.php extension:install --path=/tmp/hit/own/plg_group_cli-1.0.zip
mysql -h"$JOOMLA_DB_HOST" -u"$JOOMLA_DB_USER" --password="$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" -e 'update j_extensions set enabled = 1 where element = "group_cli";'


echo "Installing KampInfo and Im/Export Components"
php joomla.php extension:install --path=/tmp/hit/own/com_kampinfo-2.0.0.zip
php joomla.php extension:install --path=/tmp/hit/own/com_kampinfoimexport-2.0.0.zip

echo "Installing and enabling Content plugin 'plg_kampinfo'"
php joomla.php extension:install --path=/tmp/hit/own/plg_kampinfo-1.0.zip
mysql -h"$JOOMLA_DB_HOST" -u"$JOOMLA_DB_USER" --password="$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" -e 'update j_extensions set enabled = 1 where element = "kampinfo";'


echo "Creating HIT User Groups"
php joomla.php group:add --name="KampInfo" --parent="Author"
php joomla.php group:add --name="HIT Alphen" --parent="KampInfo"
php joomla.php group:add --name="HIT Dwingeloo" --parent="KampInfo"
php joomla.php group:add --name="HIT Harderwijk Kamp" --parent="KampInfo"
php joomla.php group:add --name="HIT Harderwijk" --parent="HIT Harderwijk Kamp"
php joomla.php group:add --name="HIT Heerenveen Kamp" --parent="KampInfo"
php joomla.php group:add --name="HIT Heerenveen" --parent="HIT Heerenveen Kamp"
php joomla.php group:add --name="HIT Mook Kamp" --parent="KampInfo"
php joomla.php group:add --name="HIT Mook" --parent="HIT Mook Kamp"
php joomla.php group:add --name="HIT Ommen Kamp" --parent="KampInfo"
php joomla.php group:add --name="HIT Ommen" --parent="HIT Ommen Kamp"
php joomla.php group:add --name="HIT Zeeland Kamp" --parent="KampInfo"
php joomla.php group:add --name="HIT Zeeland" --parent="HIT Zeeland Kamp"
php joomla.php group:add --name="HIT Zeewolde Kamp" --parent="KampInfo"
php joomla.php group:add --name="HIT Zeewolde" --parent="HIT Zeewolde Kamp"


echo "Enabling admin-login for group KampInfo"
SQL="update j_assets set rules = replace(json_insert(rules, concat('$.\\\"core.login.admin\\\".\"', (select id from j_usergroups where title='KampInfo'), '\"'), 1), ' ', '') where id=1;"
mysql -h"$JOOMLA_DB_HOST" -u"$JOOMLA_DB_USER" --password="$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" -e "$SQL"

echo "Enabling core.manage for group KampInfo"
SQL="update j_assets set rules = json_insert('{}', '$.\\\"core.manage\\\"', json_insert('{}', concat('$.\\\"', (select id from j_usergroups where title='KampInfo'), '\"'), 1)) where name='com_kampinfo';"
mysql -h"$JOOMLA_DB_HOST" -u"$JOOMLA_DB_USER" --password="$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" -e "$SQL"


echo "Creating Test Users"
php joomla.php user:add --username=hitalphen --name="C-team HIT Alphen" --password="hithithithit" --usergroup="HIT Alphen" --email="noreply+hitalphen@hit.scouting.nl"
php joomla.php user:add --username=hitdwingeloo --name="C-team HIT Dwingeloo" --password="hithithithit" --usergroup="HIT Dwingeloo" --email="noreply+hitdwingeloo@hit.scouting.nl"
php joomla.php user:add --username=hitharderwijk --name="C-team HIT Harderwijk" --password="hithithithit" --usergroup="HIT Harderwijk" --email="noreply+hitharderwijk@hit.scouting.nl"
php joomla.php user:add --username=hitheerenveen --name="C-team HIT Heerenveen" --password="hithithithit" --usergroup="HIT Heerenveen" --email="noreply+hitheerenveen@hit.scouting.nl"
php joomla.php user:add --username=hitmook --name="C-team HIT Mook" --password="hithithithit" --usergroup="HIT Mook" --email="noreply+hitmook@hit.scouting.nl"
php joomla.php user:add --username=hitommen --name="C-team HIT Ommen" --password="hithithithit" --usergroup="HIT Ommen" --email="noreply+hitommen@hit.scouting.nl"
php joomla.php user:add --username=hitzeeland --name="C-team HIT Zeeland" --password="hithithithit" --usergroup="HIT Zeeland" --email="noreply+zeeland@hit.scouting.nl"
php joomla.php user:add --username=hitzeewolde --name="C-team HIT Zeewolde" --password="hithithithit" --usergroup="HIT Zeewolde" --email="noreply+hitzeewolde@hit.scouting.nl"

echo "Marking container as being setup"
touch /var/www/html/setup-hit-completed
