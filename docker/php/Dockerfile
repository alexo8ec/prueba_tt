FROM php:8.3-fpm
LABEL maintainer="Alex Mite <alexo8ec@hotmail.com>"
ENV DEBIAN_FRONTEND=noninteractive
ENV ACCEPT_EULA=Y

RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    apt-transport-https \
    ca-certificates \
    lsb-release \
    unixodbc \
    unixodbc-dev \
    libgssapi-krb5-2 \
    libzip-dev \
    libonig-dev \
    zip \
    unzip \
    git \
    pkg-config \
    build-essential \
    autoconf \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /usr/share/keyrings \
    && curl -fsSL https://packages.microsoft.com/keys/microsoft.asc \
    | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" \
    > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && apt-get install -y msodbcsql18 \
    && rm -rf /var/lib/apt/lists/*

# Compilar extensiones por separado para detectar mejor errores
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install zip
RUN docker-php-ext-install mbstring

RUN pecl install sqlsrv \
    && pecl install pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]