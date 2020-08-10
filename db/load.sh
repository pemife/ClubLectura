#!/bin/sh

BASE_DIR=$(dirname "$(readlink -f "$0")")
if [ "$1" != "test" ]; then
    psql -h localhost -U clublectura -d clublectura < $BASE_DIR/clublectura.sql
fi
psql -h localhost -U clublectura -d clublectura_test < $BASE_DIR/clublectura.sql
