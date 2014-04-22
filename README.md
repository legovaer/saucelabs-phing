saucelabs-phing
===============

This project is used in order to automate your Behat tests which are running on SauceLabs.

__This project is still in active development. Do not use this project on your production environments!__

Installation
------------

This project is build to run on Jenkins combined with Phing. If you want to use this phing build, you need to add it in seperate folder in your project's workspace (e.g. build).


Version log
-----------

### 1.0
* Edited this README.md file =)
* Removed the behat.yml file from the project. The user needs to provide his own behat.yml file.
* Renamed the project to "saucelabs-phing"
* Issue [#5](issues/5) : Changed the hard-coded behat run to a more flexible one.
* Issue [#4](issues/4) : Added a property file where the user can specify the location of his behat.yml file & the location of the behat executable