<< Simple Invoices 																>>
<< this modification has been made to allow multiple instances w.o. the need of >>
<< replicating the full library every time.										>>
<<																				>>
<< basis for this file was:  simpleinvoices.2010.2.update-1.zip					>>
<< version 1: changed file structure											>>
<< version 2: changed static reference to index tabel to dynamic				>>
<<                       														>>
<< The rights and licence of the original owner has not changed.				>>
<< use it at you own risk!!					      								>>
<<																				>>
<< Albert Drent																	>>
<< aducom software																>>

This version is not fully tested yet and requires that you do some things manually.

<< Basic installation >>

This is as documented by the original code. However since the filestructure has changed you need to:


set directory rights tmp and tmp subdirs in the system directory to 777
you will find the config etc. in the system directory.

The index.htm will forward the page automatically to system/index.php

<< multiple installation >>

Changes where made to be able to install multiple instances w.o. the need of multiple installations
of the libraries which are quite large. More can be moved as there are still some libs required
in the system directory, however it a start.

Copy the system directory to your second installation like system_cust1 or whatever
Do an ordinary installation BUT use a different database OR a different table prefix
Create an alternative index.htm, i.e. index_cust1.html to forward to this installation or 
use a direct link to it. 

That should do it. BUT...

Sometime the installer refuses to create the tables. Then you need to create them by
yourself using phpMyAdmin. If you use another prefix (within define.php) then you MUST 
find-and-replace the prefix yourself. 

