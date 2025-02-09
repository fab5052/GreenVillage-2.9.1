# ./automation/scripts/composer-setup.sh

#!/bin/sh

VERSION="2.7.4"

INSTALL_DIR="/usr/local/bin"
EXPECTED_CHECKSUM="$(wget -q -O - https://composer.github.io/installer.sig)"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
then
    >&2 echo 'ERROR: Invalid installer checksum'
    rm composer-setup.php
    exit 1
fi

php composer-setup.php --quiet --version=$VERSION --install-dir=$INSTALL_DIR
RESULT=$?
rm composer-setup.php
mv $INSTALL_DIR/composer.phar $INSTALL_DIR/composer
chmod +x $INSTALL_DIR/composer

exit $RESULT
    