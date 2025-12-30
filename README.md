### Hexlet tests and linter status:
[![Actions Status](https://github.com/Lurk150rus/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/Lurk150rus/php-project-48/actions)

# php-project-48
[![Makefile PHP CI](https://github.com/Lurk150rus/php-project-48/actions/workflows/makefile.yml/badge.svg)](https://github.com/Lurk150rus/php-project-48/actions/workflows/makefile.yml)

## Prerequisites

* Linux, Macos, WSL
* PHP >=8.2
* Xdebug
* Make
* Git

## Addons

Use <http://psysh.org/>

## Setup

Setup [SSH](https://docs.github.com/en/authentication/connecting-to-github-with-ssh) before clone:

```bash
git clone https://github.com/Lurk150rus/php-project-48.git
cd php-project-48

make install
```

## Run linter

```sh
make lint
```

See configs [php.xml](./phpcs.xml) and [phpstan.neon](./phpstan.neon)

## Run tests

```sh
make test
```

## Test Coverage

* see `phpunit.xml`
* See [sonarcloud documentation](https://docs.sonarsource.com/sonarqube-cloud/enriching/test-coverage/php-test-coverage/)
* add `SONAR_TOKEN` to workflow as SECRETS ENV VARIABLE (for safety)
