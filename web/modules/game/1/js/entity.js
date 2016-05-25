/**
 * Created by Logan on 25/05/2016.
 */
var Game = Game || {};
Game.Entities = [
    {
        name: "betonniere", x: 620, y: 464,
        rules: { draggable: true, collider: true }
    },
    {
        name: "brouette", x: 150, y: 464,
        rules: { draggable: true, collider: true }
    }
];

Game.Entity = function(spriteName, x, y, rules) {
    this.sprite = Game.scene.add.sprite(x, y, spriteName);
    if(rules.draggable) this.sprite = this.setDraggable(this.sprite);
    if(rules.collidable) this.sprite = this.setCollider(this.sprite);
};

Game.Entity.prototype.setDraggable = function(sprite) {
    sprite.anchor.set(0.5);
    sprite.inputEnabled = true;
    sprite.input.enableDrag();
    sprite.events.onDragStart.add(this.onDragStart, this);
    sprite.events.onDragStop.add(this.onDragStop, this);
    return sprite;
};

Game.Entity.prototype.setCollider = function(sprite) {
    Game.scene.physics.enable(sprite, Phaser.Physics.ARCADE);
    sprite.body.collideWorldBounds = true;
    return sprite;
};

Game.Entity.prototype.onDragStart = function(sprite, pointer) {
    Game.scene.canvas.style.cursor = "move";
    Game.scene.world.bringToTop(sprite);
    Game.spriteSelected = sprite;
    this.result = "Dragging " + sprite.key;
};

Game.Entity.prototype.onDragStop = function(sprite, pointer) {
    Game.scene.canvas.style.cursor = "default";
    this.result = "Distance: " + this.interact.onDropEntity(sprite);
};