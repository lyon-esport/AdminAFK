![Docker Automated build](https://img.shields.io/docker/cloud/automated/lyonesport/adminafk?style=flat-square)
![Docker Build Status](https://img.shields.io/docker/cloud/build/lyonesport/adminafk?style=flat-square)

AdminAFK will make life better for CS:GO Admins (plugin working with eBot <3)

**This tool uses :**

* [eBot](http://www.esport-tools.net/ebot/)
* [Bootstrap](https://getbootstrap.com/docs/4.0/getting-started/introduction/)
* [DataTables](https://datatables.net/)
* [Toornament API](https://www.toornament.com/)
* [Steam API](https://developer.valvesoftware.com/wiki/Steam_Web_API/)
* [SteamID API](https://steamid.eu/)

# Requirements
## Standard

* Linux, Windows with a web server
* MySQL 5.7.19 or newer
* eBot 3.1 or newer
* 5.29 > PHP < 7.0.24
* CURL 7.51 or newer
* SSL 1.0.2 or newer
* PDO with PDO MySQL driver

## Docker
* Docker-compose

# Get your API key

* [Toornament API](https://developer.toornament.com/v2/overview/get-started?_locale=en)
* [Steam API](https://steamcommunity.com/dev/apikey)
* [SteamID API](https://steamid.eu/steamidapi/)

# Install guide
## Standard

1. Download –> https://github.com/lyon-esport/AdminAFK
2. Extract the AdminAFK files
3. Import the file adminafk.sql on a new database.
4. Edit config/config.php with the good setting

## Docker
1. Download `docker-compose.yml` and `adminafk.sql`
2. Fill `ADMINAFK_SERVERNAME_EBOT` and `ADMINAFK_BASE_URL` on `docker-compose.yml` (if you use an other database server for adminafk you can delete the mysql container). Description of variables is available on the repository `config/config.php`
3. Run it with `docker-compose up -d`

# Usage guide

* Public content –> http://localhost/adminafk/index.php
* You can embed Public content for your own intranet by adding "?embed=1" like this : http://localhost/adminafk/pages/view_connect.php?embed=1
* Admin content –> http://localhost/adminafk/admin.php (default : admin/admin)
* AdminAFK setting –> http://localhost/adminafk/pages/setting.php
* You got an example of csv file here : https://github.com/lyon-esport/AdminAFK

# Licence

The code is under CeCILL license.

You can find all details here: http://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html

# Credits

Copyright © Lyon e-Sport, 2018

Contributor(s):

-Ortega Ludovic - ludovic.ortega@lyon-esport.fr
