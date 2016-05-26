var api = (function() {

  function sendScore(data) {
    data = data || {};

    // Vérification des données
    if (typeof data.id !== 'number',
      typeof data.time !== 'number',
      typeof data.health !== 'number',
      typeof data.organization !== 'number',
      typeof data.business !== 'number') {
      return false;
    }

    // Envoi des données
    return requestAsync({
      url: '/api/score',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        data: data
      })
    }, function(status, res) {

      if (status === 202) {
        // Récup
      } else {
        console.error(status);
      }
    });
  }

  return {
    sendScore: sendScore
  };
})();
