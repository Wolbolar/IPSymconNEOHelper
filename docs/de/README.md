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

Um einen Farbwähler in einer NEO Remote für jedes beliebige Gerät in IP-Symcon, bei dem man eine Farbe einstellen kann, nutzten zu können muss eine Color Picker Instanz angelegt werden.
Diese dient als Zwischeninstanz für die NEO Remote die den Farbwert aus der NEO Remote eingegegen nimmt und dann den Farbwert an das eigentliche Gerät weiterreicht, da die NEO Remote selber zur Zeit nicht in der Lage ist den Farbwert direkt zu übergeben.

Dazu wird zunächst eine Neo Color Picker Instanz erzeugt über drücken auf __+__ und dann __*Instanz*__, also Filter gibt man __neo color__ ein.

![color_picker_1](img/neo_color_picker_1.png?raw=true "Color Picker 1")


In der Instanz ist dann die Variable des Geräts auszuwählen, die den Farbwert schaltet.

![color_picker_2](img/neo_color_picker_2.png?raw=true "Color Picker 2")

#### NEO Seitenwechel / Seite neu laden

Um einen Seitenwechsel aus IP-Symcon in einer NEO Remote durchführen zu können wird eine NEO Page Instanz angelegt für die NEO Seite, die geladen werden soll.

Dazu wird zunächst eine Neo Color Picker Instanz erzeugt über drücken auf __+__ und dann __*Instanz*__, also Filter gibt man __neo page__ ein.

![neo_page_1](img/neo_page_1.png?raw=true "NEO Page 1")
 
In der neu erstellten Instanz trägt man den NEO Remote Namen, wie im NEO Creator angegeben, in das Textfeld _Remotename_ ein.
In das Feld _Seitenname_ wird der Seitenname der NEO Remote Seite eingetragen.
 
![neo_page_2](img/neo_page_2.png?raw=true "NEO Page 2")
 
Anschließend kann dann mit einem Ereignis auf einer Variable eines Geräts ein neu laden der Seite bzw. ein wechsel auf die zu NEO Remote Seite durchgeführt werden.
 
In dem Beispiel wird bei Bewegungserkennung auf eine NEO Remote Seite gewechselt
  
![neo_page_3](img/neo_page_3.png?raw=true "NEO Page 3")

#### NEO Popup öffnen / schließen

Um ein Popup aus IP-Symcon in einer NEO Remote öffnen oder schließen zu können, wird eine NEO Popup Instanz angelegt für das NEO Popup, die geöffnet/geschlossen werden soll.

Dazu wird zunächst eine Neo Popup Instanz erzeugt über drücken auf __+__ und dann __*Instanz*__, also Filter gibt man __neo popup__ ein.

![neo_popup_1](img/neo_popup_1.png?raw=true "NEO Popup 1")

In der neu erstellten Instanz trägt man den NEO Remote Namen, wie im NEO Creator angegeben, in das Textfeld _Remotename_ ein.
In das Feld _Popup Name_ wird der Popup Name der NEO Remote Popup Seite eingetragen.

![neo_popup_2](img/neo_popup_2.png?raw=true "NEO Popup 2")

Anschließend kann dann mit einem Ereignis auf einer Variable eines Geräts ein Popup öffnen bzw. schließen.
 
In dem Beispiel wird ein NEO Remote Popup geöffnet

![neo_popup_3](img/neo_popup_3.png?raw=true "NEO Popup 3")

In dem Beispiel wird ein NEO Remote Popup geschlossen

![neo_popup_4](img/neo_popup_4.png?raw=true "NEO Popup 4")

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

#### NEO Page:

GUID: `{1C9BD973-E774-80F6-3292-28305F34A938}` 

#### NEO Popup:

GUID: `{EC5F5B4D-7855-7FC9-244E-E9FC0764F7AE}` 