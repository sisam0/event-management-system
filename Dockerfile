#Use official PHP with Apache
FROM php:8.2-Apache

#Install PHP extensions(for MySQL connection)
RUN docker-php-ext-install mysqli pdo pdo_mysql

#Enable Apache mod_Rewrite
RUN a2enmod mod_Rewrite

#Copy custio Apache config
COPY apache/000-default.conf /etc/apache2/sites-available/00-default.conf

#Copy project files into container
COPY php/ /var/www/html/

#Set permission
RUN chown -R www-data:www-data /var/www/html/

#Expose port 80
EXPOSE 80