#!/bin/sh

if [ "$1" = "travis" ]; then
    psql -U postgres -c "CREATE DATABASE clublectura_test;"
    psql -U postgres -c "CREATE USER clublectura PASSWORD 'clublectura' SUPERUSER;"
else
    sudo -u postgres dropdb --if-exists clublectura
    sudo -u postgres dropdb --if-exists clublectura_test
    sudo -u postgres dropuser --if-exists clublectura
    sudo -u postgres psql -c "CREATE USER clublectura PASSWORD 'clublectura' SUPERUSER;"
    sudo -u postgres createdb -O clublectura clublectura
    sudo -u postgres psql -d clublectura -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    sudo -u postgres createdb -O clublectura clublectura_test
    sudo -u postgres psql -d clublectura_test -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    LINE="localhost:5432:*:clublectura:clublectura"
    FILE=~/.pgpass
    if [ ! -f $FILE ]; then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE; then
        echo "$LINE" >> $FILE
    fi
fi
