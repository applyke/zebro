$(document).ready(function () {
    $("select").change(function () {
        console.log('select');
        if ($('#' + $(this).attr("id") + ' option:selected').val() === '') {
            $(this).parent('div').removeClass('is-dirty');
        } else {
            $(this).parent('div').addClass('is-dirty');
        }
    });
    // $( function() {
    //     $( "#columns" ).sortable();
    //     $( "#columns" ).disableSelection();
    // } );
});


var dragSrcEl = null;
function handleDragStart(e) {
    this.style.opacity = '0.4';
    dragSrcEl = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    this.classList.add('over');
}

function handleDragLeave(e) {
    this.classList.remove('over');
 }

function handleDrop(e) {
      if (e.stopPropagation) {
        e.stopPropagation();
    }

    if (dragSrcEl != this) {
        dragSrcEl.innerHTML = this.innerHTML;
        this.innerHTML = e.dataTransfer.getData('text/html');
    }
    this.style.opacity = '0.9';
    return false;
}

function handleDragEnd(e) {
    this.style.opacity = '0.9';
      [].forEach.call(cols, function (col) {
        col.classList.remove('over');

    });
}

var cols = document.querySelectorAll('#columns .issueColumns');

[].forEach.call(cols, function(col) {
    console.log(col);
    col.addEventListener('dragstart', handleDragStart, false);
    col.addEventListener('dragenter', handleDragEnter, false)
    col.addEventListener('dragover', handleDragOver, false);
    col.addEventListener('dragleave', handleDragLeave, false);
    col.addEventListener('drop', handleDrop, false);
    col.addEventListener('dragend', handleDragEnd, false);
});