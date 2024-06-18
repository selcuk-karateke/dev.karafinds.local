export class SettingsManager {
  constructor() {
    this.settings = this.loadSettings();
    this.init();
  }
  init() {
    document.addEventListener("click", (event) => this.saveSettings(event));
  }
  loadSettings() {
    // Lade die Einstellungen aus dem lokalen Speicher
    const settings = localStorage.getItem("userSettings");
    // Wenn nichts im Speicher, verwende Standardwerte
    return settings ? JSON.parse(settings) : this.defaultSettings();
  }

  defaultSettings() {
    // Standardwerte für die Einstellungen
    return {
      theme: "dark",
      notifications: true,
      fontSize: "medium",
    };
  }

  saveSettings() {
    // Lese Werte aus den Formularelementen
    const theme = document.getElementById("setting-theme").value;
    const notifications = document.getElementById("setting-notification").value;

    // Aktualisiere die Einstellungen über den SettingsManager
    this.settingsManager.updateSetting("theme", theme);
    this.settingsManager.updateSetting(
      "notifications",
      notifications === "true"
    );

    // Speichere die aktuellen Einstellungen im lokalen Speicher
    localStorage.setItem("userSettings", JSON.stringify(this.settings));

    // Hole die aktuelle Theme-Einstellung
    console.log(this.settingsManager.getSetting("theme"));
    console.log(localStorage.getItem("userSettings"));

    // Schließe das Modal
    var myModalEl = document.getElementById("settingsModal");
    var modal = bootstrap.Modal.getInstance(myModalEl);
    modal.hide();
  }

  updateSetting(key, value) {
    // Aktualisiere eine spezifische Einstellung
    if (key in this.settings) {
      this.settings[key] = value;
      this.saveSettings();
    }
  }

  getSetting(key) {
    // Hole eine spezifische Einstellung
    return this.settings[key];
  }
}
