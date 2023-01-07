# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "sternpunkt/jimmybox"
  config.vm.hostname = "koalacreek"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "private_network", ip: "192.168.56.10"
  config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=666"]

  config.vm.provision "shell", inline: <<-SHELL
  sudo sed -i 's!DocumentRoot /var/www!DocumentRoot /var/www/public!' /etc/apache2/sites-enabled/000-default.conf
  sudo service apache2 restart
  mysql -uroot -proot -e "create database koalacreek" > /dev/null 2>&1
  SHELL
end
