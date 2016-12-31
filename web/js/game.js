var Api = function(url) {
  this.url = url || '/api/score';
};

Api.prototype.sendScore = function(data, cb) {
    // Vérification des données
    data = data || {};
    if (typeof data.id !== 'number',
      typeof data.time !== 'number',
      typeof data.health !== 'number',
      typeof data.organization !== 'number',
      typeof data.business !== 'number') {
      return false;
    }

    console.log(this.url);

    // Envoi des données
    return requestAsync({
      url: this.url,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ data: data })
    }, function(status, res) {
      return status === 202 ? cb(res) : console.error(status);
    });
};
