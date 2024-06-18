import { ToastManager } from "./ToastManager.js";

export class SettingsManager {
  constructor() {
    this.settings = this.loadSettings();
    this.init();
    this.applySettingsToForm();
  }

  init() {
    // Horche auf Änderungen in jedem Select-Element, das eine 'data-setting' Attribut besitzt
    document.querySelectorAll("[data-setting]").forEach((select) => {
      select.addEventListener("change", (event) => {
        const key = event.target.getAttribute("data-setting");
        const value = event.target.value;
        this.updateSetting(key, value);
        this.saveSettings();
      });
    });
    this.toastManager = new ToastManager();
  }

  loadSettings() {
    try {
      // Lade die Einstellungen aus dem lokalen Speicher
      const settings = localStorage.getItem("userSettings");
      // Wenn nichts im Speicher, verwende Standardwerte
      return settings ? JSON.parse(settings) : this.defaultSettings();
    } catch (e) {
      this.toastManager.showToast("Fehler beim Laden der Daten: " + e, {
        type: "danger",
        title: "Fehler",
      });
      return this.defaultSettings();
    }
  }
  applySettingsToForm() {
    document.querySelectorAll("[data-setting]").forEach((element) => {
      const settingKey = element.getAttribute("data-setting");
      const settingValue = this.getSetting(settingKey);
      if (element.type === "checkbox") {
        element.checked = settingValue;
      } else {
        element.value = settingValue;
      }
    });
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
    try {
      // Speichere die aktuellen Einstellungen im lokalen Speicher
      localStorage.setItem("userSettings", JSON.stringify(this.settings));
      // Erfolgreich gespeichert
      this.toastManager.showToast("Einstellungen lokal gespeichert", {
        type: "success",
      });
    } catch (e) {
      // Fehlermeldung
      this.toastManager.showToast("Fehler beim Speichern der Daten: " + e, {
        type: "danger",
        title: "Fehler",
      });
    }
  }

  updateSetting(key, value) {
    // Konvertiere Wert zu einem geeigneten Typ, falls nötig
    if (typeof this.settings[key] === "boolean") {
      value = value === "true";
    }
    // Aktualisiere eine spezifische Einstellung
    if (key in this.settings) {
      this.settings[key] = value;
      this.reflectChangesInUI(key, value);
    }
  }
  reflectChangesInUI(key, value) {
    const element = document.querySelector(`[data-setting="${key}"]`);
    // Informationsnachricht
    this.toastManager.showToast("Einstellungen werden übernommen", {
      type: "info",
      delay: 8000,
      title: "Hinweis",
    });
    if (element.type === "checkbox") {
      element.checked = value;
    } else {
      element.value = value;
    }
  }
  getSetting(key) {
    // Hole eine spezifische Einstellung
    return this.settings[key];
  }
}
