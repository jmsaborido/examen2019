#!/bin/sh

BASE_DIR=$(dirname "$(readlink -f "$0")")
if [ "$1" != "test" ]; then
    psql -h localhost -U examen2019 -d examen2019 < $BASE_DIR/examen2019.sql
fi
psql -h localhost -U examen2019 -d examen2019_test < $BASE_DIR/examen2019.sql
