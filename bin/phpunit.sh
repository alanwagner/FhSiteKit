if [ ! -d ../ZF2/library/Zend ]; then
  echo "This must be run parallel to a ZF2 vendor directory"
  exit
fi

phpunit  -c FhskCore/test;
phpunit -c FhskConfig/test;

