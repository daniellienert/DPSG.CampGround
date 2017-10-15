DPSG.CampGround
===============

DPSG Campground ist ein SitePackage für das Content Management System Neos. Das Paket bietet neben dem, an www.dpsg.de angelegten Vorlage jeder Menge spezielle Seitenelemente die in dieser Art auf den meisten DPSG Seiten vorkommen dürften:

-   Eine Seite für Berichte, welche im Blogstil angezeigt werden. Die Artikel können Stufe zugeordnet werden.
-   Stufenseiten mit einer Liste aller Berichte, welche es zu der jeweiligen Stufe gibt.
-   Ein Slider und eine „Neueste Artikel“ Ansicht um die Berichte auf der Hauptseite zu präsentieren.
-   Integration in Facebook. Facebook Events werden automatisch auf der Seite angezeigt.

Eine genaue Beschreibung des Pakets findet ihr auf http://daniel.lienert.cc

Eine Instanz des Pakets befindet sich auf http://www.dpsg-urloffen.de


Installation
------------

### Pakete installieren

1. Zunächst muss das Paketverwaltungssystem „composer“ installiert werden:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
curl -s https://getcomposer.org/installer | php
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

2. Mittels composer wird dann neos installiert::

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
php composer.phar create-project --no-dev neos/neos-base-distribution
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

3. Ersetze die Zeile `"typo3/neosdemotypo3org": "1.2.*“,` durch `"dpsg/campground": "dev-master",`

4. Aktualisere die Pakete mit einem Aufruf von

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
php composer.phar update
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

### Seite aktivieren

In deinem Browser öffnest du nun die Adresse unter dem du die Pakete abgelegt hast. Du wisrt zum Setup umgeleitet. Nachdem du die Datenbankzugangsdaten eingetragen und einen Administrator-Benutzer eingetragen hast kannst du die Seite *„DPSG.CampGround“ *importieren.

Hat alles geklappt, kannst du dich im Backend anmelden und die Inhalte in deine neue Webseite einpflegen.

Konfiguration
-------------

Die Konfiguration deiner Seite befindet sich in der Datei: `Configuration/Settings.yaml`. Dort fügst du die folgende Konfigurationsstruktur hinzu:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
DPSG:
  CampGround:
    FacebookIntegration:
      PageId:
      PageName:
      AccessToken:
    GoogleAnalytics:
      TrackingId:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

### Facebook Integration

Unter dem Schlüssel `FacebookIntegration` wird die Verbindung deiner Facebook Seite zur Homepage konfiguriert.

| Schlüsselwort | Bedeutung                                |
|---------------|------------------------------------------|
| PageId        | Zur Integration deiner Facebookpage wird hier die Facebook Seiten Id eingegeben.|
| PageName      | Der Facebook Seitenname                  |
| AccessToken   | Das Facebook Accesstoken                 |


### Google Analytics

Mit `GoogleAnalytics` wird die Integration der Seitenstatistik konfiguriert:

| Schlüsselwort | Bedeutung                    |
|---------------|------------------------------|
| TrackingId    | Die Tracking Id deiner Seite |

### Suchindex füllen

Einige Listen werden mittels Suchanfragen gebildet. Für deren Funktion muss der Suchindex aufgebaut werden.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
./flow nodeindex:flush; ./flow nodeindex:build
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
