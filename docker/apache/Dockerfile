FROM httpd:2.4
WORKDIR /usr/local/apache2/htdocs
COPY ./docker/apache/httpd-default.conf /usr/local/apache2/conf/httpd.conf
COPY ./docker/apache/montcelard.conf /usr/local/apache2/conf/extra/httpd-vhosts.conf
RUN mkdir /usr/local/apache2/htdocs/src;
COPY ./src ./src
