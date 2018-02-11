# Prizovka

  Software system for managing and control of serving subpoenas

## Overview

  The main cases of use are that the system creates addresses whose coordinates are encoded in the so-called matrix (QR) codes. Once an employee scans this code, the subpoena (its distribution address) is assigned to it and appears into the list of summonses. These subpoenas, which are tied to specific addresses, may then form the shortest delivery route. Other important instances of use are related to easy access to the address history and address visits, as well as statistics measuring of the number of key business-specific indicators.

### Prerequisites

* Apache 2.4
* PHP >= 5.6
* MySQL >= 5.5
* Git >= 1.9
* Phalcon >= 3.0.0
* Composer >= 1.4.0

### Installing

The very first steps are to install Apache, PHP and MySQL or alternatevely XAMP or WAMP.
Then the the installation of Phalcon depends on the operating system: 

Windows:
https://phalconphp.com/en/download/windows
```
extension=php_phalcon.dll
```
Ubuntu/Debian
https://phalconphp.com/en/download/linux

```
curl -s "https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh" | sudo bash

sudo apt-get install php5-phalcon

# Ubuntu 16.04+, Debian 9+
sudo apt-get install php7.0-phalcon
```

For more and specific operating systems, please visit https://phalconphp.com/en/download/linux.

## Deployment

An example of an already deployed system could be found here:

https://prizovka.finite-soft.com

For more information, please contact me at avkrastev@gmail.com.

## Built With

* [Phalcon](https://phalconphp.com/en/) - The PHP framework used
* [jQuery](https://jquery.com/) - A JavaScript framework
* [Bootstrap](http://getbootstrap.com/) - Front-end component library


## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/avkrastev/prizovka/tags). 

## Authors

* **Alexander Krastev** - *Initial work* - [avkrastev](https://github.com/avkrastev)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Inspiration

* **Finite Sofware Systems** - https://finite-soft.com/
* **Konstantin Pavlov** - http://www.kpavlov.com/
