window.onload = () => {
  // Gestion de la suppression des images au travers des boutons supprimer
  let links = document.querySelectorAll("[data-delete]");
  
  // Boucle sur les links
  for(const link of links){
    // Ecouter le clic
    link.addEventListener('click', function(e) {
      // Empêcher la navigation
      e.preventDefault();

      // Demander confirmation suppression
      if(confirm("Voulez-vous supprimer ce média ? Elle sera supprimer instantanément dès le clic")) {
        // On envoie une requête Ajax vers le href du lien avec la methode DELETE
        fetch(this.getAttribute('href'), {
          method: "DELETE",
          headers: {
            'X-Requested-With': "XMLHttpRequest",
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({'_token': this.dataset.token})
        }).then(
          // On récupère la réponse en json
          response => response.json()
        ).then(data => {
          if(data.success) {
            this.parentElement.remove()
            location.reload();
          } else {
            alert(data.error)
          }
        }).catch(e => alert(e))
      }
    })

  }
}