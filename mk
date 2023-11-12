#!/bin/bash
set -a
source ./env.dev.sh;

help() {
    echo -e "\e[1;37mList of commands:\e[m";
    echo -e "css\t\t\t bin/tailwind -c tailwind.config.js -o tailwind.css";
    echo -e "db \e[32mmigrate\e[m\t\t Execute migrations file in the \e[33mMYSQL_DATABASE\e[m ";
    echo -e "db \e[32mmigration\e[m <string>\t Create a new migration at \e[33mDB_MIGRATION\e[m/<string>_timestamp.sql";
    echo -e "db \e[32mquery\e[m <string>\t Execute query as \e[33mMYSQL_USER\e[m to \e[33mMYSQL_DATABASE\e[m defined in the mysql container";
    echo -e "db \e[32mquery\e[m root <string>\t Same as above but as root mysql user";
    echo "";
    echo -e "deploy\e[m\t\t\t Zip required files and directory to be uploaded on remove server";
    echo "";
    echo -e "docker \e[32mup\e[m\t\t docker compose up with env loading";
    echo -e "docker \e[32mdown\e[m\t\t docker compose down";
    echo -e "docker \e[32mrmall\e[m\t\t Remove all volumes, images and container";
    echo -e "docker \e[32mreload\e[m\t\t ./mk rmall and ./mk up";
    echo "";
    echo -e "\e[1;37mEnv vars:\e[m";
    for env in ${env_list[@]}; do
        varname="$env";
        echo -e "\e[33m$env\e[m=${!varname}";
    done
}

case $1 in
    "css")
        bin/tailwindcss -c tailwind.config.js -o tailwind.css;
        cp tailwind.css src/public/css/;
        cp tailwind.css src/private/css/;
        ;;

    "docker")
        case $2 in
            "up")
                shift 2
                docker compose up $@
                ;;

            "down")
                shift 2
                docker compose down $@
                ;;

            "rmall")
                ./mk docker down;
                docker container rm $(docker container ls -qa);
                docker image rm $(docker image ls -qa);
                docker volume rm $(docker volume ls -q);
                ;;

            "reload")
                shift 2
                ./mk docker rmall;
                ./mk docker up $@;
                ;;

            *)
                ;;
        esac
        ;;

    "db")
        container=$(docker ps --format="{{.Names}}" | grep db);
        shift 

        if [[ "$container" == "" ]]; then
            echo -e "\033[31mNo container found containing \"db\" in their name\033[m";
            exit 0;
        else
            case $1 in
                "query")
                    if [[ $2 == "root" ]]; then
                        docker exec $container sh -c "mysql -uroot -p$DB_PASSWORD -D $DB_NAME -e \"$3\"";
                    else
                        docker exec $container sh -c "mysql -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e \"$2\"";
                    fi;
                    ;;
                "migrate")
                    for file in ./docker/mysql/migrations/*.sql; do
                        echo -e "\e[1;34m${file}\e[m";
                        docker exec $container sh -c "mysql -u$DB_USER -p$DB_PASSWORD $DB_NAME < $file"
                    done;
                    ;;

                "migration")
                    let file="./docker/mysql/migrations/$2_$(date +%s).sql"
                    touch $file;
                    echo $file;
                    ;;
                *)
                    ;;
            esac
        fi;
        ;;

    "deploy")
        rm pull-build/2nd-last-build.zip;
        mv pull-build/last-build.zip pull-build/2nd-last-build.zip;
        mv pull-build/build.zip pull-build/last-build.zip;
        zip pull-build/build.zip -r bin fonts src/private/*.html src/private/css/style.css src/private/*.png src/private/*.js src/public tailwind.config.js;
        scp -P 52367 pull-build/build.zip azefortwo@azefortwo:montcelard-build/build.zip;
        ;;

    *)
        if [[ $1 == "" ]]; then
            help
        else
            echo -e "\033[31mCommand \"$1\" not found\033[m";
            help
        fi
esac
