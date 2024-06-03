import { GameObject } from './GameObject.js';

export class Enemy extends GameObject {
    constructor(x, y, width, height, color = 'black') {
        super(x, y, width, height, color);
        this.health = 100;
    }

    attack(player) {
        player.energy -= 10;
        if (player.energy < 0) player.energy = 0;
    }
}
