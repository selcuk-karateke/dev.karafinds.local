export class Inventory {
    constructor() {
        this.items = [];
    }

    addItem(item) {
        this.items.push(item);
    }

    removeItem(item) {
        const index = this.items.indexOf(item);
        if (index > -1) {
            this.items.splice(index, 1);
        }
    }

    getItems() {
        return this.items;
    }

    displayInventory() {
        const inventoryDiv = document.getElementById('inventory');
        inventoryDiv.innerHTML = ''; // Clear current inventory
        this.items.forEach(item => {
            const itemDiv = document.createElement('div');
            itemDiv.textContent = `${item.type}: +${item.energy}`;
            inventoryDiv.appendChild(itemDiv);
        });
    }
}
