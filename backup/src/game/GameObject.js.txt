export class GameObject {
  constructor(x, y, radius, width, height, color) {
    this.x = x;
    this.y = y;
    this.width = width;
    this.radius = radius;
    this.height = height;
    this.color = color;
  }

  draw(ctx) {
    ctx.fillStyle = this.color;
    if (this.constructor.name == "Player") {
      // Beginne einen neuen Pfad
      ctx.beginPath();
      // Verschiebe den Kreis um 10px nach rechts und 10px nach unten
      ctx.arc(
        this.x + this.radius,
        this.y + this.radius,
        this.radius,
        0,
        2 * Math.PI,
        false
      );
      // Fülle den Kreis
      ctx.fill();
      // Optional: Umrande den Kreis
      ctx.strokeStyle = "red";
      ctx.stroke();
    } else {
      // Zeichne ein Rechteck
      ctx.fillRect(this.x, this.y, this.width, this.height);
    }
  }

  detectCollision(other) {
    return (
      this.x < other.x + other.width &&
      this.x + this.width > other.x &&
      this.y < other.y + other.height &&
      this.y + this.height > other.y
    );
  }
}
