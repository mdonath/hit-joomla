#!/bin/bash
set -eux

SOURCE_DIR=../j5/work_directory/kampinfo/
TARGET_DIR=components

if ! [ -d $SOURCE_DIR ]; then
  echo "De componenten zijn nog niet gebouwd en/of staan niet in ${SOURCE_DIR}!"
  echo "Bouw deze eerst door in de root van de source-dir het commando 'ant' uit te voeren."
  exit 1;
fi

# curl -o /tmp/hit/ext/j4scoutingtc4_111.zip -Sl https://extensions.scouting.nl/templates/j4scoutingtc4_111.zip

cp ${SOURCE_DIR}/com_kampinfo-*.zip ${TARGET_DIR}/com_kampinfo.zip
cp ${SOURCE_DIR}/lib_kampinfo-*.zip ${TARGET_DIR}/lib_kampinfo.zip
cp ${SOURCE_DIR}/plg_content_kampinfo-*.zip ${TARGET_DIR}/plg_content_kampinfo.zip
cp ${SOURCE_DIR}/plg_content_socialmedia-*.zip ${TARGET_DIR}/plg_content_socialmedia.zip
cp ${SOURCE_DIR}/plg_task_updateinschrijvingen-*.zip ${TARGET_DIR}/plg_task_updateinschrijvingen.zip
cp ${SOURCE_DIR}/tpl_kampinfopreview-*.zip ${TARGET_DIR}/tpl_kampinfopreview.zip
cp ${SOURCE_DIR}/com_kampinfoimexport-*.zip ${TARGET_DIR}/com_kampinfoimexport.zip

cp ${SOURCE_DIR}/plg_console_group-*.zip ${TARGET_DIR}/plg_console_group.zip
cp ${SOURCE_DIR}/plg_console_kampinfo-*.zip ${TARGET_DIR}/plg_console_kampinfo.zip

podman build \
  --tag hit_dev_docker_j5 \
  .

