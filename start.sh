#!/bin/sh

echo "composer install -n" >> ~/.bashrc
echo "service cron start -f" >> ~/.bashrc
echo "mkdir -p /Application/Bin" >> ~/.bashrc
echo "chown www-data:www-data /Application/Data -R" >> ~/.bashrc
echo "cp /Application/vendor/r3m/framework/Bin/r3m.php /Application/Bin/R3m.php" >> ~/.bashrc
echo "php /Application/Bin/R3m.php bin r3m.io" >> ~/.bashrc
echo "r3m.io configure environment development" >> ~/.bashrc
echo "r3m.io ln /Application/Host/Script/Universeorange/Com /Application/Host/Script/Universeorange/Local" >> ~/.bashrc
echo "r3m.io ln /Application/Public/Js/Development/Priya /Application/Host/Script/Universeorange/Local/Public/Js/Priya" >> ~/.bashrc
echo "chown www-data:www-data /Application/Data -R" >> ~/.bashrc
echo "r3m.io configure host add 0.0.0.0 script.universeorange.local" >> ~/.bashrc
echo "r3m.io info all" >> ~/.bashrc
