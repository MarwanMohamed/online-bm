# Central Restful Api

### Installation
- Clone the project & install composer dependencies:
    ```shell
    
    cd project
    
    cp .env.example .env
    
    composer install
    ```
- Configure Database:

  > Open `.env` file and configure the following lines:
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=project
    DB_USERNAME=root
    DB_PASSWORD=
    ``` 
    ```shell
    php artisan migrate --seed
    ```
- Configure smtp mail credentials:

    ```dotenv
    MAIL_DRIVER=smtp
    MAIL_FROM_NAME=MAIL_FROM_NAME
    MAIL_FROM_ADDRESS=MAIL_FROM_ADDRESS
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    ```
