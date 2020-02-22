#!/bin/sh

[ "$1" = "test" ] && BD="_test"
psql -h localhost -U examen2019 -d examen2019$BD
