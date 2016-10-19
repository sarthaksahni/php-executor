# php-executor
PHP Executor enables you to remotely queue and execute processes over distributed systems.

---
### Installation

##### Dependencies
- [PHP Composer](https://getcomposer.org) should be installed.
- [PHP Pcntl](http://php.net/manual/en/book.pcntl.php) is required to fork processes and keeping them running.
- You need to have an access to AWS SQS.
- Your queue should be created before.
- AWS Credentials should be in ```~/.aws/credentials```

First, Clone this repository using:

    > git clone https://github.com/sarthaksahni/php-executor
    > cd php-executor

Now, install dependencies using ```composer```

    > composer install

Copy [Sample Env](.env.sample) and create ```.env```

    > cp ./.env.sampel ./.env

That's it, Installation process is done!

---
### Usage
##### Calling Jobs on Worker
You will need to include [Executor](Executor.php) and call it's method ```call()```

    Executor::call('Example','test',["example@example.com"]);

Here, ```Example``` is the class in [lib](lib) with a method ```test```.

##### Creating Job for Worker
- Create a class in [lib](lib) folder
- Add your methods in the class
- Ensure you are making ```Static``` methods only.
- You cannot set static properties remotely.

##### Starting Workers
After setting up your ```.env``` file execute ```start.php```

    > php start.php

You can make the process more verbose by giving -v as option to ```start.php```.

    > php start.php -v 2

```-v``` takes parameters from ```1 - 4```, ```1``` being least verbose and ```4``` being most verbose.

---

If you feel anything is missing please create an issue, I'll work on it. And you are free to use it!
