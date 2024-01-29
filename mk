#!/bin/bash
set -a
env_type="dev"
source ./env.$env_type.sh;

help() {
    echo -e "\e[1;37mList of commands:\e[m";
    echo -e "css\t\t\t bin/tailwind -c tailwind.config.js -o tailwind.css";
    echo -e "test\t\t\t Run apitest files from \e[33mapi/test/*.jsona\e[m";
    echo -e "db \e[32mmigrate\e[m\t\t Execute migrations file in the \e[33mMYSQL_DATABASE\e[m ";
    echo -e "db \e[32mpopulate\e[m\t\t Execute sql query from \e[33mdocker/db/data/*.sql\e[m in \e[33mMYSQL_DATABASE\e[m ";
    echo -e "db \e[32mquery\e[m <string>\t Execute query as \e[33mMYSQL_USER\e[m to \e[33mMYSQL_DATABASE\e[m defined in the mysql container";
    echo -e "db \e[32mquery\e[m root <string>\t Same as above but as root mysql user";
    echo "";
    echo -e "deploy\e[m\t\t\t Zip required files and directory to be uploaded on remove server";
    echo "";
    echo -e "docker \e[32mup\e[m\t\t docker compose up with env loading";
    echo -e "docker \e[32mbuild\e[m <string> \t build the given service in compose";
    echo -e "docker \e[32mdown\e[m\t\t docker compose down";
    echo -e "docker \e[32mrmall\e[m\t\t Remove all volumes, images and container";
    echo "";
    echo -e "\e[1;37mEnv vars from \e[m\e[1;32menv.$env_type.sh\e[m";
    for env in ${env_list[@]}; do
        varname="$env";
        echo -e "\e[33m$env\e[m=${!varname}";
    done
}

case $1 in
    "css")
        bin/tailwindcss -c tailwind.config.js -o tailwind.css;
        cp tailwind.css src/public/css/;
        ;;

    "docker")
        case $2 in
            "build")
                shift 2
                docker compose build $@
                ;;
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

            "rm")
                shift 2
                if [[ -z $1 ]]; then
                    docker ps -a --format "{{.Names}} {{.Image}}" | \
                        fzf \
                        --preview="docker inspect {1}"\
                        --bind="enter:execute(docker container stop {1}; docker image rm -f {2};)+refresh-preview";
                else 
                    ./mk docker up --force-recreate --build -d;
                fi;
                ;;

            *)
                help;
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
                    for file in ./docker/db/migrations/*.sql; do
                        echo -e "\e[1;34m${file}\e[m";
                        docker exec $container sh -c "mysql -u$DB_USER -p$DB_PASSWORD $DB_NAME < /docker-entrypoint-initdb.d/migrations/$(basename $file)"
                    done;
                    ;;

                "populate")
                    for file in ./docker/db/data/*.sql; do
                        echo -e "\e[1;34m${file}\e[m";
                        docker exec $container sh -c "mysql -u$DB_USER -p$DB_PASSWORD $DB_NAME < /docker-entrypoint-initdb.d/data/$(basename $file)"
                    done;
                    ;;

                "migpop")
                    ./mk db migrate;
                    ./mk db populate;
                    ;;

                *)
                    help
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

    "test")
        # If you want to make the online_crud tests
        # You need to add to your env MONTCELARD_TEST_LOGIN_[NAME|PASSWORD]
        # Ask repo admin for credentials
        shift 1;
        bin/phpstan analyse -c bin/phpstan.neon api
        bin/apitest ./api/test/main.jsona $@ 2>&1
        ;;

    *)
        if [[ $1 == "" ]]; then
            help
        else
            echo -e "\033[31mCommand \"$1\" not found\033[m";
            help
        fi
esac
