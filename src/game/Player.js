import { GameObject } from './GameObject.js';

export class Player extends GameObject {
    constructor(x, y, size, color, gameWidth, gameHeight) {
        super(x, y, size, size, color);
        this.energy = 100;
        this.score = 0;
        this.gameWidth = gameWidth;
        this.gameHeight = gameHeight;
    }

    move(dx, dy, energyCost) {
        if (this.energy > 0) {
            this.x += dx;
            this.y += dy;
            this.energy -= energyCost;
            this.checkCollision();
        } else {
            alert('Nicht genug Energie!');
        }
    }

    regenerateEnergy(rate) {
        this.energy = Math.min(this.energy + rate, 100);
    }

    increaseScore(points) {
        this.score += points;
    }

    drawEnergy() {
        document.getElementById('energy').innerText = 'Energie: ' + this.energy;
    }

    drawScore() {
        document.getElementById('score').innerText = 'Score: ' + this.score;
    }

    checkCollision() {
        if (this.x < 0) this.x = 0;
        if (this.x + this.width > this.gameWidth) this.x = this.gameWidth - this.width;
        if (this.y < 0) this.y = 0;
        if (this.y + this.height > this.gameHeight) this.y = this.gameHeight - this.height;
    }
}
