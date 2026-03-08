FROM docker.io/joomla:5.4

RUN mkdir -p /tmp/hit/ext
RUN mkdir -p /tmp/hit/own

COPY scripts/setup_joomla.sh /tmp/hit/setup_joomla.sh

COPY components/j4scoutingtc4_111.zip /tmp/hit/ext/j4scoutingtc4_111.zip

COPY components/plg_console_group.zip /tmp/hit/own/plg_console_group.zip
COPY components/plg_console_kampinfo.zip /tmp/hit/own/plg_console_kampinfo.zip

COPY components/com_kampinfo.zip /tmp/hit/own/com_kampinfo.zip
COPY components/com_kampinfoimexport.zip /tmp/hit/own/com_kampinfoimexport.zip
COPY components/lib_kampinfo.zip /tmp/hit/own/lib_kampinfo.zip
COPY components/plg_content_kampinfo.zip /tmp/hit/own/plg_content_kampinfo.zip
COPY components/plg_content_socialmedia.zip /tmp/hit/own/plg_content_socialmedia.zip
COPY components/plg_task_updateinschrijvingen.zip /tmp/hit/own/plg_task_updateinschrijvingen.zip
COPY components/tpl_kampinfopreview.zip /tmp/hit/own/tpl_kampinfopreview.zip

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

