FROM drupalci/php-7.4-apache:dev

WORKDIR /var/www/html

# 使用国内镜像软件源
RUN sed -i 's/deb.debian.org/mirrors.aliyun.com/g' /etc/apt/sources.list && \
    sed -i 's/security.debian.org/mirrors.aliyun.com/g' /etc/apt/sources.list

#安装 imagick PHP 扩展
RUN apt-get update \
    && apt-get install -y libmagickwand-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# 复制项目文件
COPY --chown=www-data:www-data ./ /var/www/html
# COPY ./ /var/www/html

#处理env文件问题
COPY ./docker/.env /var/www/html/.env

#复制进程管理器文件
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]