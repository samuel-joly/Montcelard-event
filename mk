#!/bin/bash
if [ -z $HOST ]
then
    HOST="azefortwo"
fi
if [ -z $USER ]
then
    USER="azefortwo"
fi

help() {
    echo -e  "css\t bin/tailwind -c tailwind.config.js -o tailwind.css";
    echo -e  "zip deploy\t Zip required files and directory to be uploaded on remove server web-host ";
}

case $1 in
    "css")
        bin/tailwindcss -c tailwind.config.js -o tailwind.css;
        cp tailwind.css src/public/css/;
        cp tailwind.css src/private/css/;
        ;;

    "zip")
        case $2 in
            "deploy")
                rm pull-build/2nd-last-build.zip
                mv pull-build/last-build.zip pull-build/2nd-last-build.zip
                mv pull-build/build.zip pull-build/last-build.zip
                zip pull-build/build.zip -r \
                    bin\
                    fonts \
                    src/private/*.html \
                    src/private/css/style.css \
                    src/private/*.png \
                    src/private/*.js \
                    src/public \
                    tailwind.config.js ;
                # You need to be authentified to host to do this
                scp -P 52367 pull-build/build.zip $USER@$HOST:montcelard-build/build.zip;
                ;;
            *)
                echo -e "\033[31mCommand \"zip $2\" not found\033[m";
        esac
        ;;

    *)
        echo -e "\033[31mCommand \"$1\" not found\033[m";
        help
esac
