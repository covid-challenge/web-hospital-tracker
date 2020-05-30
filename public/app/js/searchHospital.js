
let SearchHospital = (function(){

    let ui = {};

    function bindUi(){

        this._ui = {
            search: $('.ct-activity__search_input'),
            token: $('.token'),
            container: $('.wt-container'),
            header_container: $('.container_header'),
            header_default: $('.header_default')
        }

        return _ui;
    }

    function bindEvents() {
        $(document).on('keyup', ui.search , searchHospital);
    }

    function OnLoad(){

    }

    function searchHospital(){
      var $url = $(ui.search).data('url');
      var $searchData = $(ui.search).val();
      var $token = $(ui.token).val();

      $.post($url,{"_token": $token,'data': $searchData},function(response){
        var data = response.data;
        var $result = '';

      if($(ui.search).val() != ''){
        $(ui.container).html($result);
        $.each(data, function(key , val){
          $(ui.header_container).removeAttr('style');
          $(ui.header_default).prop('style', 'display:none !important;');
           $result =  `<div class="ct-activity__card hospital__coordinates" data-coordinates="${val.lat + '/' + val.lng}">
                         <div class="content__block card__content">
                            <span>${val.name}</span>
                        </div>
                        <div class="content__block card__content mt-2 ml-2">
                            ${val.address}
                        </div>
                        <div class="content__block card__content ml-2">
                            ${val.status}
                        </div>
                        <div class="content__block card__content mt-2 ml-2">
                            <span class="text-danger">Infected: 12</span>
                        </div>
                      </div>`;
          $(ui.container).append($result);
        });
      }
      else{
        $(ui.header_default).removeAttr('style');
        $(ui.header_container).prop('style', 'display:none !important;');
      }
      });
    }


    function init(){
        ui = bindUi();
        bindEvents();
        OnLoad();
    }

    return {
        init: init,
        _ui: ui
    }

})();

$(document).ready(function(){
    SearchHospital.init();
});
