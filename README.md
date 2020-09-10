Template
-------------------

RPC 2.0 API MICROSERVICE BASED ON [IJSON](https://github.com/lega911/ijson)

## MENU
 - [ENVIRONMENTS](#environments)
 - [RUN WITH CODE](#run-with-code)
 - [DOCUMENTATION](#documentation)

--------------

### <a id="environments"></a>ENVIRONMENTS:
 - `IJSON_HOST` - Invert json host and port (with protocol). Default: `http://localhost:8001`
 - `PROJECT_ALIAS` - panel, apple, etc..
 - `APP_ENV` - dev, prod.
 - `CONTROL_PANEL_DISABLE` - Disable control panel microservice config obtain. Default: no
 - `AUTHORIZATION_DISABLE` - Disable authorization microservice import rules. Default: yes
 - `MYSQL_HOST` - Mysql host.
 - `MYSQL_PORT` - Mysql port.
 - `MYSQL_DATABASE` - Mysql database.
 - `MYSQL_USER` - Mysql user.
 - `MYSQL_PASSWORD` - Mysql password.
 
### <a id="run-with-code"></a>RUN WITH CODE:
 - Get code:
    - `git clone https://github.com/kakadu-dev/symfony-microservice-geo.git`

    - Run docker container `ijson` and `mysql` in `docker-compose.yml`
      ```bash
        docker-compose run mysql
        docker-compose run ijson
      ```
      or
      ```bash
        docker-compose up
      ```
    - Install dependencies `composer install`
    - Create configuration:
        ```bash
            ./bin/console microservice:configure
        ```
        or for manual configuration add the `manual` key
        ```bash
            ./bin/console microservice:configure manual
        ```
    - See `.env` your environments variables
    - Run microservice `php bin/console microservice:start`
    - See `scratches` folder for make requests
    - See `config/services.yaml microservice.service_name:name` for change microservice name

### <a id="documentation"></a>DOCUMENTATION:
 - Generate docs `composer run-script docs`
 - Open `apidoc-generated/index.html` in root dir