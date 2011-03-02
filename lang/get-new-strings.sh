#!/bin/bash

if [ "$1" = "" ]; then
    echo -n 'usage: '
    echo -n `/bin/basename $0`
    echo ' <your language code>'
    exit
fi


/bin/cat en-gb/lang.php | grep LANG | cut -d= -f1 > /tmp/lang.en.tmp
/bin/cat $1/lang.php | grep LANG | cut -d= -f1 > /tmp/lang.$1.tmp
/usr/bin/diff -C 1 /tmp/lang.en.tmp /tmp/lang.$1.tmp
rm -f /tmp/lang.en.tmp /tmp/lang.$1.tmp
