# SimpleInvoices
[Join the SimpleInvoices chat on Google+](https://plus.google.com/u/0/communities/102476804981627142204)

***Making invoicing rock since '05***

## Cloning
*Note: Cloning is not necessary unless you wish to contribute to the ongoing development and maintenance of SI.*
For versions prior to Fearless359 Release, SI uses **gitsubmodules** to load the Zend Framework.  
When cloning the repository use the `--recursive` option to ensure that Zend is retrieved.  
`git clone --recursive git@github.com:simpleinvoices/simpleinvoices.git`

For the Fearless359 releases, Zend Framework is incorporated in the download.

## Updating to Current Version
These instructions should work for all version updates. They protect your current install so that you can easily revert to it if a problem is encountered. Make sure that no one is accessing your SimpleInvoices application except you.

1. In SI, select the **Backup Database** option. This will save your database content in the **dataabase_backups** directory.
1. Log out of SI. 
1. Rename the current SI directory to add "_old" to the name. *Ex*: Rename **simpleinvoices** to be **simpleinvoices_old**.
1. [Download the zip file from fearless359 on github](https://github.com/fearless359/simpleinvoices)
1. Unzip the downloaded file into your document root directory. *Ex*: On **xampp** this would be the **htdocs** directory.
1. Rename the directory created for the unzipped data, to be what you were using. *Ex*: Say you had **simpleinvoices** and renamed it to **simpleinvoices_old** per previous step. So rename the unzipped directory **simpleinvoices-master** to be **simpleinvoices**.
1. Copy the new **config/config.php** file to be **config/custom.config.php**. Then update the database, email, etc. settings in the new **config/custom.config.php** file from the settings in your old **config/config.php** or **.ini** file. SI will see the **custom.config.php** file and use it rather than the **config.php** file. This is done so that future SI updates will not write over your settings.
1. Now access SI from your browser just as you did the previous version. It should automatically bring your database current and leave your user settings and passwords the same.

## Downloads
* Fearless359 Release - *Version 2017.2.0*: [fearless359/simpleinvoices](https://github.com/fearless359/simpleinvoices.zip) (.zip)

## Documentation
* [All discussions & help have moved to Google+](https://plus.google.com/u/0/communities/102476804981627142204)
* [Installation](https://github.com/fearless359/simpleinvoices/blob/master/readme.md)
* Frequently Asked Questions: ***Being Updated***

## Schema
* The Entity Relationship Diagram for SI is available in the **databases/mysql folder**
* [ERD Schema with Primary and Foreign Keys](https://github.com/apmuthu/simpleinvoices/raw/master/databases/mysql/SI_Schema_2013.1.beta.5.1_PKFK.png)

## About
* SimpleInvoices is released under the GPL v3 license - refer license.txt for details
* For installation instructions refer: ***Being Updated***
* For any other help or comments jump on our website or post on the forum at [Google+](https://plus.google.com/u/0/communities/102476804981627142204)

## Get Involved
* Developer Discussion: [SimpleInvoices Google+ Community](https://plus.google.com/communities/102476804981627142204)
* Developer Mailing List: [SimpleInvoices Google Groups](https://groups.google.com/forum/#!forum/simpleinvoices)

We also have a mailing list for tracking commit activity. This is hosted on Google Groups. You can find it here: https://groups.google.com/forum/?fromgroups#!forum/simpleinvoices-trac

## Translations
We are proud that SimpleInvoices is currently available in 41 different languages but we would love for that number to be even bigger!

If you would like to help translate SimpleInvoices into your language check out our [translation project](https://www.transifex.com/projects/p/SimpleInvoices/). Download updated and additional languages only if logged in there.

For more information refer: [simpleinvoices.group/translate](https://simpleinvoices.group/translate)

## Reporting Bugs
Please use the issue tracker on GitHub when reporting bugs.
* [Report Fearless359 version issues here](https://github.com/fearless359/simpleinvoices/issues)

**Developers**: There are still a number of open issues on the old Google Code issue tracker if you are looking for something to fix. http://code.google.com/p/simpleinvoices/issues/list

## Known Issues
* **Heart Internet Users** -- There is currently an issue with exporting invoices to PDF. Images are not currently being rendered in the PDF. We are working on resolving this issue but unfortunately we do not yet have a fix. We recommend that you use a different hosting service if you need to export PDF invoices.
