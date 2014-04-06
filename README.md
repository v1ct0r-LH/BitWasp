BitWasp
===

```
Project Forums: http://bit-wasp.org
Test site: http://test.bit-wasp.org
Facebook page: https://facebook.com/BitWasp
```

BitWasp is an open source PHP project which allows anyone to set up a secure bitcoin marketplace independent of other centralized services.

It features multi-signature transactions, meaning no live wallet to steal. Users can also recover their funds from the site if it goes offline.

BitWasp is not production ready
===

This project is very much under development, and not yet ready for an alpha. Please be aware the project has not yet underdone extensive security testing and the code is liable to change. Please download and test the code by all means, but don't complain if it does something unexpected.


Installation
===
For the most up-to-date information on how to install BitWasp, please see here: http://bit-wasp.org/index.php/topic,28.0.html
```
TODO

update the doc for using with nginx
```


Proposed install guide. Please review this!

Installing Bitwasp
Installation instructions for BitWasp can be found here. BitWasp is being developed on a Debian system, so I'll include some commands to try get your system ready to run the code. BitWasp has been developed on apache2 and these instructions assume you have it installed, as well as a mysql server. This installation guide will cover all steps after this, such as BitWasp's other dependencies.

Revision: 3/4/2014. Added multisig code, removed live wallet functionality!

Dependencies
System Packages
Firstly, you need curl for the bitcoin daemon to use for callback scripts. Whenever the bitcoin daemon learns of a new transaction or block, it needs to tell the marketplace. PHP also needs the cURL extension a well to load information from API's (such as bitcoin exchange rates). The PHP GD extension library is used to process image resizing/converting. The marketplace has code to work with Image Magick also. You also need the GMP extension. The PHP-ECC library requires this (as well as another slower one, bmath. Don't bother with it. Just use GMP) to run.
Code: [Select]

sudo apt-get install build-essential curl php5-curl php5-dev php5-gd php5-imagick php5-gmp php5-mcrypt php5-mysql php-pear


GnuPG
GnuPG needs to be installed, as well as the gnupg extension for PHP. the PHP extension has a few dependencies which are available through apt-get. It needs to be built from source also.
Code: [Select]

sudo apt-get install gnupg libgpg-error-dev libassuan-dev


BitWasp has been tested with version 1.3.2 of gpgme, so for now I'd recommend downloading that. The second last step installs the gnupg extension.
Code: [Select]

wget ftp://ftp.gnupg.org/gcrypt/gpgme/gpgme-1.3.2.tar.bz2
tar xvjpf gpgme-1.3.2.tar.bz2
cd gpgme-1.3.2
./configure
make && sudo make install
sudo pecl install gnupg
sudo nano /etc/php5/apache2/php5.ini


Now that the extension is installed, add this setting to the very end of php.ini
Code: [Select]

extension=gnupg.so


Hit CTRL-X to exit, hit y to confirm, and restart apache2
Code: [Select]

sudo service apache2 reload


GPG's default home directory (for the apache user) is /var/www. Bitwasp will use /tmp instead, to be compatible with read-only filesystems. This is done in the code, so requires no further steps from you.

Bitcoin Daemon
Next you need to download the bitcoin binaries. This is the bitcoin daemon which will run in the background, tell your site about new transactions, and accepts and send payments. BitWasp has been tested on the 0.8.5 client. We recommend you set up a separate user to run the bitcoin binary, but it has no bearing on later configuration if you don't. Download the binaries from here (assuming you're using the prebuilt binaries,): http://sourceforge.net/projects/bitcoin/files/Bitcoin/bitcoin-0.8.5/bitcoin-0.8.5-linux.tar.gz/download

Add a bitcoin user:
Code: [Select]

sudo adduser bitcoin
sudo su bitcoin


Unzip the bitcoin binaries, and create a .bitcoin folder in your home directory to contain your bitcoin.conf
Code: [Select]

tar xvf bitcoin-0.8.5-linux.tar.gz
cd ~
mkdir .bitcoin
touch bitcoin.conf
nano bitcoin.conf


Configure your bitcoin.conf to something similar to this. This config file make the bitcoind open the JSON RPC interface, run on port 28332, only allow connections from localhost, and run on the testnet. You probably don't want to trust the code with real bitcoins yet!
Code: [Select]

rpcuser=bitcoinrpc
rpcpassword=this should be a ridiculously long password, something you don't need to remember as you can copy from this file later, and no one else can guess.
testnet=1
daemon=1
txindex=1
rpcport=28332
rpcconnect=127.0.0.1


We will now load the bitcoin daemon for the first time, and download the blockchain. This will take some time. As we are running with the txindex=1 flag, information about every transaction will be available to the client. This can take a day or two, depending on your machine! The above sample config file assumes you're running bitcoin in the testnet, as we do not encourage people to use bitwasp to accept payment yet.

We'll now copy bitcoind to your system path.

Code: [Select]

cd bitcoin-0.8.5-linux/bin/32/
sudo cp bitcoind /usr/bin/
bitcoind

You can track the progress as it updates the blockchain by issuing: bitcoind getinfo and looking at the block height. You can stop the daemon at any time by returning to this directory and running:
Code: [Select]

bitcoind stop


MySQL
Now you need to create a bitwasp user in the MySQL database. Log in as root and create the new user and database by issuing the following:
Code: [Select]

CREATE DATABASE bitwasp   
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_unicode_ci;
CREATE USER 'bitwasp'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON bitwasp.* to 'bitwasp'@'localhost';


We recommend that you set up an account on github so you can easily stay up to date with new code. We'll assume you already have git installed, it just makes managing the code much easier; no downloading new files, just issue a command and you're up to date. You just need to set up SSH keys to access your own account.
Change directory into your document root for the bitwasp site/vhost. Initialize a new github repository, and fetch the latest data:
Code: [Select]

cd /var/www/bitwasp
git clone git@github.com:Bit-Wasp/BitWasp


Now that you have the code, you need to configure the site.

Tidy URL's
If you want to remove index.php from the URL's, you'll need to modify the .htaccess file and CodeIgniters main config file in ./application/config/config.php. Edit .htaccess so that it has:
Code: [Select]

RewriteBase /bitwaspfolder/ 

You may need to enable the rewrite module:
Code: [Select]

sudo a2enmod rewrite
sudo service apache2 reload


If your website is http://example.com/bitwasp/index.php, the folder will be /bitwasp/. If it's just http://example.com/index.php, the folder is /.

Configure BitWasp
Copy ./application/config/config.php.sample to ./application/config/config.php, and set up the following two lines accordingly.
Code: [Select]

cp ./application/config/config.php.sample ./application/config/config.php 
nano ./application/config/config.php


If you are using short URL's, remove index.php from between the quotes.
Code: [Select]

$config['index_page'] = '';
$config['base_url'] = 'http://example.com/bitwasp/';




Now we need to set up your bitcoin and mysql credentials. Copy the sample files and simply fill in your credentials then.
Code: [Select]

cp ./application/config/bitcoin.php.sample ./application/config/bitcoin.php
nano ./application/config/bitcoin.php
cp ./application/config/database.php.sample ./application/config/database.php
nano ./application/config/database.php


Once this is done, you need to import the schema.sql file in the document root into your database. You can use phpMyAdmin for this, or do it from the command line. You will be prompted for your password, and this will complete the installation/configuration of BitWasp with MySQL.
Code: [Select]

mysql -u bitwasp -p "bitwasp" < ./schema.sql


Once the bitcoin daemon has loaded and is up to date, you need to restart it so that it interacts with the marketplace, logging relevant blocks and transactions.

Code: [Select]

bitcoind stop
nano ~/.bitcoin/bitcoin.conf


Add the following lines to your bitcoin.conf file:
Code: [Select]

blocknotify=curl http://localhost/index.php/callback/block/%s
alertnotify=curl http://localhost/index.php/callback/alert

Now restart bitcoin:
Code: [Select]

 bitcoind 


From now on, your marketplace will maintain an up to date record of blocks it has seen, and scrape transactions paying to multi-signature addresses from the latest block in the chain. As users need to be trusted to sign the transaction and pass it to the other user using the marketplace, any payments leaving multi-signature addresses will be compared to a hash of what's expected. If the users collude, and exclude the site from the fee, it will identify this and mark the order as improperly finalized, allowing the admin to decide if he should ban the users.

Autorun Scripts
You need to configure your crontab so that curl calls the autorun callback trigger. This is run every minute, and Bitwasp will check if any of the jobs need to be run.
Code: [Select]

*/1 * * * * curl http://localhost/index.php/callback/autorun
*/1 * * * * curl http://localhost/index.php/callback/process


You're Nearly Done!
At this point, you need to configure an administrator account. Register an 'admin' account using the form - the name is important. There must be an 'admin' account! Open up phpmyadmin or your usual SQL software, and alter the user_role of this user (in bw_users) to 'Admin'.

Next, you need to download and set up electrum. This is used to deterministically generate keys for order addresses, and registration fee addresses. Using electrum means you instantly have access to revenue earned though the site. It is advised to set up a separate wallet for this, otherwise people will see your personal transactions.
Code: [Select]

electrum -w bitwasp


Once you have safely stored your 12 word seed, copy the master public key, and log into Bitwasp. Visit the admin panel -> Bitcoin -> Edit, and enter your master public key.

At this point, the marketplace should be ready and in working order. There are no predefined accounts, so you'll need to create some yourself. Register a buyer user, and a vendor user, and get to testing.

You should add a GPG key to the admin account right away; see how to here: https://fedoraproject.org/wiki/Creating_GPG_Keys#Creating_GPG_Keys_Using_the_Command_Line

Tested Operating Systems
Bitwasp has been successfully installed on the following operating systems:
Debian 6.0
Debian 7.0
Ubuntu 12.10
If you succeed in installing Bitwasp on another system, please let us know!
« Last Edit: April 04, 2014, 05:27:33 pm by afk11 »



Support BitWasp's Development
===
All money from donations go to fund BitWasp's development, hosting, and bounties for bug's. 

Our Bitcoin Address: 19EkDTAaGWySZv1QsWxyWwYMZpo7jpvPYe

Anyone interested in contributing code or time to help with testing, please get in touch!

Features list: http://bit-wasp.org/index.php/topic,4.msg4.html#msg4
