function changeButtonsStylesWithSearch(buttons,filters){
    
        buttons.addClass("pull-left");
    
        filters.addClass("pull-right");
        filters.css("margin-top","15px");
    
        buttons.find(".btn.btn-default").each(function( index ) {
                var text = $(this).text().toUpperCase();
                var imgSrc = "";
                if ($(this).find(".glyphicon-plus").length){
                    imgSrc = "/static/img/ico-add.png";
                }
                else if($(this).find(".glyphicon-tree-deciduous").length){
                    imgSrc = "/static/img/3376842887-mini-ico-relationships.png";
                }
    
                $(this).html('<img src="'+imgSrc+'" alt="info" height="30" width="30">'+text);
        });
    
        buttons.find(".btn.btn-default").css("font-weight","600").css("background-color","#f5f5f5").css("border-radius","0px").css("border","0px").css("color","#bfbfbf");
    
        buttons.css("padding-bottom","10px");
    
        buttons.find(".btn.btn-default").hover(
                    function(){
                        var $this = $(this);
                        $this.data('bgcolor', $this.css('background-color')).css('background-color', '#d9d9d9');
                    },
                    function(){
                        var $this = $(this);
                        $this.css('background-color', $this.data('bgcolor'));
                    }
                );
    
    }