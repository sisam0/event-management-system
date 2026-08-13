#Use official PHP with Apache
FROM php:8.2-apache

#Install PHP extensions(for MySQL connection)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set PHP upload limits
RUN echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "max_file_uploads = 50" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini

#Enable Apache mod_Rewrite
RUN a2enmod rewrite

#Copy custio Apache config
COPY apache/000-default.conf /etc/apache2/sites-available/00-default.conf

#Copy project from src which has all the php files into container
COPY ./src /var/www/html/

#Set permission
RUN chown -R www-data:www-data /var/www/html/

#Expose port 80
EXPOSE 80