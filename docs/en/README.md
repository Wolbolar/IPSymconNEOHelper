# IPSymconNEOHelperTools
[![Version](https://img.shields.io/badge/Symcon-PHPModule-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-%3E%205.1-green.svg)](https://www.symcon.de/en/service/documentation/installation/)

Module for IP Symcon Version 5.1 or higher. Allows you to send commands to the Philips Hue Sync Box und receive the current state from the device in IP-Symcon.

## Documentation

**Table of Contents**

1. [Features](#1-features)
2. [Requirements](#2-requirements)
3. [Installation](#3-installation)
4. [Function reference](#4-functionreference)
5. [Configuration](#5-configuration)
6. [Annex](#6-annex)

## 1. Features

The module provides auxiliary functions for NEO.

- Tool for color selection in NEO
- Auxiliary tool for generating scripts for toggles
- Change sides in a NEO Remote, reload
- Open and close popups in a NEO Remote
- mControl server for the simple setting of individual values from NEO

## 2. Requirements

 - IPS > 5.1
 - NEO Creator 2.8.1

## 3. Installation

### a.  Set up the NEO Remote
        
In order to be able to change or reload pages in a NEO Remote from IP-Symcon, a NEO Remote must first be created in the NEO Creator and the corresponding page created.
The same applies to popups that should be opened or closed from IP-Symcon.

### b. Loading the module

Open the IP Console's web console with _http://{IP-Symcon IP}:3777/console/_.

Then click on the module store icon in the upper right corner.

![Store](img/store_icon.png?raw=true "open store")

In the search field type

```
NEO helper tools
```  


![Store](img/module_store_search_en.png?raw=true "module search")

Then select the module and click _Install_

![Store](img/install_en.png?raw=true "install")

### c.  Setup in IP-Symcon

#### NEO color picker

.

#### NEO page change / reload page

.

#### Open / close NEO popup

.

#### mControl Server for addressing individual values in IP-Symcon

.


## 4. Function reference


## 5. Configuration:

### NEO Color Picker:

| Property      | Type    | Value        | Description                        |
| :-----------: | :-----: | :----------: | :--------------------------------: |
| Host          | string  |              | IP Adress                          |
| Updateinterval| integer |              | Updateinterval in Seconds          |


## 6. Annex

###  a. Functions:



###  b. GUIDs and data exchange:

#### NEO Color Picker:

GUID: `{239B53F3-9C16-AB61-B919-11EF82072FC8}` 