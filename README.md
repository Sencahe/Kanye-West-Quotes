<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


# Kanye West Quotes
#### Skill Assessment

The challenge will contain a few core features most applications have. That includes connecting to an API, basic MVC, exposing an API, and finally tests.

The API we want you to connect to is https://kanye.rest/

### The application should have the following features:
* A web page that shows 5 random Kayne West quotes
* There should be a button to refresh the quotes
* There should be a button besides each quote to save it to your favorites
* A web page that shows your saved favorites
* There should be a button to delete a quote from your favorites
* Authentication for these pages should be done with a password
* Frontend should be done with Vue.js and optionally Inertia.js
* An API route should be available to fetch a specified number of quotes random Kayne West quotes
* An API route should be available to fetch your favorites quotes
* An API route should be available to delete a quote from your favorites
* All API route should be secured with a token
* Above features are to be tested with Feature tests

Imagine this app is going to be submitted to Kanye himself, so you implement a new extra feature to really impress him with your creativity and coding skills.
What would that new killer feature be? Please implement it in the app.

## Developer
Name: `Francisco Cahe` <br/>
Email: `franciscocahe@gmail.com`<br/>

## Instructions (Docker required)
### DO NOT START A NEW LARAVEL APP, USE THIS BOILERPLATE INSTEAD !!

### Cloning the repository
1. Create a bare clone of the repository. (This is temporary and will be removed so just do it wherever.)
    ```bash
    git clone --bare https://github.com/FmTod/skill-assessment-kanye-west.git
    ```

2. Create a new repository on GitHub.

3. Mirror-push your bare clone to your new repository.<br/>_Replace &lt;username&gt; with your actual Github username in the url below._<br/>_Replace &lt;repository&gt; with the name of your new repository._
    ```shell
    cd skill-assessment-kanye-west.git
    git push --mirror https://github.com/<username>/<repository>.git
    ```
4. Delete the bare clone created in step 1.
    ```shell
    cd ..
    rm -rf skill-assessment-kanye-west.git
    ```
   
5. You can now clone your repository, where you are going to be working, on your machine (in my case in the code folder).
    ```shell
    cd ~/code
    git clone https://github.com/<username>/<repository>.git
    ```

### Getting Started
1. Create a copy of the `.env.example` file as `.env`
    ```bash
    cp .env.example .env
    ```

2. Install dependencies:
    ```shell
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs
    ```

3. Start the container (Sail):
    ```shell
    ./vendor/bin/sail up -d
    ```
   
4. Generate a new secret key:
    ```shell
    ./vendor/bin/sail artisan key:generate
    ```
   
5. (IMPORTANT) Edit the README.md file and add your name and email.
    ```diff
    - Name: `<your name>` <br/>
    - Email: `<your email>` <br/>
    + Name: Jhon Doe <br/>
    + Email: jhondoe@exmaple.com <br/>
    ```
   
6. (IMPORTANT) Submit your first commit with just the changes to the README.md file. Must be done before starting the assignment.
    ```shell
    git add README.md
    git commit -m "Initial commit"
    git push
    ```

### Executing Commands
#### PHP Commands
```shell
./vendor/bin/sail php --version
 
./vendor/bin/sail php script.php
```

#### Composer Commands
```shell
./vendor/bin/sail composer require laravel/sanctum
```

#### Artisan Commands
```shell
./vendor/bin/sail artisan queue:work
```

#### Node / NPM Commands
```shell
./vendor/bin/sail node --version
 
./vendor/bin/sail npm run dev
```

If you wish, you may use Yarn instead of NPM:
```shell
./vendor/bin/sail yarn
```

#### Running Tests
```shell
./vendor/bin/sail test

./vendor/bin/sail test --group orders
```
### How To (Updated)

In order to make this work in a Windows OS, you must not only have installed Docker and WSL (Windows Subsystem for Linux), but you must use a WSL distribution (usually Ubuntu) to store the project within the Linux file system, being able to execute the commands, and have a better performance. You can use Windows file system, but the application will work very slow.

Before starting the steps, you must check that you have WSL and Docker installed, then you might want to open CMD (or Windows Terminal from Microsoft store) and prompt:
```shell
wsl
cd $user
```
From there you can check where you want to place your project...


1. Git clone this repository:
```shell
git clone https://github.com/Sencahe/Company-Managment.git
```

2. Move into the project's folder in the same path you did the clone of the repo:
```shell
cd Company-Managment/
```

3. Create a copy of the `.env.example` file as `.env`:
```shell
cp .env.example .env
```

4. Build the image running the following command:
```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

5. After that you will get the Image in your docker respository as well as the Laravel dependencies. Before starting the container, we have to modify the Dockerfile that comes by default in "vendor\laravel\sail\runtimes\8.1\Dockerfile" as it has set the Node version in 16, when it has to be at least 18. Without this, you might face versioning issues when runnin the container for the first time using sail.
```shell
vim vendor/laravel/sail/runtimes/8.1/Dockerfile
```
Go to line 6, column 18 to modify the node version to 18 or higher

6. Start the container (Sail):
```shell
./vendor/bin/sail up -d
```

7. Generate a new secret key and the storage link (for the images used in the frontend):
```shell
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan storage:link
```

8. Create table schemas and populate them:
```shell
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

9. install Node dependencies and run Frontend:
```shell
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

10. Check the website at localhost:80 in your browser, login with:
<br/>
<br/>email: test@test.com
<br/>password: password
<br/>
<br/>You can also register your own account!

11. You can also run tests:
```shell
./vendor/bin/sail artisan test
```
