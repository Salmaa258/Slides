$install_requirements = <<SCRIPT
echo ">>> installing Base Requirements"

#!/usr/bin/env bash

DBHOST=localhost
DBNAME=db_Presentaciones
ROOTPASSWD=root

# Usuario y contraseña
USER="usuari"
PASS="password1"

apt-get update
apt-get install vim curl build-essential python-software-properties git

debconf-set-selections <<< "mysql-server mysql-server/root_password password $ROOTPASSWD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $ROOTPASSWD"

# Instalar MySQL
apt-get -y install mysql-server

mysql -uroot -p$ROOTPASSWD -e "CREATE DATABASE $DBNAME"

# Crear un usuario y otorgar permisos
mysql -uroot -p$ROOTPASSWD -e "CREATE USER '$USER'@'%' IDENTIFIED BY '$PASS';"
mysql -uroot -p$ROOTPASSWD -e "GRANT ALL PRIVILEGES ON $DBNAME.* TO '$USER'@'%';"

# Actualizar el archivo de configuración de MySQL para permitir acceso remoto a la base de datos
sudo sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf

sudo service mysql restart

# Crear tablas en la base de datos
mysql -uroot -p$ROOTPASSWD $DBNAME <<EOF
CREATE TABLE Presentacions (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Títol VARCHAR(255),
    Descripció TEXT,
    Data_de_Creació TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Diapositives_Tipus (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Tipus ENUM('T', 'TC')
);

CREATE TABLE Diapositives (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    presentació_id INT,
    diapositives_tipus_id INT,
    FOREIGN KEY (presentació_id) REFERENCES Presentacions(ID),
    FOREIGN KEY (diapositives_tipus_id) REFERENCES Diapositives_Tipus(ID)
);
EOF


SCRIPT

Vagrant.configure("2") do |config|

  config.vm.box = "ubuntu/mantic64"

  config.vm.define "db-server" do |db|
      db.vm.network "private_network", ip: "172.16.1.16"
      db.vm.network "forwarded_port", guest: 3306, host: 8808
      db.vm.network "forwarded_port", guest: 80, host: 8306
      db.vm.provision "shell", inline: $install_requirements
  end
end
