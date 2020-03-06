# IPSymconNEOHelperTools
[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-%3E%205.1-green.svg)](https://www.symcon.de/service/dokumentation/installation/)

Modul für IP-Symcon ab Version 5.1. Stellt Hilfsfunktionen für die Ansteuerung von IP-Symcon aus NEO von Mediola zur Verfügungs sowie Funktionen zur Ansteuerung von NEO aus IP-Symcon.

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Voraussetzungen](#2-voraussetzungen)  
3. [Installation](#3-installation)  
4. [Funktionsreferenz](#4-funktionsreferenz)
5. [Konfiguration](#5-konfiguartion)  
6. [Anhang](#6-anhang)  

## 1. Funktionsumfang

Das Modul stellt Hilfsfunktionen für NEO zur Verfügung.

- Hilfswerkzeug für Farbauswahl in NEO
- Hilfswerkzeug zum erzeugen von Skripten für Toggle
- Seiten in einer NEO Remote wecheln, neu laden
- Popups in einer NEO Remote öffnen und schließen
- mControl Server für das einfache setzten von einzelen Werten aus NEO

## 2. Voraussetzungen

 - IPS > 5.1
 - NEO Creator 2.8.1

## 3. Installation

### a. Einrichten der NEO Remote

Um Seiten in einer NEO Remote aus IP-Symcon wechseln bzw. neu laden zu können muss zunächst eine NEO Remote im NEO Creator angelegt werden und entsprechnde Seite erzeugt werden.
Das Gleiche gilt für Popups die aus IP-Symcon geöffnet oder geschlossen werden sollen.

### b. Laden des Moduls

Die Webconsole von IP-Symcon mit _http://{IP-Symcon IP}:3777/console/_ öffnen. 


Anschließend oben rechts auf das Symbol für den Modulstore klicken

![Store](img/store_icon.png?raw=true "open store")

Im Suchfeld nun

```
NEO Hilfswerkzeuge
```  

eingeben

![Store](img/module_store_search.png?raw=true "module search")

und schließend das Modul auswählen und auf _Installieren_

![Store](img/install.png?raw=true "install")

drücken.

### c. Einrichtung in IP-Symcon

#### NEO Farbwähler

.

#### NEO Seitenwechel / Seite neu laden

.

#### NEO Popup öffnen / schließen

.

#### mControl Server zum Ansprechen von einzelnen Werten in IP-Symcon

.


## 4. Funktionsreferenz




## 5. Konfiguration:

### NEO Color Picker:

| Eigenschaft   | Typ     | Standardwert | Funktion                           |
| :-----------: | :-----: | :----------: | :--------------------------------: |
| Host          | string  |              | IP Adresse der Steckdosenleiste    |
| Updateinterval| integer |              | Updateinterval in Sekunden         |


## 6. Anhang

###  a. Funktionen:



	 
###  b. GUIDs und Datenaustausch:

#### NEO Color Picker:

GUID: `{239B53F3-9C16-AB61-B919-11EF82072FC8}` 