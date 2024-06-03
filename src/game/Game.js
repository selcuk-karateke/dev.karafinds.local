import { Player } from './Player.js';
import { Obstacle } from './Obstacle.js';
import { Collectible } from './Collectible.js';
import { Resource } from './Resource.js';
import { Inventory } from './Inventory.js';
import { Enemy } from './Enemy.js';
import { getRandomDivisibleBy20 } from '../utils/utils.js';

export class Game {
    constructor() {
        this.canvas = document.getElementById('game-canvas');
        this.ctx = this.canvas.getContext('2d');
        this.gameWidth = 600;
        this.gameHeight = 600;
        this.gridSize = 20;

        this.canvas.width = this.gameWidth;
        this.canvas.height = this.gameHeight;

        this.player = new Player(0, 0, 20, 'blue', this.gameWidth, this.gameHeight);
        this.obstacles = [
            new Obstacle(100, 100, 20, 20),
            new Obstacle(200, 200, 20, 20),
            new Obstacle(300, 300, 20, 20)
        ];
        this.collectibles = [
            new Collectible(40, 40, 20, 20)
        ];
        this.resources = [
            new Resource(160, 160, 20, 20, 'food', 20),
            new Resource(260, 260, 20, 20, 'water', 10)
        ];
        this.enemies = [
            new Enemy(400, 400, 20, 20, 'black')
        ];
        this.level = 1;

        this.init();
    }

    init() {
        this.inventory = new Inventory();
        document.addEventListener('keydown', (event) => this.handleKeydown(event));
        setInterval(() => this.updateEnergy(), 1000);
        this.draw();
    }

    handleKeydown(event) {
        const step = 20;
        switch (event.key) {
            case 'ArrowUp':
                this.player.move(0, -step, 5);
                break;
            case 'ArrowDown':
                this.player.move(0, step, 5);
                break;
            case 'ArrowLeft':
                this.player.move(-step, 0, 5);
                break;
            case 'ArrowRight':
                this.player.move(step, 0, 5);
                break;
        }
        this.checkCollisions();
        this.checkCollections();
        this.checkResourceCollections();
        this.checkEnemyCollisions();
        if (this.resources.length === 0) {
            this.nextLevel();
        }
        this.draw();
    }

    updateEnergy() {
        this.player.regenerateEnergy(1);
        this.draw();
    }

    draw() {
        this.ctx.clearRect(0, 0, this.gameWidth, this.gameHeight);
        this.drawGrid();
        this.player.draw(this.ctx);
        this.player.drawEnergy(this.ctx);
        this.player.drawScore(this.ctx);
        this.obstacles.forEach(obstacle => obstacle.draw(this.ctx));
        this.collectibles.forEach(collectible => collectible.draw(this.ctx));
        this.resources.forEach(resource => resource.draw(this.ctx));
        this.enemies.forEach(enemy => enemy.draw(this.ctx));
    }

    drawGrid() {
        this.ctx.strokeStyle = '#ddd';

        for (let x = 0; x <= this.gameWidth; x += this.gridSize) {
            this.ctx.beginPath();
            this.ctx.moveTo(x, 0);
            this.ctx.lineTo(x, this.gameHeight);
            this.ctx.stroke();
        }

        for (let y = 0; y <= this.gameHeight; y += this.gridSize) {
            this.ctx.beginPath();
            this.ctx.moveTo(0, y);
            this.ctx.lineTo(this.gameWidth, y);
            this.ctx.stroke();
        }
    }

    checkCollisions() {
        this.obstacles.forEach(obstacle => {
            if (this.player.detectCollision(obstacle)) {
                alert('Game Over!');
                this.player.x = 0;
                this.player.y = 0;
            }
        });
    }

    checkCollections() {
        this.collectibles.forEach((collectible, index) => {
            if (this.player.detectCollision(collectible)) {
                this.player.increaseScore(50);
                this.collectibles.splice(index, 1); // Entferne das gesammelte Item
            }
        });
    }

    checkResourceCollections() {
        this.resources.forEach((resource, index) => {
            if (this.player.detectCollision(resource)) {
                this.inventory.addItem(resource);
                this.resources.splice(index, 1); // Entferne die gesammelte Ressource
                this.inventory.displayInventory();
            }
        });
    }

    checkEnemyCollisions() {
        this.enemies.forEach(enemy => {
            if (this.player.detectCollision(enemy)) {
                enemy.attack(this.player);
            }
        });
    }

    nextLevel() {
        this.level += 1;
        document.getElementById('level').innerText = 'Level: ' + this.level;
        this.player.x = 0;
        this.player.y = 0;
        this.obstacles.push(new Obstacle(this.getRandomDivisibleBy20(this.gameWidth), this.getRandomDivisibleBy20(this.gameHeight), 20, 20));
        this.resources.push(new Resource(this.getRandomDivisibleBy20(this.gameWidth), this.getRandomDivisibleBy20(this.gameHeight), 20, 20, 'food', 20));
        this.resources.push(new Resource(this.getRandomDivisibleBy20(this.gameWidth), this.getRandomDivisibleBy20(this.gameHeight), 20, 20, 'water', 10));
    }

    useResource(resourceType) {
        const resource = this.inventory.items.find(item => item.type === resourceType);
        if (resource) {
            this.player.regenerateEnergy(resource.energy);
            this.inventory.removeItem(resource);
            this.inventory.displayInventory();
        }
    }
}
