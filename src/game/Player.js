import { GameObject } from "./GameObject.js";

export class Player extends GameObject {
  constructor(x, y, size, radius, color, gameWidth, gameHeight) {
    super(x, y, radius, size, size, radius, color);
    this.energy = 100;
    this.score = 0;
    this.gameWidth = gameWidth;
    this.gameHeight = gameHeight;
    this.targetX = x; // Anfangszielposition setzen
    this.targetY = y;
    this.radius = radius;
  }

  move(dx, dy, energyCost) {
    // Berechne die neue Zielposition
    let newX = this.targetX + dx;
    let newY = this.targetY + dy;

    // Überprüfe, ob die neue Zielposition innerhalb des Spielfelds liegt
    if (
      newX >= 0 &&
      newX <= this.gameWidth - this.width &&
      newY >= 0 &&
      newY <= this.gameHeight - this.height &&
      this.energy > energyCost
    ) {
      // Aktualisiere die Zielpositionen
      this.targetX = newX;
      this.targetY = newY;

      // Ziehe Energiekosten nur ab, wenn die Bewegung tatsächlich erfolgt
      this.energy -= energyCost;
    } else {
      // Optional: Benachrichtige den Spieler, dass keine Bewegung möglich ist
      console.log("Bewegung nicht möglich oder nicht genug Energie!");
    }
  }

  // Lineare Interpolation (Lerp) Funktion
  lerp(start, end, t) {
    return start + (end - start) * t;
  }
  // Methode zum kontinuierlichen Aktualisieren der Spielerposition
  updatePosition() {
    let smoothingFactor = 0.1;
    //   console.log(
    //     `Before Lerp: x=${this.x}, y=${this.y}, targetX=${this.targetX}, targetY=${this.targetY}`
    //   );
    let minDistance = 1; // Mindestdistanz, unter der direkt zum Ziel gesprungen wird

    this.x = this.lerp(this.x, this.targetX, smoothingFactor);
    this.y = this.lerp(this.y, this.targetY, smoothingFactor);
    //   // console.log(`After Lerp: x=${this.x}, y=${this.y}`);

    // Überprüfe, ob der Spieler nah genug am Ziel ist, und setze ihn direkt darauf
    if (Math.abs(this.targetX - this.x) < minDistance) {
      this.x = this.targetX;
    }
    if (Math.abs(this.targetY - this.y) < minDistance) {
      this.y = this.targetY;
    }
  }

  regenerateEnergy(rate) {
    this.energy = Math.min(this.energy + rate, 100);
  }

  increaseScore(points) {
    this.score += points;
  }

  drawEnergy() {
    document.getElementById("energy").innerText = "Energie: " + this.energy;
  }

  drawScore() {
    document.getElementById("score").innerText = "Score: " + this.score;
  }

  checkCollision() {
    if (this.x < 0) this.x = 0;
    if (this.x + this.width > this.gameWidth)
      this.x = this.gameWidth - this.width;
    if (this.y < 0) this.y = 0;
    if (this.y + this.height > this.gameHeight)
      this.y = this.gameHeight - this.height;
  }
}
