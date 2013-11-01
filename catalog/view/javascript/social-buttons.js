$(document).ready(function(){
    // Add Sharing Icons
    fb_link = $('<a href="#" class="sb blue flat facebook">Facebook</a>');
    fb_link.bind("click", function(){
        window.open(
      'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 
      'facebook-share-dialog', 
      'width=626,height=436');
        return false;
    });
    $('#sharethis').append(fb_link);
    tt_link = $('<a href="#" class="sb light-blue flat twitter">Twitter</a>');
    tt_link.bind("click", function(){
        window.open(
      'https://twitter.com/share?url='+encodeURIComponent(location.href), 
      'twitter-share-dialog', 
      'width=626,height=436');
        return false;
    });
    $('#sharethis').append(tt_link);
    gp_link = $('<a href="#" class="sb red flat google">Google+</a>');
    gp_link.bind("click", function(){
        window.open(
      'https://plus.google.com/share?url='+encodeURIComponent(location.href), 
      'google-share-dialog', 
      'width=626,height=436');
        return false;
    });
    $('#sharethis').append(gp_link);
});