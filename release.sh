#!/bin/sh
# vim: set expandtab tabstop=4 shiftwidth=4:
#
#  I like Vim (http://www.vim.org) as a text editor.  The above line
#  tells Vim to set the indent to 4 spaces and the tab key to indent
#  4 spaces.
# 
#  release.sh
#
#  Cygwin Shell Script to buikd the wp-SwimTeam release package.
#
#  This script has only been run and tested under Cygwin's Bash shell.
#
#  (c) February 2012 - Mike Walsh - mike@walshcrew.com
#
#  Change Log:
#
#    02/17/2012 - Initial version.
#

##  Programs used by this script that may not be part of a standard install
SEVENZIP="C:/Program Files/7-Zip/7z.exe"
WCREV="C:/Program Files/TortoiseSVN/bin/subWcRev.exe"

MAJOR_RELEASE="1"
MINOR_RELEASE="14"

##  Paths and defaults
BASENAME=`basename $0`
GBLREVNUM=""
TARGET="wp-swimteam"
EXPORT="C:/users/mike/desktop/export"
CYG_EXPORT="`cygpath --unix ${EXPORT}`"
NOEXPORT=""
REPOS="C:/inetpub/wwwroot/wordpress/wp-content/plugins/${TARGET}"
CYG_REPOS="`cygpath --unix ${REPOS}`"
PLUGIN="swimteam.php"
VERSION="include/version.include.php"
ZIPTYPE="-tzip"
ZIPSUFFIX=".zip"
FORCE=""

#
#  procedure show_help()
#
#  This procedure displays the help page.
#
show_help()
{
    cat << EOF
 
    Usage::  $BASENAME [-e | --export Path] [-r | --repos Path] [--major N] [--minor N] [--target Name]

  "$BASENAME" will assemble a release package fromt the Subversion
  repository.  Several optional command line switches can be used to
  control the version and naming of the assembled release package.

  Description of command line switches.  Defaults are noted with (D).

      -e Path            Specify the "Path" where the Subversion
                         content should be exported to.
                         (e.g. -e I:/export)

      --export Path      Specify the "Path" where the Subversion
                         content should be exported to.
                         (e.g. --export I:/export)

      --major Rev        Specify the Major Revision Number or text.

      --minor Rev        Specify the Minor Revision Number or text.

      -r Path            Specify the "Path" where the Subversion
                         working repository can be found.
                         (e.g. -r I:/wp-swimteam)

      --repos Path       Specify the "Path" where the Subversion
                         working repository can be found.
                         (e.g. --repos I:/wp-swimteam)

      -7                 Generate a 7-Zip ".7z" archive.

      --7z               Generate a 7-Zip ".7z" archive.

      -z                 Generate a classic ".zip" archive.

      --zip              Generate a classic ".zip" archive.

      --help             This help text.

  Examples:

      % $BASENAME --export I:/export --target wp-swimteam_beta

      % $BASENAME --export I:/export --major 4 --minor 3-beta

      % $BASENAME --export I:/export --repos I:/wp-swimteam  --target wp-swimteam

  Warning:

      Make sure the export path and repository path are separate trees
      to prevent export content on top of the working repository!

EOF
}

#  Parse through command line

while [ $# -gt 0 ]
do
    case $1 in
        -*)
            case $1 in
                -r)
                    shift
                    REPOS="$1"
                    CYG_REPOS="`cygpath --unix ${REPOS}`" ;;
                --repos)
                    shift
                    REPOS="$1"
                    CYG_REPOS="`cygpath --unix ${REPOS}`" ;;
                -e)
                    shift
                    EXPORT="$1" 
                    CYG_EXPORT="`cygpath --unix ${EXPORT}`" ;;
                --export)
                    shift
                    EXPORT="$1"
                    CYG_EXPORT="`cygpath --unix ${EXPORT}`" ;;
                -7)
                    ZIPTYPE="-t7z"
                    ZIPSUFFIX=".7z" ;;
                --7z)
                    ZIPTYPE="-t7z"
                    ZIPSUFFIX=".7z" ;;
                -z)
                    ZIPTYPE="-tzip"
                    ZIPSUFFIX=".zip" ;;
                --zip)
                    ZIPTYPE="-tzip"
                    ZIPSUFFIX=".zip" ;;
                --force)
                    FORCE="--force" ;;
                --noexport)
                    NOEXPORT="no SVN" ;;
                --major)
                    shift
                    MAJOR_RELEASE="$1" ;;
                --minor)
                    shift
                    MINOR_RELEASE="$1" ;;
                --target)
                    shift
                    TARGET="$1" ;;
                --help)
                    show_help
                    exit 0 ;;
                -*)
                    echo "Error:  unknown option $1 (from: $BASENAME)"
                    exit 1 ;;
            esac ;;
        *)
            echo "Error:  unknown option $1 (from: $BASENAME)"
            exit 1 ;;
    esac
    shift
done


##  Major and Minor release numbers are extracted from the
##  Version.txt file if not specified on the command line.

##  Does Repository exist?

if [ ! -d "${REPOS}" ]
then
    echo "Error:  Repository \"${REPOS}\" does not exist, aborting."
    exit 1
fi

##  Does Export Path exist?

if [ ! -d "${EXPORT}" ]
then
    echo "Error:  Export Path \"${EXPORT}\" does not exist, aborting."
    exit 1
fi

##  Does Export Target exist?
if [ -d "${EXPORT}/${TARGET}" ]
then
    if [ -z "${NOEXPORT}" -a -z "${FORCE}" ]
    then
        echo "Error:  Export Target \"${EXPORT}/${TARGET}\" already exists, aborting."
        exit 1
    else
        echo "Note:  Export Target \"${EXPORT}/${TARGET}\" already exists, re-using it."
    fi
fi

##  Get the global revision number from the repository
##  Wierd behavoir - the number ends up with a \r appended to it ...
GBLREVNUM=`"$WCREV" "$REPOS" | egrep 'Last committed at revision [0-9]?' | cut -d' ' -f5 | cat -v | cut -d'^' -f1`
echo "wp-SwimTeam repository at revision:  $GBLREVNUM"
echo "Building wp-SwimTeam release:  Build: ${MAJOR_RELEASE}.${MINOR_RELEASE}.${GBLREVNUM}"

if [ -z "${NOEXPORT}" ]
then
    ##  Export the latest version of wp-SwimTeam from the repository for building
    echo "Exporting wp-SwimTeam at revision:  ${GBLREVNUM}"
    svn export ${FORCE} --revision ${GBLREVNUM} ${CYG_REPOS} ${CYG_EXPORT}/${TARGET}

    if [ $? -ne 0 ]
    then
	    echo "Unable to export wp-SwimTeam, aborting release script."
        exit 1
    fi
else
    echo "Note:  Skipping SVN Export step for \"${EXPORT}/${TARGET}\", re-using existing data."
fi

echo "Updating export tree with global revision number."
"$WCREV" "$REPOS" "${EXPORT}/${TARGET}/${PLUGIN}" "${EXPORT}/${TARGET}/${PLUGIN}"
"$WCREV" "$REPOS" "${EXPORT}/${TARGET}/${VERSION}" "${EXPORT}/${TARGET}/${VERSION}"

echo "Updating export tree with major revision number."
sed -i -e "s/MAJOR_RELEASE/${MAJOR_RELEASE}/" "${EXPORT}/${TARGET}/${PLUGIN}"
sed -i -e "s/MAJOR_RELEASE/${MAJOR_RELEASE}/" "${EXPORT}/${TARGET}/${VERSION}"

echo "Updating export tree with minor revision number."
sed -i -e "s/MINOR_RELEASE/${MINOR_RELEASE}/" "${EXPORT}/${TARGET}/${PLUGIN}"
sed -i -e "s/MINOR_RELEASE/${MINOR_RELEASE}/" "${EXPORT}/${TARGET}/${VERSION}"

echo "Creating Zip file."
ZIPFILE="${EXPORT}/${TARGET}/../${TARGET}_v${MAJOR_RELEASE}.${MINOR_RELEASE}.${GBLREVNUM}${ZIPSUFFIX}"

##  Clean up old zip file

if [ -f "${ZIPFILE}" ]
then
    rm "{$ZIPFILE}"
fi

##  Build a new zip file

"$SEVENZIP" a "${ZIPTYPE}" "${ZIPFILE}" "${EXPORT}/${TARGET}"

echo "wp-SwimTeam release completed."

exit 0
