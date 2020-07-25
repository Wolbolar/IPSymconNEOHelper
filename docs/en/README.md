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

In order to be able to use a color picker in a NEO Remote for any device in IP-Symcon with which you can set a color, a Color Picker instance must be created.
This serves as an intermediate instance for the NEO Remote, which takes the color value from the NEO Remote and then forwards the color value to the actual device, since the NEO Remote itself is currently not able to transfer the color value directly.

To do this, first create a Neo Color Picker instance by pressing __+__ and then __*instance*__, i.e. filter you enter __neo color__.

![color_picker_1](img/neo_color_picker_1.png?raw=true "Color Picker 1")


The variable of the device that switches the color value must then be selected in the instance.

![color_picker_2](img/neo_color_picker_2.png?raw=true "Color Picker 2")

#### NEO page change / reload page

In order to be able to switch pages from IP-Symcon in a NEO remotely, a NEO page instance is created for the NEO page that is to be loaded.

To do this, first create a Neo Color Picker instance by pressing __+__ and then __*instance*__, i.e. filter, enter __neo page__.

 ![neo_page_1](img/neo_page_1.png?raw=true "NEO Page 1")
 
In the newly created instance, enter the NEO Remote Name, as specified in the NEO Creator, in the text field _Remotename_.
The page name of the NEO Remote page is entered in the field _page name_.
 
![neo_page_2](img/neo_page_2.png?raw=true "NEO Page 2")
 
You can then reload the page or switch to the NEO Remote page with an event on a device variable.
 
In the example, a switch is made to a NEO remote page when motion is detected
  
![neo_page_3](img/neo_page_3.png?raw=true "NEO Page 3")

#### Open / close NEO popup

In order to be able to open or close a popup from IP-Symcon in a NEO Remote, a NEO popup instance is created for the NEO popup, which should be opened / closed.

To do this, first create a Neo Popup instance by pressing __+__ and then __*instance*__, i.e. filter, you enter __neo popup__.

![neo_popup_1](img/neo_popup_1.png?raw=true "NEO Popup 1")

In the newly created instance, enter the NEO Remote Name, as specified in the NEO Creator, in the text field _Remotename_.
The popup name of the NEO Remote Popup page is entered in the _Popup Name_ field.

![neo_popup_2](img/neo_popup_2.png?raw=true "NEO Popup 2")

A pop-up can then open or close with an event on a variable of a device.
 
In the example, a NEO Remote Popup is opened

![neo_popup_3](img/neo_popup_3.png?raw=true "NEO Popup 3")

In the example, a NEO Remote Popup is closed

![neo_popup_4](img/neo_popup_4.png?raw=true "NEO Popup 4")

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

#### NEO Page:

GUID: `{1C9BD973-E774-80F6-3292-28305F34A938}` 

#### NEO Popup:

GUID: `{EC5F5B4D-7855-7FC9-244E-E9FC0764F7AE}` 