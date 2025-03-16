# Лабораторная работа IWNO5: Запуск сайта в контейнере

## Цель работы
Подготовить образ контейнера для запуска веб-сайта на базе Apache HTTP Server + PHP (mod_php) + MariaDB.

## Задание
Создать ```Dockerfile``` для сборки образа контейнера, который будет содержать веб-сайт на базе ```Apache HTTP Server``` + ```PHP``` (mod_php) + ```MariaDB```. База данных ```MariaDB``` должна храниться в монтируемом томе. Сервер должен быть доступен по порту ```8000```.

Установить сайт ```WordPress```. Проверить работоспособность сайта.

## Выполнение

### Извлечение конфигурационных файлов apache2, php, mariadb из контейнера

Создаём ```Dockerfile``` с ```debian``` и устанавливаем ```apache2```, ```php```, ```libapache2-mod-php```, ```php-mysql```, ```mariadb-server```

``` bash
RUN apt-get update && \
    apt-get install -y apache2 php libapache2-mod-php php-mysql mariadb-server && \
    apt-get clean
```

![Alt text](/images/1.png "image")

Собираем образ

![Alt text](/images/2.png "image")

В контексте сборки создаём папку ```files``` и при помощи команд копируем конфигруационные файлы внутрь

``` bash
docker cp apache2-php-mariadb:/etc/apache2/sites-available/000-default.conf files/apache2/
docker cp apache2-php-mariadb:/etc/apache2/apache2.conf files/apache2/
docker cp apache2-php-mariadb:/etc/php/8.2/apache2/php.ini files/php/
docker cp apache2-php-mariadb:/etc/mysql/mariadb.conf.d/50-server.cnf files/mariadb/
```

![Alt text](/images/4.png "image")
![Alt text](/images/3.png "image")

### Настройка конфигурационных файлов
#### Конфигурационный файл apache2

Открываем файл ```000-default.conf``` и разкоментируем строку ```#ServerName www.example.com```, заменяя ```www.example.com на localhost```

Находим строку ```ServerAdmin webmaster@localhost``` и заменяем в ней почтовый адрес на мой (```stasciobanuwor@gmail.com```)

После строки ```DocumentRoot /var/www/html``` добавляем строки: ```DirectoryIndex index.php index.html ```

![Alt text](/images/6.png "image")

В конце ```apache2.conf``` добавляем ```ServerName localhost ```

![Alt text](/images/7.png "image")

#### Конфигурационный файл php

В файле ```php.ini``` разкоментируем ```;error_log = php_errors.log``` и заменяем на ```error_log = /var/log/php_errors.log```

![Alt text](/images/5.png "image")

Настраиваем параметры ```memory_limit```, ```upload_max_filesize```, ```post_max_size``` и ```max_execution_time``` следующим образом:

```bash
memory_limit = 128M
upload_max_filesize = 128M
post_max_size = 128M
max_execution_time = 120
```

![Alt text](/images/9.png "image")
![Alt text](/images/10.png "image")
![Alt text](/images/11.png "image")
![Alt text](/images/12.png "image")

#### Конфигурационный файл mariadb

В файле ```50-server.cnf``` раскоменируем ```#log_error = /var/log/mysql/error.log```

![Alt text](/images/8.png "image")

### Создание скрипта запуска

Создаём директорию ```/files/supervisor``` и в ней добавляем файл ```supervisord.conf``` с содержимым

```bash
[supervisord]
nodaemon=true
logfile=/dev/null
user=root

# apache2
[program:apache2]
command=/usr/sbin/apache2ctl -D FOREGROUND
autostart=true
autorestart=true
startretries=3
stderr_logfile=/proc/self/fd/2
user=root

# mariadb
[program:mariadb]
command=/usr/sbin/mariadbd --user=mysql
autostart=true
autorestart=true
startretries=3
stderr_logfile=/proc/self/fd/2
user=mysql
```

![Alt text](/images/13.png "image")

### Модифицируем Dockerfile

Добавляем инструкции для монтировки томов

```dockerfile
VOLUME /var/lib/mysql

VOLUME /var/log
```

Добавляем скачивание ```supervisor``` и скачивание и распаковку wordpress через ```tar```

```dockerfile
RUN apt-get update && \
    apt-get install -y apache2 php libapache2-mod-php php-mysql mariadb-server supervisor tar && \
    apt-get clean

ADD https://wordpress.org/latest.tar.gz /tmp/wordpress.tar.gz  

RUN tar -xzf /tmp/wordpress.tar.gz -C /var/www/html/ && \
    mv /var/www/html/wordpress/* /var/www/html/ && \
    rm -rf /var/www/html/wordpress /tmp/wordpress.tar.gz
```

Добавляем копирование конфигурационных файлов ```apache2```, ```php```,```mariadb```, а также скрипта запуска:

```dockerfile
COPY files/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY files/apache2/apache2.conf /etc/apache2/apache2.conf

COPY files/php/php.ini /etc/php/8.2/apache2/php.ini

COPY files/mariadb/50-server.cnf /etc/mysql/mariadb.conf.d/50-server.cnf

COPY files/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
```

Для функционирования ```mariadb``` создадим директорию ```/var/run/mysqld``` и установите права на неё:

```dockerfile
RUN mkdir /var/run/mysqld && chown mysql:mysql /var/run/mysqld
```

Откроем порт 80

```dockerfile
EXPOSE 80
```

Добавим команду запуска ```supervisord```

```dockerfile
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
```

![Alt text](/images/14.png "image")

Заново собираем и запускаем образ в контейнере. Проверяем наличие файлов в директории ```/var/www/html```

![Alt text](/images/15.png "image")

### Создание базы данных и пользователя

Создадим базу данных ```wordpress``` и пользователя ```wordpress``` с паролем ```wordpress``` в контейнере ```apache2-php-mariadb```. Для этого, в контейнере ```apache2-php-mariadb```, выполните команды:

```sql
mysql
CREATE DATABASE wordpress;
CREATE USER 'wordpress'@'localhost' IDENTIFIED BY 'wordpress';
GRANT ALL PRIVILEGES ON wordpress.* TO 'wordpress'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

![Alt text](/images/16.png "image")

### Создание файла конфигурации WordPress

Открывем в браузере сайт ```WordPress``` по адресу http://localhost/. Укажите параметры подключения к базе данных:

```
имя базы данных: wordpress;
имя пользователя: wordpress;
пароль: wordpress;
адрес сервера базы данных: localhost;
префикс таблиц: wp_.
```

![Alt text](/images/17.png "image")

Копируем содержимое файла конфигурации в файл ```files/wp-config.php``` на компьютере.

![Alt text](/images/18.png "image")

![Alt text](/images/19.png "image")

![Alt text](/images/20.png "image")

В ```Dockerfile``` добавляем строку ```COPY files/wp-config.php /var/www/html/wordpress/wp-config.php```

Снова запускаем контейнер с бересобранным образом

Как мы видим наш конфиг теперь находитя внутри контейнера

![Alt text](/images/21.png "image")

## Ответы на вопросы

1. ### Какие файлы конфигурации были изменены?
   - ```000-default.conf```
   - ```apache2.conf```
   - ```php.ini``
   - ```50-server.cnf```

   Так же мы добавили ```supervisor.conf``` и написали ```Dockerfile```


2. ### За что отвечает инструкция ```DirectoryIndex``` в файле конфигурации ```apache2```?
   Инструкция ```DirectoryIndex``` указывает что будети загруженно по умолчанию, при условии что мы не укажем другого файла в запросе

3. ### Зачем нужен файл ```wp-config.php```?
   В ```wp-config.php``` содержатся настройки WordPress. Например там находятся имя хоста, пароль от базы данных и.т.д

4. ### За что отвечает параметр ```post_max_size``` в файле конфигурации php?  
   ```post_max_size``` указывает максимальный размер ```body``` для ```POST``` запросов

5. ### Укажите, на ваш взгляд, какие недостатки есть в созданном образе контейнера?
   База данных и веб-сервер запускаются в одном и том же контейнере. Их следовало бы разделить и запустить в двух разных для безопасности, удобства и расширяемости.

## Выводы

    1. Мы запустили сайт wordpress внутри контейнера, настроив веб-сервер (Apache) и базу данных (MariaDB) 
    2. Мы добавили supervisor для управления несколькими сервисами
    3. Мы установили Wordpress и сконфигурировали его