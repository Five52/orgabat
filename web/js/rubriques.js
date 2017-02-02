
(function(window) {
  // Objet des modals
  var categoryModalElement = {
    iframe: document.querySelector('#categoryModal iframe'),
    title: document.querySelector('#categoryModal #categoryModalLabel')
  };
  var gameModalElement = {
    iframe: document.querySelector('#gameModal iframe'),
    title: document.querySelector('#gameModal #gameModalLabel')
  };

  // Preload spinner
  document.createElement('img').src = '/images/loadingSpinner/hex-loader.gif';

  // Init des elementw de rubrique
  var rubriques = document.querySelectorAll('.rubrique');
  for (var r = rubriques.length - 1; r >= 0; r--) {
    rubriques[r].onclick = clickHandler;
  }

  // Quand le modal du jeu se ferme
  $('#gameModal').on('hidden.bs.modal', function(e) {
    $('#categoryModal').modal('show');
  });

  // Config des iframes
  categoryModalElement.iframe.onload = function() {
    categoryModalElement.title.textContent = this.categoryTitle;
  };
  gameModalElement.iframe.onload = function() {
    gameModalElement.title.textContent = this.gameTitle;
  };

  // Handler d'un click sur une rubrique
  function clickHandler(e) {
    e.stopPropagation();
    e.preventDefault();

    // Reset de l'iframe
    categoryModalElement.iframe.src = '';
    categoryModalElement.iframe.contentWindow.document.open();
    categoryModalElement.iframe.contentWindow.document.write('');
    categoryModalElement.iframe.contentWindow.document.close();
    categoryModalElement.title.textContent = 'Chargement...';
    categoryModalElement.iframe.categoryTitle = this.querySelector('#category_title').textContent;

    // Chargement de la page
    categoryModalElement.iframe.src = './' + this.dataset.categoryId;

    // Affichage du modal
    $('#categoryModal').modal({
      keyboard: false
    });
  }

  window.closeExerciseModal = function() {
    $('#categoryModal').modal('hide');
  };

  window.reloadCategoryModal = function() {
    categoryModalElement.iframe.src = categoryModalElement.iframe.src;
  };

  window.loadGame = function(id, title) {
    $('#categoryModal').modal('hide');

    gameModalElement.iframe.gameTitle = title;
    gameModalElement.iframe.src = '/jeu/' + id;

    $('#gameModal').modal({
      keyboard: false
    });
  };
})(window);
