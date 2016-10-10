(function($){
  $.fn.calendarwidget = function(options)
  {
    var settings = $.extend({
      readOnly:false
    },options);

    return this.each(function () {
      var $instance;
      var datasource = $(this).data('datasource');
      var toggleurl = $(this).data('toggle');
      var modalurl = $(this).data('modal');

      var errorEvent = function(html) {
        console.log(html,'Erreur');
      }

      var displayModal = function(html) {
        let $wrap  = $('<div>').addClass('modal-wrap');
        let $div   = $('<div>').addClass('modal');
        let $close = $('<a>').addClass('close').html('&times;');
        $close.on('click',function(){
          $close.off('click');
          $wrap.removeClass('transition');
          setTimeout(function(){
            $wrap.remove();
          },500);
        });
        $div.append($close);
        $div.append(html);
        $('body').append($wrap);
        $wrap.append($div);
        setTimeout(function(){
          $wrap.addClass('transition');
        },100);
      }

      var openModal = function() {
        let $this = $(this);
        let data = {
          date:$this.closest('ul').data('date'),
          plage:$this.hasClass('am')?'am':'pm',
          active:$this.hasClass('active')?1:0
        }
        $.ajax({
          url:modalurl,
          data:data,
          method:'POST',
          success:displayModal,
          error:errorEvent
        })
      }

      var placeEvent = function(json) {
        $.each(json.events,function(indice,element) {
          if (element.message) {
            alert(element.message);
          } else {
            let $block = $instance.find('.day[data-date='+element.date+'] .' + element.plage);
            if (element.active)
            {
              $block.addClass((element.disabled)?'disabled':'active');
            } else {
              $block.removeClass('active');
            }
          }
        });
      }

      var updateEvent = function() {
        let $this = $(this);
        let data = {
          date:$this.closest('ul').data('date'),
          plage:$this.hasClass('am')?'am':'pm',
          active:$this.hasClass('active')?1:0
        }
        $.ajax({
          url:toggleurl,
          data:data,
          method:'POST',
          success:placeEvent,
          error:errorEvent
        });
      }

      $instance = $(this);
      $.ajax({
        url:datasource,
        success:placeEvent,
        error:errorEvent
      });
      if (!settings.readOnly)
      {
        if (modalurl) {
          $(this).on('click','.active,.disabled',openModal);
        } else {
          $(this).on('click','.am,.pm',updateEvent);
        }
      }
    });
  }
}(jQuery));


