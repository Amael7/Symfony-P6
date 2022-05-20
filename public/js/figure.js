// $("#add-video").click(function(){
//   // Recuperation du numero des futurs champs que je vais créer
//   const index = +$("#widgets-counter-video").val();

//   // Recuperation du prototype des entrées
//   // AddEventListener sur le click etc...
//   const tmpl = $("#figure_videos").data("prototype").replace(/__name__/g, index);

//   // Injection du code du prototype au sein de la div
//   $("#figure_videos").append(tmpl);

//   $("#widgets-counter-video").val(index + 1);

//   // Gestion du bouton Supprimer
//   handleDeleteButton();
// });

// function updateCounter() {
//   const countVideo = +$("#figure_videos div.form-group").length;

//   $("#widgets-counter-video").val(countVideo);
// }





// document.getElementById('add-video').on('click', function () {
//   console.log("add-video function");
//   var index = document.getElementById('video-counter').val();
//   var tmpl = document.getElementsByClassName('videos').data('prototype').replace(/__name__/g, index);
//   document.getElementById('figure_videos').append(tmpl);
//   document.getElementById('video-counter').val(index + 1);
//   displayCounter();
// });

// function displayCounter() {
//   console.log("displaycounter function");
//   var countVideo = document.getElementsByClassName('videos div.form-group').length;
//   var counterVideo = countVideo + '/8';
//   document.getElementsByClassName('counter-video').text(counterVideo);

//   if (countVideo >= 8) {
//     document.getElementById('add-video').disabled = true;
//     alert('vous pouvez charger jusqu\'à 4 vidéos');
//   } else {
//     document.getElementById('add-video').show();
//   }
// }