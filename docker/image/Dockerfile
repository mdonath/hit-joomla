FROM joomla:4.4

RUN mkdir -p /tmp/hit/ext
RUN mkdir -p /tmp/hit/own

COPY scripts/setup_joomla.sh /tmp/hit/setup_joomla.sh
COPY configuration.tmp.php /tmp/hit/configuration.php
COPY scripts/add_superuser.sql /tmp/hit/add_superuser.sql

# TIJDELIJK!
COPY components/pkg_j4scoutingtc4.zip /tmp/hit/ext/pkg_j4scoutingtc4.zip
COPY components/j4scoutingtc4_105.zip /tmp/hit/ext/j4scoutingtc4_105.zip

COPY components/plg_group_cli-1.0.zip /tmp/hit/own/plg_group_cli-1.0.zip
COPY components/plg_kampinfo-1.0.zip /tmp/hit/own/plg_kampinfo-1.0.zip
COPY components/com_kampinfo-2.0.0.zip /tmp/hit/own/com_kampinfo-2.0.0.zip
COPY components/com_kampinfoimexport-2.0.0.zip /tmp/hit/own/com_kampinfoimexport-2.0.0.zip


RUN { \
    echo 'upload_max_filesize = 16M'; \
    echo 'post_max_size = 16M'; \
    echo 'upload_tmp_dir = /var/www/html/tmp'; \
    } > /usr/local/etc/php/conf.d/max_sizes.ini && \
    \
    apt update && apt install -y mariadb-client && \
    apt install -y libmagickwand-dev libmemcached-tools libzip4 && \
    \
    head -n -1 /entrypoint.sh > /tmp/entrypoint.sh && \
    echo "/tmp/hit/setup_joomla.sh" >> /tmp/entrypoint.sh && \
    tail -1 /entrypoint.sh >> /tmp/entrypoint.sh && \
    mv /tmp/entrypoint.sh /entrypoint.sh && \
    chmod +x /entrypoint.sh && \
    \
    chmod +x /tmp/hit/setup_joomla.sh
    # chmod +x /tmp/hit/setup_joomla.sh && \
    # curl -o /tmp/hit/ext/pkg_j4scoutingtc4.zip -Sl https://extensions.scouting.nl/templates/pkg_j4scoutingtc4.zip && \
    # curl -o /tmp/hit/ext/j4scoutingtc4_105.zip -Sl https://extensions.scouting.nl/templates/j4scoutingtc4_105.zip

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]

