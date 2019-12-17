$(document).ready(function(){

//scroll down chat
var element = document.getElementById("threadMessages");
element.scrollTop = element.scrollHeight - element.clientHeight;

//update array of onckick class
$( document.getElementsByClassName('threadId') ).click(function( event ) {
  event.preventDefault();

  const ReplyToAppended = '>>' + event.currentTarget.name + '>>';
  document.getElementById('thread_form_threadText').value += ReplyToAppended;
});


//fullscreen picture on click
$('img[data-enlargable]').addClass('img-enlargable').click(function(){
  var src = $(this).attr('src');
  $('<div>').css({
      background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
      backgroundSize: 'contain',
      width:'100%', height:'100%',
      position:'fixed',
      zIndex:'10000',
      top:'0', left:'0',                                                     
      cursor: 'zoom-out'                                                     
  }).click(function(){                                                     
      $(this).remove();                                                     
  }).appendTo('body');                                                     
});


document.getElementById('threadText').onkeydown = function(e){                                                     
  //execute on enter click                                                     
  if(e.keyCode == 13){                                                     
    e.preventDefault(); 

    var form = document.getElementById("threadForm");
    var formData = new FormData(form);                                                     
        
      $.ajax({
            type: "POST",
            url: "",
            data: formData, 
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,

            success: function(data)
            {
              //get updated message data
              var parsed = jQuery.parseJSON(data);
              
              if(parsed['threadMediaFile']) {
                uploadMessage(parsed['threadText'], parsed['threadMediaFile'], parsed['id'], parsed['threadId'], parsed['date'])
              }
              else if(parsed['threadText']) {
                uploadMessage(parsed['threadText'], "", parsed['id'], parsed['threadId'], parsed['date'])
              }  
            },
              error: function (data) {
              console.log('An error occurred.');
              console.log(data);
              },
          });
 }
};

function uploadMessage(message, file, id, threadId, date) {
  //creating card html
  var cardTitle = document.createElement("p");
  var node = document.createTextNode("Anonymous " + date);
  cardTitle.appendChild(node);
  cardTitle.setAttribute("class", "card-title");

  var cardId = document.createElement("a");
  var node = document.createTextNode(id);
  cardId.appendChild(node);
  cardId.setAttribute("class", "threadId card-title");
  cardId.setAttribute("name", id);
  cardId.setAttribute("href", threadId + "/#" + id);

  var headerDiv = document.createElement("div");
  headerDiv.setAttribute("class", "row");
  headerDiv.appendChild(cardTitle);
  headerDiv.appendChild(cardId);

  var imgDiv = document.createElement("div");

  if(file) {
    var cardFileData = document.createElement("img");
    cardFileData.setAttribute("data-enlargable", "");
    cardFileData.setAttribute("src", "/uploads/" + file);
    cardFileData.setAttribute("class", "threadImg rounded float-left img-fluid");
    cardFileData.setAttribute("width","15%");
    cardFileData.setAttribute("height","25%");

    imgDiv.setAttribute("class", "threadImg");
    imgDiv.appendChild(cardFileData);
  }

  var textDiv = document.createElement("div");
  textDiv.innerHTML = message;
  textDiv.setAttribute("class", "threadText");

  var cardBody = document.createElement("div");
  cardBody.setAttribute("class", "card-body");
  cardBody.appendChild(headerDiv);
  cardBody.appendChild(imgDiv);
  cardBody.appendChild(textDiv);

  var card = document.createElement("div");
  card.setAttribute("class", "card");;
  card.appendChild(cardBody);

  var element = document.getElementById("threadMessages");
  element.appendChild(card);        
 
  
  //scroll down chat
  var element = document.getElementById("threadMessages");
  element.scrollTop = element.scrollHeight - element.clientHeight;

  document.getElementById("threadForm").reset();
}


});