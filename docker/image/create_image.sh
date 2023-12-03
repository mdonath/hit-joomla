#!/bin/bash
set -e

SOURCE_DIR=../j4/work_directory/kampinfo/
TARGET_DIR=components

if ! [ -d $SOURCE_DIR ]; then
  echo "De componenten zijn nog niet gebouwd en/of staan niet in ${SOURCE_DIR}!"
  echo "Bouw deze eerst door in de root van de source-dir het commando 'ant' uit te voeren."
  exit 1;
fi

cp ${SOURCE_DIR}/com_kampinfo-2.* ${TARGET_DIR}/com_kampinfo-2.0.0.zip
cp ${SOURCE_DIR}/com_kampinfoimexport-2.* ${TARGET_DIR}/com_kampinfoimexport-2.0.0.zip
cp ${SOURCE_DIR}/plg_kampinfo-1.* ${TARGET_DIR}/plg_kampinfo-1.0.zip
cp ${SOURCE_DIR}/plg_group_cli-1.* ${TARGET_DIR}/plg_group_cli-1.0.zip

docker build \
  --tag hit_dev_docker \
  .

