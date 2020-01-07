# DDD Aggregate tests

This repository porpuse is to test an implementation of a DDD Aggregate.

## Domain

- In this domain, we can have questions, and each question has possible answers;
- Each question can have a maximum of 10 possible answers;
- A question can be of the following types:
    - SelectOne: In HTML, this could be a radio input for each answer
    - SelectMultiple: In HTML, this could be a checkbox input for each answer
    
## Use cases

So far, the following use cases are implemented:

1. Add question
    - When adding a question, you msut provide:
        1. its type;
        2. its prompt;
        3. if it is required or not;
        4. its answers.
2. Remove question
    - When removing a question, all of its answers are also removed. You must provide:
        1. The question ID.

## Delivery mechanisms

Many different delivery mechanisms can be implemented using this architecture. CLI, API, Web, etc.

For now, the first one I propose to implement is the web one, using the good old forms to input data.

## Starting the project up

This project comes with a Dockerfile, so if you don't have PHP 7.4 installed, run the following commands after cloning this repository:

```shell script
docker run --rm composer install --ignore-platform-reqs
docker build -t php74-xdebug .
docker run --rm -itv $(pwd):/app -w /app php74-xdebug vendor/bin/phpunit # this runs the unit tests
docker run --rm -itv $(pwd):/app -w /app -p 8080:8080 php74-xdebug -S 0.0.0.0:8080 -t src/Infrastructure/Delivery/Web/Public/ # this starts a web server
```

If everything works ok, you'll be able to access on you browser: `http://0.0.0.0:8080/questions/add`

There you can create a question. After creating it, check the db.sqlite to make sure it worked ok (there's no list for now)