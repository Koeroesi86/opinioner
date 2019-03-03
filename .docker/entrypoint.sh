#!/bin/sh

#chown -R www-data:www-data /src
chmod -R 777 /src

apachectl -D FOREGROUND