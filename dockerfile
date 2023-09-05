FROM drupalci/php-7.4-apache:dev

WORKDIR /var/www/html

# 复制项目文件
COPY --chown=www-data:www-data ./ /var/www/html
# COPY ./ /var/www/html

#处理env文件问题
COPY ./docker/.env /var/www/html/.env

#复制进程管理器文件
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]