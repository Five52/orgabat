(function() {
  var gameModalElement = {
    iframe: document.querySelector('#gameModal iframe'),
    title: document.querySelector('#gameModal #gameModalLabel')
  };


  // Preload spinner
  document.createElement('img').src = '/images/loadingSpinner/hex-loader.gif';

  // Init des element de rubrique
  var rubriques = document.querySelectorAll('.rubrique');
  for (var r = rubriques.length - 1; r >= 0; r--) {
    rubriques[r].onclick = clickHandler;
  }

  // Quand le modal se ferme
  $('#gameModal').on('hidden.bs.modal', function(e) {
    gameModalElement.iframe.src = '';
    gameModalElement.iframe.onload = undefined;
    gameModalElement.title.textContent = 'Chargement...';
  });

  // Handler d'un click sur une rubrique
  function clickHandler(e) {
    e.stopPropagation();
    e.preventDefault();

    $('#gameModal').modal({
      keyboard: false
    });

    gameModalElement.iframe.gameTitle = this.querySelector('#game_title').textContent;
    gameModalElement.iframe.onload = function() {
      gameModalElement.title.textContent = this.gameTitle;
    };

    gameModalElement.iframe.src = '/jeu/' + this.dataset.gameId;
  }

})();
