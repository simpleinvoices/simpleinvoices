# Simple Invoices

[![Join the chat at https://gitter.im/simpleinvoices/simpleinvoices](https://badges.gitter.im/simpleinvoices/simpleinvoices.svg)](https://gitter.im/simpleinvoices/simpleinvoices?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
Making invoicing rock since '05.

## Cloning
SimpleInvoices using gitsubmodules to load the Zend Framework.  
When cloning the repository use the `--recursive` option to ensure that Zend is retrieved.  
`git clone --recursive git@github.com:simpleinvoices/simpleinvoices.git`

If a [non-release download](https://github.com/simpleinvoices/simpleinvoices/archive/master.zip) of the SI code is taken from GitHub (instead of a recursive repo clone), then download the [Zend v1.11 library](https://github.com/dmelo/Zend-1.11/archive/27d7f1b3f45a436a9c795881db1d41689b8f9224.zip) manually and expand it into the `library/Zend` folder.

## Downloads
* Bleeding Edge Code: [SI Git Master](http://github.com/simpleinvoices/simpleinvoices/archive/master.zip) (.zip)
* Latest Beta Release: [2013.1 beta 8](https://github.com/simpleinvoices/simpleinvoices/archive/2013.1.beta.8.tar.gz) (tar.gz)
* Latest Stable Release: [2011.1](https://bitbucket.org/simpleinvoices/simpleinvoices/downloads/simpleinvoices.2011.1.zip) (.zip)

## Documentation
* Installation: http://simpleinvoices.org/install
* Frequently Asked Questions: http://simpleinvoices.org/wiki/faqs
* Help: http://simpleinvoices.org/help

## Schema
* The Entity Relationship Diagram for SI is available in the <b>databases/mysql folder</b>
* [ERD Schema with Primary and Foreign Keys](https://github.com/apmuthu/simpleinvoices/raw/master/databases/mysql/SI_Schema_2013.1.beta.5.1_PKFK.png)

## About
* Simple Invoices is released under the GPL v3 license - refer license.txt for details
* For installation instructions refer: http://simpleinvoices.org/install
* For any other help or comments jump on our website or post on the forum at http://simpleinvoices.org/forum

## Get Involved
* Developer Discussion: [Simple Invoices Google+ Community](https://plus.google.com/communities/102476804981627142204)
* Developer Mailing List: [Simple Invoices Google Groups](https://groups.google.com/forum/#!forum/simpleinvoices)

We also have a mailing list for tracking commit activity. This is hosted on Google Groups. You can find it here: https://groups.google.com/forum/?fromgroups#!forum/simpleinvoices-trac

* [SI on Docker](https://github.com/justinkelly/docker-simple-invoices)

## Translations
We are proud that SimpleInvoices is currently available in 41 different languages but we would love for that number to be even bigger!

If you would like to help translate SimpleInvoices into your language check out our [translation project](https://www.transifex.com/projects/p/SimpleInvoices/). Download updated and additional languages only if logged in there.

For more information refer: [simpleinvoices.org/translate](http://www.simpleinvoices.org/translate)


## Reporting Bugs
Please use the issue tracker on GitHub when reporting bugs.
https://github.com/simpleinvoices/simpleinvoices/issues

**Developers**: There are still a number of open issues on the old Google Code issue tracker if you are looking for something to fix. http://code.google.com/p/simpleinvoices/issues/list

## Known Issues
* **Heart Internet Users** -- There is currently an issue with exporting invoices to PDF. Images are not currently being rendered in the PDF. We are working on resolving this issue but unfortunately we do not yet have a fix. We recommend that you use a different hosting service if you need to export PDF invoices.
