#!/bin/bash

# cd /var/www/simpleinvoices/lang
mkdir -p ../newlangs
find ./ -maxdepth 1 -type d  -not -path "./" -not -path "./en_US" -printf '%P\n' | xargs -I file echo 'php -q lang_insert.php file > ../newlangs/lang_file.php' | bash
tar -czf newlangs.tar.gz ../newlangs
rm -rf ../newlangs
