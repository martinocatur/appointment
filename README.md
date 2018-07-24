# Appointment App

Appointment maker with google calendar api integration.

## Instalation

Download and install this package using composer `composer require kly/appointment`. Load up the autoloader and call the classes you need.

## Steps to contribute

1. Fork the original repository.

2. Clone from your repository

   `https://github.com/<YOUR_GIT_USERNAME>/appointment.git`

3.  Sync your fork with the original repository. 

    `git remote add upstream https://github.com/KLYgarage/appointment.git`

    `git pull upstream master`

4. Create a branch. **Remember, the name of the branch, should express what you're doing**

    `git checkout -b <BRANCH_NAME>`

5. Save `credential_example.json` to `credential.json`. Modify the content accordingly based on your google api calendar settings. For more information about how to enable google calendar api, refer to the following url : [google calendar api](https://developers.google.com/calendar/quickstart/php)

6. When you're fininished developing, you can create pull request. Pull request can be done using the following steps :

    `git add .`

    `git commit -m <YOUR_COMMIT_MESSAGE>`

    `git push -u origin <YOUR_BRANCH_NAME>`

7. Go to your github account, on tab pull request, add your comment. Be detailed, use imperative, emoticon to make it clearer.

8. Watch for feedbacks !

## PHP CS-FIX
PHP CS Fixer is intended to fix coding standard. So, Remember! to always run PHP CS Fixer before you create pull request.

    `composer run cs-fix`

## Testing
Open a command prompt or terminal, navigate to project directory and run command `php ./phpunit --bootstrap ./test/bootstrap.php ./test/`
~~~
> php ./phpunit --bootstrap ./test/bootstrap.php ./test/
PHPUnit 4.8.36 by Sebastian Bergmann and contributors.

..................

Time: 14 seconds, Memory: 10.00MB

OK (18 tests, 98 assertions)
~~~
