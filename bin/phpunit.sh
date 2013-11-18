if [ ! -d ../ZF2/library/Zend ]; then
  echo "This must be run parallel to a ZF2 vendor directory"
  exit
fi

if [ ! -f ../phpunit/phpunit/phpunit.php ]; then
  echo "This is written to be run parallel to a phpunit/phpunit vendor directory"
  exit
fi

../phpunit/phpunit/phpunit.php -c FhskCore/test;
../phpunit/phpunit/phpunit.php -c FhskConfig/test;

