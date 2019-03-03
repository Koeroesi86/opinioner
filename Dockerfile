FROM nimmis/apache-php7
MAINTAINER Krisztian Korosi <koeroesi86@gmail.com>

ADD . /src
ADD .docker/entrypoint.sh /entrypoint.sh

RUN chown -R www-data:www-data /src

CMD  ["sh", "/entrypoint.sh"]
