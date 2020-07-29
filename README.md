
Kakadu microservice template for Symfony
-------------------

RPC 2.0 API 

ENVIRONMENT:  
 - `IJSON` - Invert json host and port (with protocol)
 - `PROJECT_ALIAS` - your_project_name, etc...

**Run:**
 - Install Symfony CLI:
    - copy file:
        - LINUX `wget https://get.symfony.com/cli/installer -O - | bash`
        - MACOS `curl -sS https://get.symfony.com/cli/installer | bash`
    - install it globally on your system
         `sudo mv /home/$USER/.symfony/bin/symfony /usr/local/bin/symfony`
    
- Configure (pass environment variables below for each step):
    - Run docker container `ijson` and `mysql` in `docker-compose.yml`
      ```bash
        docker-compose run mysql
        docker-compose run ijson
      ```
    - `composer install`
    - Create config `./bin/console microservice:configure`
    - Start server `./bin/console microservice:start`
    - See `scratches` folder for make requests
