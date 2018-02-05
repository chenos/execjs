FROM php:7.1-fpm

RUN apt-get update
RUN apt-get install -y --no-install-recommends git zlib1g-dev

RUN docker-php-ext-install zip

RUN git clone https://github.com/chenos/v8.git /opt/v8
RUN git clone https://github.com/phpv8/v8js.git /tmp/v8js

RUN cd /opt/v8 && git checkout tags/v6.4.388.18
# RUN cd /tmp/v8js && git checkout tags/2.1.0

RUN cd /tmp/v8js && phpize
RUN cd /tmp/v8js && ./configure CXXFLAGS="-Wall -Wno-write-strings -Werror" \
    LDFLAGS="-lstdc++" --with-v8js=/opt/v8
RUN cd /tmp/v8js && make
RUN cd /tmp/v8js && make test
RUN cd /tmp/v8js && make install
RUN docker-php-ext-enable v8js

RUN curl -s http://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

RUN pecl install xdebug && docker-php-ext-enable xdebug
