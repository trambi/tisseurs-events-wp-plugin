# Script bash to create the downloadable archive of this WordPress plugin

if [ ! -d dist ]; then
  mkdir dist
fi
rm -rf dist/tisseurs-events-wp-plugin.zip
workingdir=$(mktemp -d)
mkdir ${workingdir}/tisseurs-events-wp-plugin
cp -r src/* ${workingdir}/tisseurs-events-wp-plugin
currentdir=$(pwd)
cd ${workingdir}
zip -r tisseurs-events-wp-plugin.zip tisseurs-events-wp-plugin/*
mv tisseurs-events-wp-plugin.zip ${currentdir}/dist
cd ${currentdir}
rm -rf ${workingdir}

