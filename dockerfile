FROM drupalci/php-7.4-apache:dev

WORKDIR /var/www/html

# 复制项目文件
COPY --chown=www-data:www-data ./ /var/www/html
# COPY ./ /var/www/html