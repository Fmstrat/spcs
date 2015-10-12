# SPCS: Simple PHP Content Server for Calibre
SPCS, or Simple PHP Content Server, is a PHP based content server that reads directly from the Calibre metadata.db file.

Features include:

- Support in most major desktop, mobile and Kindle browsers
- Built-in multi-user login functionality allowing for direct book download to Kindle and Android devices
- “Send-to” functionality to support Whispersync with Amazon’s Personal Documents feature
- No need to have Calibre running
- Sortable results
- Cover display

## Download

Grab the latest zip from the <a href="https://github.com/Fmstrat/spcs/tree/master/builds">builds directory</a>.

##Usage/Installation

Installation is simple and straightforward:

- Extract the archive to any folder on a system running PHP. Ensure Pear Mail tools are installed.
- Edit /include/config.php to your liking.
- Ensure the metadata.db file AND the folder it resides in is writable by the Apache/IIS user.
- Visit https://<server>/<installfolder>/setup.php to install. This will create a new table (spcs) in your Calibre database with a user “admin” and password “password”.
- Visit https://<server>/<installfolder> and login as “admin” with password “password”.
- Go to settings to change password and Kindle email address.
- Other users can be set up by using a Sqlite editor like Sqlite Database Browser. Just add a row with a username and blank password, and change it on first login.

*NOTE: Downloading with “Basic Authentication” causes problems, thus the included login interface. However, we STRONGLY recommend you force HTTPS/SSL to secure your passwords. Also, this application has NOT undergone strict security testing.*

Please pose any questions or discussion to the thread at: http://www.mobileread.com/forums/showthread.php?t=203177


##Configuration

The following variables should be edited before running setup.php for the first time.

Sets the number of results per page:
```
$results_per_page = 20;
```

The location of your Calibre DB file (This can be a copy):
```
$calibre_db = "/files/eBooks/metadata.db";
```

The location of your books library (include trailing slash):
```
$books_folder = "/files/eBooks/";
```

Set this to the book type you wish to use (file extension, all lower case):
```
$book_type = "mobi";
```

The email address books are sent from (Ensure this is validated in your Kindle settings):
```
$from = "myemail@gmail.com";
```

The server to send emails through:
```
$config=array(
  'host'      => 'ssl://smtp.googlemail.com',
  'port'      => 465,
  'auth'      => true,
  'username'  => 'user',
  'password'  => 'password'
);
```

To-Do

- Multi-format book download/send
- Administration console for users
- Full security test

##Change Log

**v0.04**

- CSS fix for latest Android Chrome

**v0.03**

- Misc. bug fixes
- Added PDF backup for if primary format isn’t found but PDF is
- Added “Added” sort, for sorting by books modified or added latest
- Added “NEW” banner for books added or modified in past week

**v0.02**

- Kindle stylesheet fixes

**v0.01**

- Release
