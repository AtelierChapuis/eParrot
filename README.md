# eParrot
An eParrot that senses the temperature and converts to ABV. Display results on internal webpage. Raspberry Pi 3.

## Description:
asdf

## Installation:
From a fresh Raspbian installation:
1. LAMP Install

   1.1 Linux
    ```
    sudo bash
    apt-get update
    apt-get upgrade
    ```
   1.2 Apache
    ```
    apt-get install -y apache2 apache2-doc apache2-utils
    systemctl enable apache2.service
    systemctl start apache2.service
    ```
   1.3 Maria DB
    ```
    apt-get install -y mariadb-server
    mysql_secure_installation
    ```
   1.4 PHP
    ```
    apt-get install -y libapache2-mod-php
    apt-get install -y php php-apcu php-curl php-mbstring php-gd php-mysql php-opcache php-pear
    systemctl restart apache2.service
    php -v
    ```
   1.5 Python module for MariaDB
    ```
    apt-get install -y python-mysqldb
    ```
   1.6 Remove trashed installations (if needed)
    ```
    apt autoremove
    ```
   1.7 Clean-up
    ```
    apt-get clean
    ```
2. Setup MariaDB:
   
   2.1 Create database for web access
    ```
    CREATE DATABASE web;
    USE web;
    ```
   2.2 Create database table to store temperatures
    ```
    CREATE TABLE temperature (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      sampled_at TIMESTAMP NOT NULL,
      sensor_id VARCHAR(20) NOT NULL,
      sensor_val FLOAT(5,2) NOT NULL
    );
    ```
   2.3 Create database user for web access
    ```
    CREATE USER 'pi'@'localhost' IDENTIFIED BY '1q2w3e4r5t';
    GRANT INSERT,SELECT ON web.* TO 'pi'@'localhost';
    ```
4. Download the contents of this GitHub repo onto your Raspberry Pi 3, into the folder `/var/www/html`.
5. Change the temperature sensor's ID in the file `/var/www/html/index.php`.
6. Add cron job:
In a consol on the rasp pi, type `crontab -e`, add the following text, change '28-041780f40cff' to the correct sensor identity, save the file and exit your editor.
```
    */5 * * * * python /home/pi/Raspberry/Temperature/temperature_logger.py 28-041780f40cff
```

## Functionality:
* The temperature and webserver will start automatically when the Raspberry Pi boots (due to cron job).
* To read the results of the sensor, open a web browser of a device on the same LAN as the raspberry pi. Type the IP address of the pi, and the webpage should appear.
