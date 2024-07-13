<?php
// classes/ConfigLoader.php
namespace Karatekes;

/**
 * Klasse zum Laden und Verwalten von Konfigurationsdaten aus einer JSON-Datei.
 */
class ConfigLoader
{
    private $config;

    /**
     * Konstruktor lädt die Konfiguration beim Erstellen der Instanz.
     *
     * @param string $filename Pfad zur Konfigurationsdatei.
     * @throws \Exception Wenn die Datei nicht geladen oder geparst werden kann.
     */
    public function __construct($filename)
    {
        $this->loadConfiguration($filename);
    }

    /**
     * Lädt die Konfigurationsdaten aus der angegebenen Datei.
     *
     * @param string $filename Pfad zur Konfigurationsdatei.
     * @throws \Exception Wenn die Datei nicht geladen oder geparst werden kann.
     */
    private function loadConfiguration($filename)
    {
        $configData = file_get_contents($filename);
        if ($configData === false) {
            throw new \Exception("Konnte die Konfigurationsdatei nicht laden.");
        }
        $this->config = json_decode($configData, true);
        if ($this->config === null) {
            throw new \Exception("Fehler beim Parsen der Konfigurationsdaten. Überprüfe das JSON-Format.");
        }
    }

    /**
     * Gibt den Wert für einen spezifischen Konfigurationsschlüssel zurück.
     *
     * @param string $key Der Schlüssel der Konfiguration.
     * @return mixed Der Wert des Konfigurationsschlüssels.
     * @throws \Exception Wenn der Schlüssel nicht gefunden wird.
     */
    public function get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        throw new \Exception("Konfigurationsschlüssel '$key' nicht gefunden.");
    }

    /**
     * Gibt die gesamte Konfiguration zurück.
     *
     * @return array Die gesamte Konfigurationsdaten.
     */
    public function getAll()
    {
        return $this->config;
    }

    /**
     * Gibt die Konfiguration für einen verschachtelten Pfad zurück.
     * Unterstützt verschachtelte Pfade, die Arrays enthalten könnten.
     *
     * @param string $path Der Punkt-separierte Pfad (z.B. "db.0.host").
     * @return mixed Der Wert am Ende des Pfads.
     * @throws \Exception Wenn der Pfad nicht gefunden wird.
     */
    public function getSection($path)
    {
        $pathArray = explode('.', $path);
        $data = $this->config;

        foreach ($pathArray as $key) {
            // Prüfen, ob der Schlüssel ein numerischer Index in einem Array sein könnte
            if (is_array($data) && isset($data[$key])) {
                $data = $data[$key];
            } elseif (!is_array($data) || !isset($data[$key])) {
                throw new \Exception("Konfigurationspfad '$path' nicht gefunden.");
            }
        }
        return $data;
    }
}