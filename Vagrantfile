# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "sternpunkt/jimmybox"
  config.vm.hostname = "koalacreek"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "private_network", ip: "192.168.56.10"
  config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=666"]


  def provisioned?(vm_name='default', provider='virtualbox')
    File.exist?(".vagrant/machines/#{vm_name}/#{provider}/action_provision")
  end

  if !provisioned? || ARGV.include?("provision")

    print("=======================\n- Create New Database -\n=======================\n")
    puts "Enter Database name: "
    database = STDIN.gets.chomp

    if database.empty?
      print("\033[0;33;42mNo database name entered, database will be created as `koalacreek`\033[0m\n")
      database = 'koalacreek'
    end

    if database.match(/\W/)
      abort("Database name can only contain alphanumeric characters")
    end

  end

  config.vm.provision "shell", inline: <<-SHELL
  sudo sed -i 's!DocumentRoot /var/www$!DocumentRoot /var/www/public!' /etc/apache2/sites-enabled/000-default.conf
  sudo service apache2 restart
  mysql -uroot -proot -e "create database #{database}" > /dev/null 2>&1
  sed -i "s!dbname=[a-zA-Z0-9_]*!dbname=#{database}!" /var/www/config/local.neon
  echo "Database config is stored in: local.neon"
  SHELL
end
