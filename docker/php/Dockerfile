FROM php:8.3-fpm

# Устанавливаем необходимые пакеты и расширения
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Аргументы UID/GID (по умолчанию 1000)
ARG UID=1000
ARG GID=1000

# Создаём группу и пользователя с такими же UID/GID, как на хосте
RUN groupadd -g ${GID} appgroup \
    && useradd -u ${UID} -g ${GID} -m appuser

# Используем этого пользователя по умолчанию
USER appuser
