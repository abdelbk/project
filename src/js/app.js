/*
@author Abdelkader Benkhadra
*/

jQuery(function($) {

// Slide between the content divs
$('#nav-container').children().click(function() {
    if($(this).next().length == 0) {
      showOnScreen('#clients');
    }
    else if($(this).prev().length == 0) {
      showOnScreen('#users');
    }
    else {
      showOnScreen('#visits');
    }
});


// Show the div if it's off screen
function showOnScreen(id) {
    var content = $(id), // The desired div to be shown
        y = content.offset().left - 150; // ensure that the hidden div is completely off screen
    // If the div is hidden then show it and hide the active one
    if(content.offset().left < 0) {
        content.animate({ "left": 0}, "slow", function() {
          $('.active').animate({ "left": y}, "slow" );
          content.addClass('active').siblings().removeClass('active');
          }
    )};
};

function tooltip(id, t) {
  $(id).mouseenter(function() {
    var offset = $(this).offset(), // Get the hovered div's offset
        midlle = $(this).width() / 2, // Get the middle of the width of the hovered div
        tooltip_mid = $('#tooltip').width() / 2 + 3,  // Make a more precise horizontal position
        tooltip_top = offset.top - 30; // The tooltip vertical position
    
    // Animate the tooltip
    $('#tooltip').text(t).css({'top':tooltip_top, 
                               'left':offset.left + midlle - tooltip_mid,
                               'opacity' : '1',
                               WebkitTransition: 'all 0.3s ease',
                               MozTransition:    'all 0.3s ease',
                               MsTransition:     'all 0.3s ease',
                               OTransition:      'all 0.3s ease',
                               transition : 'all 0.3s ease'
                              }).show();
  });

  $(id).mouseleave(function() {
    $('#tooltip').css('opacity', '0');
  });
};


tooltip('#u-nav', 'utilisateurs');
tooltip('#c-nav', 'clients');
tooltip('#v-nav', 'visites');
 
});