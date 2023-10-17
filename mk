help() {
    echo -e  "clean\t rm apache2 container & image";
    echo -e  "build\t build apache2";
    echo -e  "start\t docker run";
    echo -e  "stop\t docker stop";
    echo -e  "restart\t stop start";
    echo -e  "rebuild\t stop clean build";
    echo -e  "css\t bin/tailwind -c tailwind.config.js -o tailwind.css";
}
case $1 in
    "clean")
        docker container rm montcelard-gen-apache;
        docker image rm apache2;
        ;;
    "build")
        docker build -t apache2 .
        ;;
    "start")
        if [ -z $PROJECT ]; then
            echo -e "\033[31mERROR \033[m the \$PROJECT env var should point to the root of this project";
            echo -e "\033[31mPlease run this command \033[m\nexport PROJECT=\$(pwd)"
        else
            docker run -dit --name montcelard-gen-apache -p 8080:80 -v $PROJECT:/usr/local/apache2/htdocs/ apache2
        fi;
        ;;
    "stop")
        docker container stop montcelard-gen-apache
        ;;
    "restart")
        ./mk stop;
        ./mk start;
        ;;
    "rebuild")
        ./mk stop;
        ./mk clean;
        ./mk build;
        ;;
    "css")
        bin/tailwindcss -c tailwind.config.js -o tailwind.css;
        cp tailwind.css public/public/css/;
        cp tailwind.css public/private/css/;
        ;;
    "zip-deploy")
	rm pull-build/build.zip;
        zip pull-build/build.zip -r \
            bin\
            fonts \
            public/private/*.html \
            public/private/css/style.css \
            public/private/*.png \
            public/private/*.js \
            public/public \
            tailwind.config.js ;
    	# You need to be authentified to host to do this
        scp pull-build/build.zip azefortwo@best-show:Downloads/build.zip;
	ssh azefortwo@best-show
        ;;
    *)
        echo -e "\033[31mCommand \"$1\" not found\033[m";
        help
esac
