#
# Install Aegir debian packages located in the 'build/' directory.
# These are provided by the GitLab CI build stage.
#
# This script is tuned for Ubuntu 16.04.
#
echo "[CI] Updating APT"
sudo apt-get update

echo "[CI] Setting debconf settings"
echo "debconf debconf/frontend select Noninteractive" | debconf-set-selections
#echo "debconf debconf/priority select critical" | debconf-set-selections


echo mysql-server-5.7 mysql-server/root_password password PASSWORD | debconf-set-selections
echo mysql-server-5.7 mysql-server/root_password_again password PASSWORD | debconf-set-selections

debconf-set-selections <<EOF
aegir3-hostmaster aegir/db_password string PASSWORD
aegir3-hostmaster aegir/db_password seen  true
aegir3-hostmaster aegir/db_user string root
aegir3-hostmaster aegir/db_host string localhost
aegir3-hostmaster aegir/email string  aegir@example.com
aegir3-hostmaster aegir/site  string  aegir.example.com
postfix postfix/main_mailer_type select Local only

EOF

echo "[CI] Pre-installing dependencies"
sudo apt-get install --yes mariadb-server php7.1-mysql php7.1-cli php7.1 postfix

echo "[CI] Installing .deb files .. will fail on missing packages"
sudo DPKG_DEBUG=developer dpkg --install build/aegir3_*.deb build/aegir3-provision*.deb build/aegir3-hostmaster*.deb

echo "[CI] Installing remaining packaged and configuring our debs"
sudo apt-get install --fix-broken --yes

