import { GameObject } from './GameObject.js';

export class Resource extends GameObject {
    constructor(x, y, width, height, type, energy) {
        let color = type === 'food' ? 'orange' : 'blue';
        super(x, y, null, width, height, color);
        this.type = type;
        this.energy = energy;
    }
}
