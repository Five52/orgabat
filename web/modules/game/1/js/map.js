/**
 * Created by Logan on 26/05/2016.
 */
var Game = Game || {};
Game.MapProperty = {
    name: "map",
    calque: "Calque de Tile 1",
    file: "map.json",
    entities: [
        {
            name: "wall", file: "Spr_wall.png"
        },
        {
            name: "dirt", file: "dirt.png"
        },
        {
            name: "ground", file: "ground.png"
        },
        {
            name: "road", file: "road.png"
        }
    ]
};

Game.Map = function() {
    this._map = Game.scene.add.tilemap(Game.MapProperty.name);
    for(var i in Game.MapProperty.entities)
        this._map.addTilesetImage(Game.MapProperty.entities[i].name);
    this.setLayer();
};

Game.Map.prototype.setLayer = function() {
    this.layer = this._map.createLayer(
        Game.MapProperty.calque,
        Game.scene.world.width / Game.scale,
        Game.scene.world.height / Game.scale
    );
    this.layer.setScale(Game.scale);
    this.layer.resizeWorld();
    this._map.setCollisionBetween(1, 1);
    //self.layer.debug = true;
};