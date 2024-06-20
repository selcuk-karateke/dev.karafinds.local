import { ToastManager } from "./ToastManager.js";

export class SettingsManager {
  constructor() {
    this.settings = this.loadSettings();
    this.init();
    this.applySettingsToForm();
    this.applyTheme(); // Stelle sicher, dass das gespeicherte Theme angewandt wird
  }

  init() {
    // Event-Listener für alle Elemente mit "data-setting" Attribut
    document.querySelectorAll("[data-setting]").forEach((element) => {
      element.addEventListener("change", (event) => {
        const key = event.target.getAttribute("data-setting");
        let value = event.target.type === 'checkbox' ? event.target.checked : event.target.value;
        console.log(`Setting ${key} changed to ${value}`); // Prüfe den tatsächlichen Wert
        this.updateSetting(key, value);
        this.saveSettings();
      });
    });

    // EventListener für den Theme-Wechsel-Button
    this.setupThemeChangeListener();
    this.toastManager = new ToastManager();
  }

  setupThemeChangeListener() {
    const themeButton = document.getElementById("change-theme");
    if (themeButton) {
      themeButton.addEventListener("click", () => {
        this.toggleTheme();
      });
    }
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
      notifications: true, // Achte darauf, dass dieser Wert korrekt als Boolean initialisiert wird
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
    // // Konvertiere Wert zu einem geeigneten Typ, falls nötig
    // if (typeof this.settings[key] === "boolean") {
    //   value = value === "true";
    // }
    // Aktualisiere eine spezifische Einstellung
    if (key in this.settings) {
      this.settings[key] = value;
      this.reflectChangesInUI(key, value);
      if (key === "theme") {
        this.applyTheme();
      }
    }
  }
  toggleTheme() {
    const currentTheme = this.getSetting("theme");
    const newTheme = currentTheme === "dark" ? "light" : "dark";
    this.updateSetting("theme", newTheme);
    this.saveSettings();
    this.applyTheme();
  }

  applyTheme() {
    const theme = this.getSetting("theme");
    document.body.classList.toggle("bg-dark", theme === "dark");
    document.body.classList.toggle("text-white", theme === "dark");
    document.querySelectorAll(".modal-content").forEach((modal) => {
      modal.classList.toggle("bg-dark", theme === "dark");
      modal.classList.toggle("text-white", theme === "dark");
    });

    const themeButtonIcon = document
      .getElementById("change-theme")
      .querySelector("i");
    if (theme === "dark") {
      themeButtonIcon.className = "fas fa-moon"; // Beispiel für ein Mond-Icon
    } else {
      themeButtonIcon.className = "fas fa-sun"; // Beispiel für ein Sonnen-Icon
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
