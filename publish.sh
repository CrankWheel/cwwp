#!/bin/bash

# For reference see:
#   https://developer.wordpress.org/plugins/wordpress-org/how-to-use-subversion/
#
# Before submitting changes you will need SVN access to the repository for
# our plug-in, which is at:
#   https://plugins.svn.wordpress.org/crankwheel
#
# Your username and password are the same as you use to log in at
# www.wordpress.org.
#
# Your credentials will be stored the first time you check in to SVN. You can
# create a dummy checkin e.g. by modifying one character in the README.txt file
# and then submitting as follows (example assumes username is joisig):
#   svn ci -m 'Test modification' --username joisig
#
# This will prompt you for your password and after that you shouldn't need to
# provide a username or password.
#
# A dashboard showing installation numbers is available at:
#   https://wordpress.org/plugins/crankwheel/advanced/

if [ -z "$1" ]; then
    echo "Must supply one argument, the root directory of the SVN repository."
    exit 1
fi

WORKING_DIR=`pwd`
SVN_DIR=$1

if [ ! -d "$SVN_DIR" ]; then
    echo "The SVN directory must exist."
    exit 1
fi

CWWP_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SVN_DIR="$( cd $CWWP_DIR && cd $SVN_DIR && pwd )"

pushd $SVN_DIR
if [[ $(svn status) ]]; then
    echo "The SVN status must be clean."
    exit 1
fi

svn up

pushd trunk
svn rm dist
popd
popd

rm -rf /tmp/cwwp-staging
mkdir /tmp/cwwp-staging

yarn run build:production || exit 1
cp $CWWP_DIR/crankwheel.zip /tmp/cwwp-staging
pushd /tmp/cwwp-staging
unzip crankwheel.zip
rm crankwheel.zip
popd

mv -f /tmp/cwwp-staging/icon-*.png $SVN_DIR/assets/
cp -R /tmp/cwwp-staging/* $SVN_DIR/trunk/

pushd $SVN_DIR/trunk
svn add dist
cd ..

echo "-----"
echo "ABOUT TO PRINT SVN REPO STATUS"
echo "-----"

svn status

echo "-----"
echo "Does everything look OK with the SVN repo?"
echo "Type 'YES UPDATE PRODUCTION' and press Enter to commit,"
echo "or press Enter to cleanup, or Ctrl+C to quit and leave repo as-is."
echo "-----"
read RESULT

if [ "$RESULT" = "YES UPDATE PRODUCTION" ]; then
    svn ci -m 'New CrankWheel plug-in release.'
else
    svn revert --recursive .
    svn cleanup --remove-unversioned
fi

popd # $SVN_DIR/trunk
