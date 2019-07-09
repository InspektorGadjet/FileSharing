$(function() {
    $('.file-well input').on('change', function(e) {
        console.log('change!');
        $(e.target).parent().removeClass('hover');
        $(e.target).parent().addClass('filled');
    });
    $('.file-well input').on('dragover', function(e) {
        console.log('dragover');
        $(e.target).parent().addClass('hover');
    });
    $('.file-well input').on('dragleave', function(e) {
        console.log('dragleave');
        $(e.target).parent().removeClass('hover');
    });
});

$(document).ready(function() {   
    $('a[name=modal]').click(function(e) {
    e.preventDefault();
    var id = $(this).attr('href');
    var maskHeight = $(document).height();
    var maskWidth = $(window).width();
    $('#mask').css({'width':maskWidth,'height':maskHeight});
    $('#mask').fadeIn(1000); 
    $('#mask').fadeTo("slow",0.8); 
    var winH = $(window).height();
    var winW = $(window).width();
    $(id).css('top',  winH/2-$(id).height()/2);
    $(id).css('left', winW/2-$(id).width()/2);
    $(id).fadeIn(2000); 
    });
    $('.window .close').click(function (e) { 
    e.preventDefault();
    $('#mask, .window').hide();
    }); 
    $('#mask').click(function () {
    $(this).hide();
    $('.window').hide();
    });
});


/*function check_form()
{
    if ($('textarea[name=comment]').val() == '') {
        $('.messenger').html('Вы не ввели текст');
    } else {
       $('.comment_form').submit();
    }
}*/
$(function() {
   $('.comment_form').submit(function() {
        if($('textarea[name=comment]').val() == '') {
            $('.messenger').html('Вы не ввели текст');
            event.preventDefault();
        } else {
            alert('Ваш комментарий успешно добавлен');
            return;
        }
   });
});


