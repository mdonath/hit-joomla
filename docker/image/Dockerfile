FROM docker.io/joomla:5.4

RUN mkdir -p /tmp/hit/ext
RUN mkdir -p /tmp/hit/own

COPY scripts/setup_joomla.sh /tmp/hit/setup_joomla.sh

COPY components/j4scoutingtc4_111.zip /tmp/hit/ext/j4scoutingtc4_111.zip
COPY components/plg_group_cli-1.x.zip /tmp/hit/own/plg_group_cli-1.x.zip
COPY components/plg_kampinfo_cli-1.x.zip /tmp/hit/own/plg_kampinfo_cli-1.x.zip

COPY components/com_kampinfo-3.x.zip /tmp/hit/own/com_kampinfo-3.x.zip
COPY components/com_kampinfoimexport-2.x.zip /tmp/hit/own/com_kampinfoimexport-2.x.zip
COPY components/lib_kampinfo-1.x.zip /tmp/hit/own/lib_kampinfo-1.x.zip
COPY components/plg_kampinfo-3.x.zip /tmp/hit/own/plg_kampinfo-3.x.zip
COPY components/plg_socialmedia-1.x.zip /tmp/hit/own/plg_socialmedia-1.x.zip
COPY components/plg_task_updateinschrijvingen-1.x.zip /tmp/hit/own/plg_task_updateinschrijvingen-1.x.zip
COPY components/tpl_kampinfopreview-1.x.zip /tmp/hit/own/tpl_kampinfopreview-1.x.zip

RUN { \
    echo 'upload_max_filesize = 16M'; \
    echo 'post_max_size = 16M'; \
    echo 'upload_tmp_dir = /var/www/html/tmp'; \
    } > /usr/local/etc/php/conf.d/max_sizes.ini && \
    \
    apt update && \
    apt install -y mariadb-client && \
    apt install -y libmagickwand-dev libmemcached-tools libzip5 && \
    \
    head -n -1 /entrypoint.sh > /tmp/entrypoint.sh && \
    echo "/tmp/hit/setup_joomla.sh" >> /tmp/entrypoint.sh && \
    tail -1 /entrypoint.sh >> /tmp/entrypoint.sh && \
    mv /tmp/entrypoint.sh /entrypoint.sh && \
    chmod +x /entrypoint.sh && \
    \
    chmod +x /tmp/hit/setup_joomla.sh


ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]

