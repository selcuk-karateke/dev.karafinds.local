<?php
// classes/ConfigLoader.php
namespace Karatekes;

/**
 * Klasse zum Laden und Verwalten von Konfigurationsdaten aus einer JSON-Datei.
 */
class ConfigLoader
{
    private $config;
    private $filename;

    /**
     * Konstruktor lädt die Konfiguration beim Erstellen der Instanz.
     *
     * @param string $filename Pfad zur Konfigurationsdatei.
     * @throws \Exception Wenn die Datei nicht geladen oder geparst werden kann.
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
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
            } else {
                throw new \Exception("Konfigurationspfad '$path' nicht gefunden.");
            }
        }
        return $data;
    }

    /**
     * Aktualisiert den Wert für einen spezifischen Konfigurationsschlüssel und schreibt die gesamte Konfiguration zurück in die Datei.
     *
     * @param string $path Der Punkt-separierte Pfad (z.B. "websites.0.updates").
     * @param mixed $value Der neue Wert für den Schlüssel.
     */
    public function updateConfig($path, $value)
    {
        $pathArray = explode('.', $path);
        $data = &$this->config;
        foreach ($pathArray as $key) {
            if (!isset($data[$key])) {
                throw new \Exception("Konfigurationspfad '$path' führt zu einem ungültigen Schlüssel.");
            }
            $data = &$data[$key];
        }
        $data = $value;
        file_put_contents($this->filename, json_encode($this->config, JSON_PRETTY_PRINT));
    }
    private function saveConfiguration()
    {
        $configContent = json_encode($this->config, JSON_PRETTY_PRINT);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Fehler beim Kodieren der Konfigurationsdatei: " . json_last_error_msg());
        }
        return file_put_contents($this->filename, $configContent) !== false;
    }
    public function updateConfigByHash($hash, $key, $value)
    {
        foreach ($this->config['websites'] as &$website) {
            if (md5($website['url']) === $hash) {
                $website[$key] = $value;  // Setzt den neuen Zählerwert
                return $this->saveConfiguration();
            }
        }
        return false;
    }
}
