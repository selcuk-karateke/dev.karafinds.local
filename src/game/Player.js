import { GameObject } from './GameObject.js';

export class Player extends GameObject {
    constructor(x, y, size, color) {
        super(x, y, size, size, color);
        this.energy = 100;
        this.score = 0;
    }

    move(dx, dy, energyCost) {
        if (this.energy > 0) {
            this.x += dx;
            this.y += dy;
            this.energy -= energyCost;
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
}
