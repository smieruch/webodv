FROM ubuntu:20.04

MAINTAINER webODV <webodv@webodv.org>




#install software
RUN apt-get update && apt-get -y upgrade && DEBIAN_FRONTEND=noninteractive \
    apt-get -y install \
    sudo \
    apache2 \
    php7.4 \
    php7.4-mbstring \
    php7.4-xml \
    php7.4-curl \
    php7.4-mysql \
    php7.4-soap \
    php7.4-imagick \
    php-zip \
    composer \
    libapache2-mod-php7.4 \
    emacs \
    xvfb \
    qt5dxcb-plugin \
    mysql-client \
    netcat-traditional \
    cron \
    iputils-ping \
    inetutils-telnet \    
    npm \
    curl \
    zip


#-----install node------------------#
RUN curl -sL https://deb.nodesource.com/setup_14.x -o nodesource_file.sh
#install new version of node if it is not installed
RUN chmod +x nodesource_file.sh
RUN ./nodesource_file.sh
RUN apt-get -y install nodejs




#Enable apache mods.
RUN a2enmod rewrite
RUN a2enmod *proxy*

#Expose apache.
EXPOSE 80

# # add crontab
COPY crontab /etc/crontab
# # sudoers
COPY sudoers /etc/sudoers
# # Update the default apache with our config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# # Copy HTML and PHP ..., mount it for development
COPY webodv /var/www/html/webodv

# new user
RUN useradd -u 1000 -ms /bin/bash woody
USER woody
WORKDIR /home/woody
# create Documents
RUN mkdir /home/woody/Documents

#set display for development
ENV DISPLAY :0

# # init
COPY init.bash /var/www/html/init.bash
# mount also if needed

# # switch to root
USER root

#run init
CMD /var/www/html/init.bash 
