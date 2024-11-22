# Script bash to create the downloadable archive of this WordPress plugin

if [ ! -d dist ]; then
  mkdir dist
fi
rm -rf dist/tisseurs-event-scheduler.zip
workingdir=$(mktemp -d)
mkdir ${workingdir}/tisseurs-event-scheduler
cp -r src/* ${workingdir}/tisseurs-event-scheduler
currentdir=$(pwd)
cd ${workingdir}
zip -r tisseurs-event-scheduler.zip tisseurs-event-scheduler/*
mv tisseurs-event-scheduler.zip ${currentdir}/dist
