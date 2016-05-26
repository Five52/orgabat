/**
 * Created by Logan on 26/05/2016.
 */
var Game = Game || {};
Game.Modal = function() {
    this.modal = new gameModal(Game.scene);
    this.createModals();
    this.show("modal1");
};

Game.Modal.prototype.show = function(name) {
    this.modal.showModal(name);
};

Game.Modal.prototype.createModals = function () {
    this.modal.createModal({
        type: "modal1",
        includeBackground: true,
        modalCloseOnInput: true,
        fixedToCamera: true,
        itemsArr: [
            {
                type: "text",
                content: "Nous devons approvisionner le poste de travail. \n" +
                "Pour réaliser ton ouvrage, tu auras besoin du mortier. \n" +
                "Quel matériel vas-tu utiliser ?",
                fontFamily: "Arial",
                fontSize: 26 * Game.scale,
                color: "0xFEFF49",
                offsetY: 0
            }
        ]
    });
};