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