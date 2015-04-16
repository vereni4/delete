$(document).ready(function() {

  $("#article-languages-en").click(function() {
    
    $("#article-edit-en").show();
    $("#article-edit-ua").hide();
    
    $("#article-languages-en").attr("class", "article-edit-enabled");
    $("#article-languages-ua").attr("class", "article-edit-disabled");
    
  });
  
  $("#article-languages-ua").click(function() {
    
    $("#article-edit-ua").show();
    $("#article-edit-en").hide();
    
    $("#article-languages-ua").attr("class", "article-edit-enabled");
    $("#article-languages-en").attr("class", "article-edit-disabled");
    
  });

  if ($(".article-edit-enabled")) {
    
    $("#article-edit-en").show();
    $("#article-edit-ua").hide();
    
    $("#article-languages-en").attr("class", "article-edit-enabled");
    $("#article-languages-ua").attr("class", "article-edit-disabled");
    
  }
  else {
    
    $("#article-edit-ua").show();
    $("#article-edit-en").hide();
    
    $("#article-languages-ua").attr("class", "article-edit-enabled");
    $("#article-languages-en").attr("class", "article-edit-disabled");
    
  }
})
