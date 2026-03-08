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

cp ${SOURCE_DIR}/com_kampinfo-3.* ${TARGET_DIR}/com_kampinfo-3.x.zip
cp ${SOURCE_DIR}/lib_kampinfo-1.* ${TARGET_DIR}/lib_kampinfo-1.x.zip
cp ${SOURCE_DIR}/plg_kampinfo-3.* ${TARGET_DIR}/plg_kampinfo-3.x.zip
cp ${SOURCE_DIR}/plg_socialmedia-3.* ${TARGET_DIR}/plg_socialmedia-1.x.zip
cp ${SOURCE_DIR}/plg_task_updateinschrijvingen-1.* ${TARGET_DIR}/plg_task_updateinschrijvingen-1.x.zip
cp ${SOURCE_DIR}/tpl_kampinfopreview-1.* ${TARGET_DIR}/tpl_kampinfopreview-1.x.zip
cp ${SOURCE_DIR}/com_kampinfoimexport-2.* ${TARGET_DIR}/com_kampinfoimexport-2.x.zip

cp ${SOURCE_DIR}/plg_group_cli-1.* ${TARGET_DIR}/plg_group_cli-1.x.zip
cp ${SOURCE_DIR}/plg_kampinfo_cli-1.* ${TARGET_DIR}/plg_kampinfo_cli-1.x.zip

podman build \
  --tag hit_dev_docker_j5 \
  .

